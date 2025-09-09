<?php
namespace App\Repository;

use App\Entity\DailyHoroscope;
use App\Entity\Users;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<DailyHoroscope> */
class DailyHoroscopeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    { parent::__construct($registry, DailyHoroscope::class); }

    public function findOneForUserDate(Users $user, \DateTimeInterface $date): ?DailyHoroscope
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.user = :u')
            ->andWhere('d.date = :d')
            ->setParameter('u', $user)
            ->setParameter('d', $date->format('Y-m-d'))
            ->getQuery()->getOneOrNullResult();
    }
}
