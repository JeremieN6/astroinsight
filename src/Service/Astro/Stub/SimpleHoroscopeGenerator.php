<?php
namespace App\Service\Astro\Stub;

use App\Entity\AstroProfile;
use App\Service\Astro\HoroscopeGeneratorInterface;
use DateTimeInterface;

class SimpleHoroscopeGenerator implements HoroscopeGeneratorInterface
{
    public function generate(AstroProfile $profile, DateTimeInterface $date): array
    {
        // Stub logic: random-ish repeatable scores based on user id + date hash
        $seed = crc32(($profile->getId() ?? 0) . $date->format('Ymd'));
        mt_srand($seed);
        $scores = [
            'focus' => mt_rand(30, 90),
            'emotion' => mt_rand(30, 90),
            'energy' => mt_rand(30, 90),
        ];
        $summary = sprintf(
            "Journée propice à la réflexion intérieure. Ton niveau de focus est à %d%%, pense à canaliser ton énergie (%d%%) dans une tâche clé avant midi.",
            $scores['focus'],
            $scores['energy']
        );
        return [
            'scores' => $scores,
            'summary' => $summary,
            'aspects' => [
                ['planet' => 'Sun', 'aspect' => 'trine', 'target' => 'Moon', 'strength' => 0.6],
            ],
        ];
    }
}
