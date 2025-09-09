<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ApiCacheEntryRepository;

#[ORM\Entity(repositoryClass: ApiCacheEntryRepository::class)]
#[ORM\Table(name: 'api_cache_entry')]
#[ORM\Index(name: 'idx_api_cache_expires', columns: ['expires_at'])]
#[ORM\UniqueConstraint(name: 'uniq_api_cache_key', columns: ['cache_key'])]
class ApiCacheEntry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 190)]
    private string $cacheKey; // hash of endpoint+query

    #[ORM\Column(type: 'text')]
    private string $payload; // JSON string

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $createdAt;

    #[ORM\Column(type: 'datetime')]
    private \DateTimeInterface $expiresAt;

    public function __construct(string $key, string $payload, \DateTimeInterface $expiresAt)
    {
        $this->cacheKey = $key;
        $this->payload = $payload;
        $this->createdAt = new \DateTimeImmutable();
        $this->expiresAt = $expiresAt;
    }

    public function getId(): ?int { return $this->id; }
    public function getCacheKey(): string { return $this->cacheKey; }
    public function getPayload(): string { return $this->payload; }
    public function getCreatedAt(): \DateTimeInterface { return $this->createdAt; }
    public function getExpiresAt(): \DateTimeInterface { return $this->expiresAt; }
    public function isExpired(): bool { return $this->expiresAt < new \DateTimeImmutable(); }
}
