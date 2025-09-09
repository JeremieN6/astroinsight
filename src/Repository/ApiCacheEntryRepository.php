<?php
namespace App\Repository;

use App\Entity\ApiCacheEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<ApiCacheEntry> */
class ApiCacheEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    { parent::__construct($registry, ApiCacheEntry::class); }

    public function findValid(string $key): ?ApiCacheEntry
    {
        $now = new \DateTimeImmutable();
        return $this->createQueryBuilder('c')
            ->andWhere('c.cacheKey = :k')
            ->andWhere('c.expiresAt > :now')
            ->setParameter('k', $key)
            ->setParameter('now', $now)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }

    public function purgeExpired(int $limit = 100): int
    {
        $now = new \DateTimeImmutable();
        return $this->createQueryBuilder('c')
            ->delete()
            ->andWhere('c.expiresAt <= :now')
            ->setParameter('now', $now)
            ->getQuery()->execute();
    }
}
