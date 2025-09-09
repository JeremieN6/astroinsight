<?php
namespace App\Service\Astro;

/**
 * Simple mapping-based interpreter turning a list of aspects into human phrases.
 * Input aspects example: [{"p1":"SUN","aspect":"TRINE","p2":"JUPITER","score":85}, ...]
 */
class AspectInterpreter
{
    /** @var array<string,string> */
    private array $specificMap = [
        'SUN|CONJUNCTION|MERCURY' => "Clarté mentale accrue : exprime une idée maintenant.",
        'MOON|TRINE|VENUS' => "Harmonie émotionnelle, bon moment pour nourrir un lien.",
        'MARS|SQUARE|SATURN' => "Friction énergie / limites : avance par petits blocs.",
        'JUPITER|TRINE|SUN' => "Expansion personnelle : ose formuler une ambition concrète.",
        'SATURN|OPPOSITION|MOON' => "Besoin de structurer tes émotions, ritualise un ancrage." ,
    ];

    /** @var array<string,string> */
    private array $aspectFallback = [
        'TRINE' => 'Flux harmonieux à exploiter.',
        'CONJUNCTION' => 'Concentration d’énergie : focalise.',
        'SQUARE' => 'Tension constructive : clarifie un blocage.',
        'OPPOSITION' => 'Polarité à équilibrer.',
        'SEXTILE' => 'Ouverture légère : opportunité douce.',
    ];

    /**
     * @param array<int,array<string,mixed>> $aspects
     * @return string[] max 3 insights
     */
    public function interpret(array $aspects, int $limit = 3): array
    {
        // Sort by score desc if present
        usort($aspects, function($a,$b){ return ($b['score'] ?? 0) <=> ($a['score'] ?? 0); });
        $phrases = [];
        foreach ($aspects as $asp) {
            $key = ($asp['p1'] ?? '').'|'.($asp['aspect'] ?? '').'|'.($asp['p2'] ?? '');
            $phrase = $this->specificMap[$key] ?? $this->aspectFallback[$asp['aspect'] ?? ''] ?? null;
            if ($phrase) { $phrases[] = $phrase; }
            if (count($phrases) >= $limit) break;
        }
        if (!$phrases) { $phrases[] = 'Énergie générale neutre, concentre-toi sur la clarté et la simplicité.'; }
        return $phrases;
    }
}
