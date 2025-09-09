<?php
namespace App\Service\Astro;

use App\Entity\AstroProfile;
use App\Service\Astro\Prokerala\ProkeralaApiClient;
use App\Service\Astro\AspectInterpreter;
use App\Service\Astro\NextTransitFinder;
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
        private AspectInterpreter $aspectInterpreter,
        private NextTransitFinder $nextTransitFinder,
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

        $internalAspects = [];
        $insights = [];
    $nextTransit = null;
    try {
            if ($profile->getLatitude() && $profile->getLongitude() && $profile->getBirthDate()) {
                $birthDt = (new \DateTimeImmutable($profile->getBirthDate()->format('Y-m-d').' '.$profile->getBirthTime()->format('H:i:s')))->setTimezone(new \DateTimeZone('UTC'));
                $birthIso = $birthDt->format('Y-m-d\TH:i:sP');
                $todayIso = (new \DateTimeImmutable('now')) ->format('Y-m-d\T12:00:00P');
                // Natal planet positions (western) may already be stored in natalChartJson; only fetch if absent
                $natalPositions = [];
                if ($profile->getNatalChartJson()) {
                    $chart = json_decode($profile->getNatalChartJson(), true) ?: [];
                    foreach (['sun','moon','mercury','venus','mars'] as $p) {
                        if (isset($chart[$p]['degree'])) { $natalPositions[strtoupper($p)] = (float)$chart[$p]['degree']; }
                    }
                }
                if (!$natalPositions) {
                    $natalApi = $this->client->getNatalPlanetPositions($profile->getLatitude(), $profile->getLongitude(), $birthIso, 'UTC');
                    $natalRaw = $natalApi['data']['planet_positions'] ?? [];
                    foreach ($natalRaw as $row) {
                        if (isset($row['planet']['name'], $row['position']['longitude'])) {
                            $natalPositions[strtoupper($row['planet']['name'])] = (float)$row['position']['longitude'];
                        }
                    }
                }
                // Transit (current positions)
                $transitApi = $this->client->getTransitPlanetPositions($todayIso, 'UTC');
                $transitPositions = [];
                foreach (($transitApi['data']['planet_positions'] ?? []) as $row) {
                    if (isset($row['planet']['name'], $row['position']['longitude'])) {
                        $transitPositions[strtoupper($row['planet']['name'])] = (float)$row['position']['longitude'];
                    }
                }
                if ($natalPositions && $transitPositions) {
                    $internalAspects = $this->aspectComputer->compute($natalPositions, $transitPositions);
                    // Build future horizon (next 7 days) for next transit
                    $future = [];
                    for ($i=1; $i<=7; $i++) {
                        $day = new \DateTimeImmutable('+' . $i . ' day 12:00:00 UTC');
                        $iso = $day->format('Y-m-d\\T12:00:00P');
                        try {
                            $tp = $this->client->getTransitPlanetPositions($iso, 'UTC');
                            $pos = [];
                            foreach (($tp['data']['planet_positions'] ?? []) as $row) {
                                if (isset($row['planet']['name'], $row['position']['longitude'])) {
                                    $pos[strtoupper($row['planet']['name'])] = (float)$row['position']['longitude'];
                                }
                            }
                            if ($pos) { $future[] = ['date' => $day, 'positions' => $pos]; }
                        } catch (\Throwable $e) {
                            // swallow; continue
                        }
                    }
                    if ($future) { $nextTransit = $this->nextTransitFinder->find($future, $natalPositions); }
                }
            }
        } catch (\Throwable $e) {
            $this->logger->notice('Aspect computation skipped', ['err' => $e->getMessage()]);
        }

        $aspects = $apiAspects ?: $internalAspects;
    if ($aspects) { $insights = $this->aspectInterpreter->interpret($aspects); }
    return [ 'scores' => $scores, 'summary' => $summary, 'aspects' => $aspects, 'insights' => $insights, 'nextTransit' => $nextTransit ];
    }
}
