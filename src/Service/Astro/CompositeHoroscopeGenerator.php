<?php
namespace App\Service\Astro;

use App\Entity\AstroProfile;
use App\Service\Astro\Prokerala\ProkeralaApiClient;
use DateTimeInterface;
use Psr\Log\LoggerInterface;

/**
 * Compose horoscope data by combining Prokerala daily advanced + internal aspect computation.
 */
class CompositeHoroscopeGenerator implements HoroscopeGeneratorInterface
{
    public function __construct(
        private ProkeralaApiClient $client,
        private AspectComputer $aspectComputer,
        private LoggerInterface $logger,
    ) {}

    public function generate(AstroProfile $profile, DateTimeInterface $date): array
    {
        // 1. Determine sign from natalChartJson if exists
        $sign = 'aries';
        if ($profile->getNatalChartJson()) {
            try { $chart = json_decode($profile->getNatalChartJson(), true); if (isset($chart['sun']['sign'])) { $sign = strtolower($chart['sun']['sign']); } } catch (\Throwable $e) {}
        }
        $api = [];
        try {
            try { $api = $this->client->getAdvancedDailyHoroscope($sign); }
            catch (\Throwable $adv) { $api = $this->client->getDailyHoroscope($sign); }
        } catch (\Throwable $e) {
            $this->logger->warning('CompositeHoroscopeGenerator API failure', ['error' => $e->getMessage()]);
        }
        $core = $api['data'] ?? $api;
        $summary = $core['summary'] ?? ($core['general'] ?? 'Tonalité générale stable, avance méthodiquement.');
        $scores = [
            'focus' => $core['scores']['focus'] ?? 55,
            'emotion' => $core['scores']['emotion'] ?? 55,
            'energy' => $core['scores']['energy'] ?? 55,
        ];
        $apiAspects = $core['aspects'] ?? [];

        // 2. Internal aspects if natal positions exist
        $internalAspects = [];
        if ($profile->getNatalChartJson()) {
            try {
                $chart = json_decode($profile->getNatalChartJson(), true);
                $natalPositions = [];
                foreach (['sun','moon','mercury','venus','mars'] as $p) {
                    if (isset($chart[$p]['degree'])) { $natalPositions[strtoupper($p)] = (float)$chart[$p]['degree']; }
                }
                // NOTE: On n'a pas (encore) les positions du jour par API -> si aspects API absents on ne peut calculer; placeholder.
            } catch (\Throwable $e) {}
        }
        // Merge aspects lists
        $aspects = $apiAspects ?: $internalAspects;
        return [ 'scores' => $scores, 'summary' => $summary, 'aspects' => $aspects ];
    }
}
