<?php
namespace App\Service\Astro;

use DateTimeInterface;

interface AstroEphemerisProviderInterface
{
    /**
     * Return planetary positions (geocentric) for given datetime UTC.
     * Output structure example:
     * [ 'Sun' => ['lon' => 123.45, 'lat' => 0.0, 'speed' => 1.0], ...]
     */
    public function getPositions(DateTimeInterface $dt): array;
}
