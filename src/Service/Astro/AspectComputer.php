<?php
namespace App\Service\Astro;

/**
 * Derive aspects between natal and current positions.
 * Natal / current arrays format expected: ['planets' => ['SUN' => deg, 'MOON' => deg, ...]] (0-360)
 */
class AspectComputer
{
    /** @var array<string,int> */
    private array $aspectAngles = [
        'CONJUNCTION' => 0,
        'SEXTILE' => 60,
        'SQUARE' => 90,
        'TRINE' => 120,
        'OPPOSITION' => 180,
    ];

    public function __construct(private float $orbMajor = 6.0, private float $orbMinor = 4.0) {}

    /**
     * @param array $natal ['SUN'=>deg,...]
     * @param array $current ['SUN'=>deg,...]
     * @return array<int,array<string,mixed>>
     */
    public function compute(array $natal, array $current): array
    {
        $out = [];
        $personal = ['SUN','MOON','MERCURY','VENUS','MARS'];
        foreach ($personal as $p1) {
            if (!isset($natal[$p1])) continue;
            foreach ($current as $p2 => $deg2) {
                if ($p1 === $p2) continue;
                $deg1 = $natal[$p1];
                $delta = $this->angleDiff($deg1, $deg2);
                foreach ($this->aspectAngles as $name => $angle) {
                    $diff = abs($delta - $angle);
                    $orb = in_array($name, ['CONJUNCTION','OPPOSITION','TRINE']) ? $this->orbMajor : $this->orbMinor;
                    if ($diff <= $orb) {
                        $score = (int) max(5, (100 * (1 - ($diff / $orb))));
                        $out[] = [ 'p1' => $p1, 'p2' => $p2, 'aspect' => $name, 'orb' => round($diff,2), 'score' => $score ];
                    }
                }
            }
        }
        usort($out, fn($a,$b)=> $b['score'] <=> $a['score']);
        return $out;
    }

    private function angleDiff(float $a, float $b): float
    {
        $d = abs($a - $b) % 360;
        return $d > 180 ? 360 - $d : $d;
    }
}
