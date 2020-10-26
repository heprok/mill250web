<?php

namespace App\Repository;

use App\Entity\Postav;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Postav|null find($id, $lockMode = null, $lockVersion = null)
 * @method Postav|null findOneBy(array $criteria, array $orderBy = null)
 * @method Postav[]    findAll()
 * @method Postav[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PostavRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Postav::class);
    }

    // /**
    //  * @return Postav[] Returns an array of Postav objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Postav
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
