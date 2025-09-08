<?php

namespace App\Repository;

use App\Entity\HoroscopeCache;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<HoroscopeCache>
 */
class HoroscopeCacheRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, HoroscopeCache::class);
    }

    public function findForUserAndDate(Users $user, \DateTimeInterface $date, string $scope = 'daily'): ?HoroscopeCache
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.user = :user')
            ->andWhere('h.date = :date')
            ->andWhere('h.scope = :scope')
            ->setParameters([
                'user' => $user,
                'date' => $date->format('Y-m-d'),
                'scope' => $scope,
            ])
            ->getQuery()
            ->getOneOrNullResult();
    }
}
