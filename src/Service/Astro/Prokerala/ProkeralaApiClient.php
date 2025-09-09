<?php
namespace App\Service\Astro\Prokerala;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;
use App\Service\Astro\ApiCache;

class ProkeralaApiClient
{
    private ?string $accessToken = null;
    private ?int $expiresAt = null;

    /**
     * TTL mapping in seconds for endpoints. Adjust as needed.
     */
    private array $defaultTtl = [
        '/v2/horoscope/daily' => 1800,            // 30 min
        '/v2/horoscope/daily/advanced' => 1800,
        '/v2/astrology/birth-details' => 2592000, // 30 days
        '/v2/astrology/kundli' => 15552000,       // 180 days
        '/v2/astrology/kundli/advanced' => 15552000,
        '/v2/astrology/mangal-dosha' => 15552000,
        '/v2/astrology/mangal-dosha/advanced' => 15552000,
        '/v2/astrology/kaal-sarp-dosha' => 15552000,
        '/v2/astrology/panchang' => 86400,        // 1 day
        '/v2/astrology/panchang/advanced' => 86400,
        '/v2/astrology/auspicious-period' => 43200,  // 12h
        '/v2/astrology/inauspicious-period' => 43200,
    ];

    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
        private ApiCache $apiCache,
        private string $clientId,
        private string $clientSecret,
        private string $baseUrl = 'https://api.prokerala.com'
    ) {}

    private function authenticate(): void
    {
        if ($this->accessToken && $this->expiresAt && $this->expiresAt > time() + 30) {
            return; // still valid
        }
        $response = $this->httpClient->request('POST', $this->baseUrl.'/token', [
            'body' => [
                'grant_type' => 'client_credentials',
                'client_id' => $this->clientId,
                'client_secret' => $this->clientSecret,
            ],
        ]);
        $data = $response->toArray(false);
        $this->accessToken = $data['access_token'] ?? null;
        $this->expiresAt = isset($data['expires_in']) ? time() + (int)$data['expires_in'] : null;
        if (!$this->accessToken) {
            $this->logger->error('Prokerala auth failed', ['response' => $data]);
            throw new \RuntimeException('Prokerala authentication failed');
        }
    }

    private function get(string $path, array $query, ?int $ttl = null): array
    {
        // Determine TTL
        $ttl = $ttl ?? ($this->defaultTtl[$path] ?? 900); // fallback 15 min
        ksort($query);
        $cacheKey = 'prokerala:'.$path.':'.http_build_query($query);
        return $this->apiCache->getOrSet($cacheKey, $ttl, function () use ($path, $query) {
            $this->authenticate();
            $response = $this->httpClient->request('GET', $this->baseUrl.$path, [
                'headers' => [ 'Authorization' => 'Bearer '.$this->accessToken ],
                'query' => $query,
            ]);
            $arr = $response->toArray(false);
            // On error structure (contains 'error') we avoid caching by throwing
            if (isset($arr['error'])) {
                throw new \RuntimeException('API error: '.json_encode($arr));
            }
            return $arr;
        });
    }

    // Western daily horoscope basic
    public function getDailyHoroscope(string $sign, ?string $timezone = null, ?string $lang = null): array
    {
        $query = ['sign' => $sign];
        if ($timezone) { $query['timezone'] = $timezone; }
        if ($lang) { $query['lang'] = $lang; }
        return $this->get('/v2/horoscope/daily', $query);
    }

    // Western daily horoscope advanced (adds more sections if plan allows)
    public function getAdvancedDailyHoroscope(string $sign, ?string $timezone = null, ?string $lang = null): array
    {
        $query = ['sign' => $sign];
        if ($timezone) { $query['timezone'] = $timezone; }
        if ($lang) { $query['lang'] = $lang; }
        return $this->get('/v2/horoscope/daily/advanced', $query);
    }

    // Birth details (positions etc.)
    public function getBirthDetails(float $lat, float $lon, string $dateTimeIso, string $timezone, int $ayanamsa = 1): array
    {
        // dateTimeIso: 2025-09-08T10:30:00+05:30 or with Z
        return $this->get('/v2/astrology/birth-details', [
            'coordinates' => $lat.','.$lon,
            'datetime' => $dateTimeIso,
            'ayanamsa' => $ayanamsa,
            'timezone' => $timezone,
        ]);
    }

    // Full natal chart / kundli (basic or advanced)
    public function getKundli(float $lat, float $lon, string $dateTimeIso, string $timezone, int $ayanamsa = 1, bool $advanced = false): array
    {
        $path = $advanced ? '/v2/astrology/kundli/advanced' : '/v2/astrology/kundli';
        return $this->get($path, [
            'coordinates' => $lat.','.$lon,
            'datetime' => $dateTimeIso,
            'ayanamsa' => $ayanamsa,
            'timezone' => $timezone,
        ]);
    }

    public function getMangalDosha(float $lat, float $lon, string $dateTimeIso, string $timezone, bool $advanced = false, int $ayanamsa = 1): array
    {
        $path = $advanced ? '/v2/astrology/mangal-dosha/advanced' : '/v2/astrology/mangal-dosha';
        return $this->get($path, [
            'coordinates' => $lat.','.$lon,
            'datetime' => $dateTimeIso,
            'ayanamsa' => $ayanamsa,
            'timezone' => $timezone,
        ]);
    }

    public function getKaalSarpDosha(float $lat, float $lon, string $dateTimeIso, string $timezone, int $ayanamsa = 1): array
    {
        return $this->get('/v2/astrology/kaal-sarp-dosha', [
            'coordinates' => $lat.','.$lon,
            'datetime' => $dateTimeIso,
            'ayanamsa' => $ayanamsa,
            'timezone' => $timezone,
        ]);
    }

    public function getPanchang(float $lat, float $lon, string $dateTimeIso, string $timezone, bool $advanced = false, int $ayanamsa = 1): array
    {
        $path = $advanced ? '/v2/astrology/panchang/advanced' : '/v2/astrology/panchang';
        return $this->get($path, [
            'coordinates' => $lat.','.$lon,
            'datetime' => $dateTimeIso,
            'ayanamsa' => $ayanamsa,
            'timezone' => $timezone,
        ]);
    }

    public function getAuspiciousPeriod(float $lat, float $lon, string $dateTimeIso, string $timezone, int $ayanamsa = 1): array
    {
        return $this->get('/v2/astrology/auspicious-period', [
            'coordinates' => $lat.','.$lon,
            'datetime' => $dateTimeIso,
            'ayanamsa' => $ayanamsa,
            'timezone' => $timezone,
        ]);
    }

    public function getInauspiciousPeriod(float $lat, float $lon, string $dateTimeIso, string $timezone, int $ayanamsa = 1): array
    {
        return $this->get('/v2/astrology/inauspicious-period', [
            'coordinates' => $lat.','.$lon,
            'datetime' => $dateTimeIso,
            'ayanamsa' => $ayanamsa,
            'timezone' => $timezone,
        ]);
    }
}
