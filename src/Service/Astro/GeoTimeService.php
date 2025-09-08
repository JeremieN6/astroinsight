<?php
namespace App\Service\Astro;

/**
 * Dev-only stub service for geocoding + timezone inference without external calls.
 * Returns a fixed coordinate for known city substrings; otherwise Paris.
 */
class GeoTimeService
{
    public function geocode(string $place): array
    {
        $p = mb_strtolower($place);
        if (str_contains($p, 'paris')) return ['lat' => 48.8566, 'lon' => 2.3522, 'tz' => 'Europe/Paris'];
        if (str_contains($p, 'london')) return ['lat' => 51.5072, 'lon' => -0.1276, 'tz' => 'Europe/London'];
        if (str_contains($p, 'new york')) return ['lat' => 40.7128, 'lon' => -74.0060, 'tz' => 'America/New_York'];
        return ['lat' => 48.8566, 'lon' => 2.3522, 'tz' => 'Europe/Paris'];
    }
}
