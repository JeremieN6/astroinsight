<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\GeoCacheEntryRepository;

#[ORM\Entity(repositoryClass: GeoCacheEntryRepository::class)]
#[ORM\Table(name: 'geo_cache_entry')]
#[ORM\UniqueConstraint(name: 'uniq_geo_hash', columns: ['hash'])]
class GeoCacheEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    private string $hash; // sha1(normalized place)

    #[ORM\Column(length: 160)]
    private string $placeOriginal;

    #[ORM\Column(type: 'float')]
    private float $lat;

    #[ORM\Column(type: 'float')]
    private float $lon;

    #[ORM\Column(length: 64)]
    private string $timezone;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    public function __construct(string $hash, string $placeOriginal, float $lat, float $lon, string $timezone)
    {
        $this->hash = $hash; $this->placeOriginal = $placeOriginal; $this->lat = $lat; $this->lon = $lon; $this->timezone = $timezone; $this->createdAt = new \DateTimeImmutable();
    }
    public function getLat(): float { return $this->lat; }
    public function getLon(): float { return $this->lon; }
    public function getTimezone(): string { return $this->timezone; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
}
