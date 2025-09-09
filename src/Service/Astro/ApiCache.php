<?php
namespace App\Service\Astro;

use App\Repository\ApiCacheEntryRepository;
use App\Entity\ApiCacheEntry;
use Doctrine\ORM\EntityManagerInterface;

class ApiCache
{
    public function __construct(private ApiCacheEntryRepository $repo, private EntityManagerInterface $em) {}

    public function getOrSet(string $key, int $ttlSeconds, callable $producer): array
    {
        $existing = $this->repo->findValid($key);
        if ($existing) {
            $payload = json_decode($existing->getPayload(), true);
            if (is_array($payload)) { return $payload; }
        }
        $data = $producer();
        $expires = new \DateTimeImmutable('+'.$ttlSeconds.' seconds');
        $entry = new ApiCacheEntry($key, json_encode($data, JSON_UNESCAPED_UNICODE), $expires);
        $this->em->persist($entry);
        $this->em->flush();
        return $data;
    }
}
