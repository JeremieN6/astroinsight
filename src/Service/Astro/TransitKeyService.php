<?php
namespace App\Service\Astro;

use App\Entity\AstroProfile;
use DateTimeImmutable;

class TransitKeyService
{
    /**
     * Stub: returns a fixed upcoming transit window for demo with simple heuristic.
     */
    public function nextKeyTransit(AstroProfile $profile): array
    {
        $now = new DateTimeImmutable();
        return [
            'label' => 'Momentum créatif',
            'planet' => 'Sun',
            'target' => 'Moon',
            'aspect' => 'trine',
            'from' => $now->modify('+1 day'),
            'exact' => $now->modify('+2 days'),
            'to' => $now->modify('+3 days'),
            'advice' => 'Planifie tâches de conception ou brainstorming durant le pic.',
            'score' => 78,
        ];
    }
}
