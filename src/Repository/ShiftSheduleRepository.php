<?php

namespace App\Repository;

use App\Entity\ShiftShedule;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ShiftShedule|null find($id, $lockMode = null, $lockVersion = null)
 * @method ShiftShedule|null findOneBy(array $criteria, array $orderBy = null)
 * @method ShiftShedule[]    findAll()
 * @method ShiftShedule[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ShiftSheduleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ShiftShedule::class);
    }

    // /**
    //  * @return ShiftShedule[] Returns an array of ShiftShedule objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ShiftShedule
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
