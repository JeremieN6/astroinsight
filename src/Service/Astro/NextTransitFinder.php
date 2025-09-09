<?php
namespace App\Service\Astro;

use DateTimeImmutable;

class NextTransitFinder
{
    public function __construct(private AspectComputer $aspectComputer) {}

    /**
     * Simplified: scan provided future days ephemeris for highest score aspect.
     * @param array<int,array{date:DateTimeImmutable, positions:array<string,float>}> $future
     * @param array<string,float> $natal
     * @return array|null {date,label,score}
     */
    public function find(array $future, array $natal): ?array
    {
        $best = null;
        foreach ($future as $day) {
            $aspects = $this->aspectComputer->compute($natal, $day['positions']);
            if (!$aspects) continue;
            $top = $aspects[0];
            if (!$best || $top['score'] > $best['score']) {
                $best = [
                    'date' => $day['date']->format('Y-m-d'),
                    'label' => $top['p1'].' '.$top['aspect'].' '.$top['p2'],
                    'score' => $top['score'],
                ];
            }
        }
        return $best;
    }
}
