<?php
namespace App\Service\Astro;

use App\Entity\AstroProfile;
use DateTimeInterface;

interface HoroscopeGeneratorInterface
{
    /**
     * Generate (or compose) textual + scoring horoscope data structure.
     * Should NOT persist; returns associative array with: scores, summary, aspects.
     */
    public function generate(AstroProfile $profile, DateTimeInterface $date): array;
}
