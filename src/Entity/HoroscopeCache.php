<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\HoroscopeCacheRepository;

#[ORM\Entity(repositoryClass: HoroscopeCacheRepository::class)]
#[ORM\Table(name: 'horoscope_cache')]
#[ORM\Index(name: 'idx_user_date', columns: ['user_id', 'date'])]
class HoroscopeCache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Users $user = null;

    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date = null; // UTC date of horoscope

    #[ORM\Column(type: 'string', length: 20, nullable: true)]
    private ?string $scope = 'daily'; // daily, weekly, monthly

    #[ORM\Column(type: 'json')]
    private array $scores = []; // e.g. {"focus": 65, "emotion": 40}

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $summary = null; // main textual summary

    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $aspects = null; // list of influencing aspects for transparency

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $generatedAt = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isFinal = false; // if false, can be regenerated (e.g., stub -> real)

    public function getId(): ?int { return $this->id; }

    public function getUser(): ?Users { return $this->user; }
    public function setUser(Users $user): self { $this->user = $user; return $this; }

    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): self { $this->date = $date; return $this; }

    public function getScope(): ?string { return $this->scope; }
    public function setScope(?string $scope): self { $this->scope = $scope; return $this; }

    public function getScores(): array { return $this->scores; }
    public function setScores(array $scores): self { $this->scores = $scores; return $this; }

    public function getSummary(): ?string { return $this->summary; }
    public function setSummary(?string $summary): self { $this->summary = $summary; return $this; }

    public function getAspects(): ?array { return $this->aspects; }
    public function setAspects(?array $aspects): self { $this->aspects = $aspects; return $this; }

    public function getGeneratedAt(): ?\DateTimeInterface { return $this->generatedAt; }
    public function setGeneratedAt(?\DateTimeInterface $generatedAt): self { $this->generatedAt = $generatedAt; return $this; }

    public function isFinal(): bool { return $this->isFinal; }
    public function setIsFinal(bool $isFinal): self { $this->isFinal = $isFinal; return $this; }
}
