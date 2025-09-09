<?php
namespace App\Service\Astro;

use App\Repository\GeoCacheEntryRepository;
use App\Entity\GeoCacheEntry;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class GeoTimeService
{
    public function __construct(
        private GeoCacheEntryRepository $repo,
        private EntityManagerInterface $em,
        private ?HttpClientInterface $httpClient = null,
        private bool $enableRemote = false,
        private ?string $timezoneApiKey = null,
        private ?LoggerInterface $logger = null,
    ) {}

    public function geocode(string $place): array
    {
        $norm = mb_strtolower(trim($place));
        $hash = sha1($norm);
        if ($cached = $this->repo->findRecentByHash($hash)) {
            return ['lat' => $cached->getLat(), 'lon' => $cached->getLon(), 'tz' => $cached->getTimezone(), 'cached' => true];
        }
        // Optional remote lookup
        if ($this->enableRemote && $this->httpClient) {
            try {
                $response = $this->httpClient->request('GET', 'https://nominatim.openstreetmap.org/search', [
                    'query' => ['format' => 'json', 'limit' => 1, 'q' => $place],
                    'headers' => ['User-Agent' => 'AstroInsight/1.0 (contact@example.com)']
                ]);
                $arr = $response->toArray(false);
                if (is_array($arr) && isset($arr[0]['lat'], $arr[0]['lon'])) {
                    $lat = (float)$arr[0]['lat']; $lon = (float)$arr[0]['lon'];
                    $tz = $this->resolveTimezone($lat, $lon);
                    $entry = new GeoCacheEntry($hash, $place, $lat, $lon, $tz);
                    $this->em->persist($entry); $this->em->flush();
                    return ['lat' => $lat, 'lon' => $lon, 'tz' => $tz, 'cached' => false];
                }
            } catch (\Throwable $e) { if ($this->logger) { $this->logger->notice('Geocode remote failed', ['err'=>$e->getMessage()]); } }
        }
        // Fallback stub logic
        $p = $norm;
        if (str_contains($p, 'paris')) { $lat=48.8566;$lon=2.3522;$tz='Europe/Paris'; }
        elseif (str_contains($p, 'london')) { $lat=51.5072;$lon=-0.1276;$tz='Europe/London'; }
        elseif (str_contains($p, 'new york')) { $lat=40.7128;$lon=-74.0060;$tz='America/New_York'; }
        else { $lat=48.8566;$lon=2.3522;$tz='Europe/Paris'; }
        $entry = new GeoCacheEntry($hash, $place, $lat, $lon, $tz);
        $this->em->persist($entry); $this->em->flush();
        return ['lat'=>$lat,'lon'=>$lon,'tz'=>$tz,'cached'=>false,'fallback'=>true];
    }

    private function resolveTimezone(float $lat, float $lon): string
    {
        // External API if key present
        if ($this->timezoneApiKey && $this->httpClient) {
            try {
                $resp = $this->httpClient->request('GET', 'http://api.timezonedb.com/v2.1/get-time-zone', [
                    'query' => [
                        'format' => 'json',
                        'by' => 'position',
                        'lat' => $lat,
                        'lng' => $lon,
                        'key' => $this->timezoneApiKey,
                    ]
                ]);
                $tzData = $resp->toArray(false);
                if (($tzData['status'] ?? '') === 'OK' && !empty($tzData['zoneName'])) { return $tzData['zoneName']; }
            } catch (\Throwable $e) { if ($this->logger) { $this->logger->info('Timezone lookup failed', ['err'=>$e->getMessage()]); } }
        }
        return 'UTC';
    }
}

