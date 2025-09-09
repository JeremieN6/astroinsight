<?php
namespace App\Entity;

use App\Repository\DailyHoroscopeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DailyHoroscopeRepository::class)]
#[ORM\Table(name: 'daily_horoscope')]
#[ORM\UniqueConstraint(name: 'uniq_daily_user_date', columns: ['user_id','date'])]
#[ORM\Index(name: 'idx_daily_user_date', columns: ['user_id','date'])]
class DailyHoroscope
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Users::class)]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Users $user = null;

    // Always stored as UTC date (no time component)
    #[ORM\Column(type: 'date')]
    private ?\DateTimeInterface $date = null;

    // Primary textual summary already human-friendly
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $summary = null;

    // Additional interpreted bullet points (max ~3) - stored as JSON array of strings
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $insights = null;

    // Next key transit lightweight object {date: Y-m-d, label: string, score:int}
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $nextTransit = null;

    // Raw aspects / technical data (optional) kept for debug transparency
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $rawData = null;

    // Scores (focus, emotion, energy, social...) kept separate for UI gauges
    #[ORM\Column(type: 'json', nullable: true)]
    private ?array $scores = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $generatedAt = null;

    // If final = true we won't recompute automatically except explicit invalidate
    #[ORM\Column(type: 'boolean')]
    private bool $final = false;

    public function getId(): ?int { return $this->id; }
    public function getUser(): ?Users { return $this->user; }
    public function setUser(Users $user): self { $this->user = $user; return $this; }
    public function getDate(): ?\DateTimeInterface { return $this->date; }
    public function setDate(\DateTimeInterface $date): self { $this->date = $date; return $this; }
    public function getSummary(): ?string { return $this->summary; }
    public function setSummary(?string $summary): self { $this->summary = $summary; return $this; }
    public function getInsights(): ?array { return $this->insights; }
    public function setInsights(?array $insights): self { $this->insights = $insights; return $this; }
    public function getNextTransit(): ?array { return $this->nextTransit; }
    public function setNextTransit(?array $nextTransit): self { $this->nextTransit = $nextTransit; return $this; }
    public function getRawData(): ?array { return $this->rawData; }
    public function setRawData(?array $rawData): self { $this->rawData = $rawData; return $this; }
    public function getScores(): ?array { return $this->scores; }
    public function setScores(?array $scores): self { $this->scores = $scores; return $this; }
    public function getGeneratedAt(): ?\DateTimeInterface { return $this->generatedAt; }
    public function setGeneratedAt(?\DateTimeInterface $generatedAt): self { $this->generatedAt = $generatedAt; return $this; }
    public function isFinal(): bool { return $this->final; }
    public function setFinal(bool $final): self { $this->final = $final; return $this; }
}
