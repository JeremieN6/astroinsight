<?php
namespace App\Service\Astro\Prokerala;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Psr\Log\LoggerInterface;

class ProkeralaApiClient
{
    private ?string $accessToken = null;
    private ?int $expiresAt = null;

    public function __construct(
        private HttpClientInterface $httpClient,
        private LoggerInterface $logger,
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

    private function get(string $path, array $query): array
    {
        $this->authenticate();
        $response = $this->httpClient->request('GET', $this->baseUrl.$path, [
            'headers' => [ 'Authorization' => 'Bearer '.$this->accessToken ],
            'query' => $query,
        ]);
        return $response->toArray(false);
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
}
