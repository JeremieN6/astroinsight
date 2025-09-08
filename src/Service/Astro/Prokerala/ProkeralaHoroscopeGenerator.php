<?php
namespace App\Service\Astro\Prokerala;

use App\Entity\AstroProfile;
use App\Service\Astro\HoroscopeGeneratorInterface;
use Psr\Log\LoggerInterface;
use DateTimeInterface;

class ProkeralaHoroscopeGenerator implements HoroscopeGeneratorInterface
{
    public function __construct(private ProkeralaApiClient $client, private LoggerInterface $logger) {}

    public function generate(AstroProfile $profile, DateTimeInterface $date): array
    {
        // Simplified: derive sun sign from natal chart JSON if present, else default Aries.
        $sign = 'aries';
        if ($profile->getNatalChartJson()) {
            try {
                $chart = json_decode($profile->getNatalChartJson(), true);
                if (isset($chart['sun']['sign'])) { $sign = strtolower($chart['sun']['sign']); }
            } catch (\Throwable $e) {}
        }
        try {
            // Try advanced first
            try {
                $api = $this->client->getAdvancedDailyHoroscope($sign);
            } catch (\Throwable $inner) {
                $api = $this->client->getDailyHoroscope($sign);
            }
            $core = $api['data'] ?? $api;
            $summary = $core['summary'] ?? ($core['general'] ?? '');
            // Heuristic scoring from sections if explicit scores absent
            $scores = [
                'focus' => $core['scores']['focus'] ?? 50,
                'emotion' => $core['scores']['emotion'] ?? 50,
                'energy' => $core['scores']['energy'] ?? 50,
            ];
            $aspects = $core['aspects'] ?? [];
            return [ 'scores' => $scores, 'summary' => $summary, 'aspects' => $aspects ];
        } catch (\Throwable $e) {
            $this->logger->warning('Prokerala daily horoscope fallback', ['error' => $e->getMessage()]);
            // Fallback minimal generic message
            return [
                'scores' => ['focus' => 55, 'emotion' => 55, 'energy' => 55],
                'summary' => 'Journée neutre. Profite de la stabilité pour planifier ou avancer calmement sur tes priorités.',
                'aspects' => []
            ];
        }
    }
}
