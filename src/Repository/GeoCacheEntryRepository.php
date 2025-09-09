<?php
namespace App\Repository;

use App\Entity\GeoCacheEntry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<GeoCacheEntry> */
class GeoCacheEntryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry) { parent::__construct($registry, GeoCacheEntry::class); }

    public function findRecentByHash(string $hash, int $maxAgeDays = 30): ?GeoCacheEntry
    {
        $since = (new \DateTimeImmutable())->modify('-'.$maxAgeDays.' days');
        return $this->createQueryBuilder('g')
            ->andWhere('g.hash = :h')
            ->andWhere('g.createdAt > :since')
            ->setParameter('h', $hash)
            ->setParameter('since', $since)
            ->setMaxResults(1)
            ->getQuery()->getOneOrNullResult();
    }
}
