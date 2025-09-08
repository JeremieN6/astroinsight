<?php
namespace App\Service\Astro;

use App\Entity\AstroProfile;
use DateTimeInterface;

interface TransitAnalyzerInterface
{
    /**
     * Analyze current transits for a user profile at a given date.
     * Return array of transit events with keys: planet, aspect, target, strength, start, exact, end.
     */
    public function analyze(AstroProfile $profile, DateTimeInterface $dt): array;
}
