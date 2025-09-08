<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\AstroProfileRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AstroProfileRepository::class)]
#[ORM\Table(name: 'astro_profile')]
class AstroProfile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'astroProfile', targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Users $user = null;

    // Raw birth data
    #[ORM\Column(type: 'date', nullable: true)]
    #[Assert\LessThanOrEqual('today')]
    private ?\DateTimeInterface $birthDate = null; // date part only

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $birthTime = null; // time part only (local time)

    #[ORM\Column(length: 120, nullable: true)]
    private ?string $birthPlace = null; // Free text original place name

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\Range(min: -90, max: 90)]
    private ?float $latitude = null;

    #[ORM\Column(type: 'float', nullable: true)]
    #[Assert\Range(min: -180, max: 180)]
    private ?float $longitude = null;

    #[ORM\Column(length: 64, nullable: true)]
    #[Assert\Length(max: 64)]
    private ?string $timezone = null; // Olson TZ ID

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20)]
    private ?string $accuracy = null; // e.g. exact, approx, unknown

    // Cached computed chart core (JSON for quick access, can be regenerated if engine changes)
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $natalChartJson = null; // planets positions, houses, aspects summary

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $lastComputedAt = null;

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?Users { return $this->user; }
    public function setUser(Users $user): self { $this->user = $user; return $this; }

    public function getBirthDate(): ?\DateTimeInterface { return $this->birthDate; }
    public function setBirthDate(?\DateTimeInterface $birthDate): self { $this->birthDate = $birthDate; return $this; }

    public function getBirthTime(): ?\DateTimeInterface { return $this->birthTime; }
    public function setBirthTime(?\DateTimeInterface $birthTime): self { $this->birthTime = $birthTime; return $this; }

    public function getBirthPlace(): ?string { return $this->birthPlace; }
    public function setBirthPlace(?string $birthPlace): self { $this->birthPlace = $birthPlace; return $this; }

    public function getLatitude(): ?float { return $this->latitude; }
    public function setLatitude(?float $latitude): self { $this->latitude = $latitude; return $this; }

    public function getLongitude(): ?float { return $this->longitude; }
    public function setLongitude(?float $longitude): self { $this->longitude = $longitude; return $this; }

    public function getTimezone(): ?string { return $this->timezone; }
    public function setTimezone(?string $timezone): self { $this->timezone = $timezone; return $this; }

    public function getAccuracy(): ?string { return $this->accuracy; }
    public function setAccuracy(?string $accuracy): self { $this->accuracy = $accuracy; return $this; }

    public function getNatalChartJson(): ?string { return $this->natalChartJson; }
    public function setNatalChartJson(?string $natalChartJson): self { $this->natalChartJson = $natalChartJson; return $this; }

    public function getLastComputedAt(): ?\DateTimeInterface { return $this->lastComputedAt; }
    public function setLastComputedAt(?\DateTimeInterface $lastComputedAt): self { $this->lastComputedAt = $lastComputedAt; return $this; }
}
