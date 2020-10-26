<?php

namespace App\Repository;

use App\Entity\Timber;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Timber|null find($id, $lockMode = null, $lockVersion = null)
 * @method Timber|null findOneBy(array $criteria, array $orderBy = null)
 * @method Timber[]    findAll()
 * @method Timber[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TimberRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Timber::class);
    }

    /**
     * Подготавливает запрос для периода
     *
     * @param DatePeriod $period
     * @return QueryBuilder
     */
    private function getQueryFromPeriod(DatePeriod $period):QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.drecTimestampKey BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_ATOM))
            ->setParameter('end', $period->getEndDate()->format(DATE_ATOM))
            ->orderBy('t.drecTimestampKey', 'ASC');
    }

    public function findVolumeTimberByPeriod(DatePeriod $period)
    {
        $qb = $this->createQueryBuilder('t');
        return $qb
            ->select(
                        's.name',
                        't.diam',
                        'standard_length(t.length) as length',
                        'count(1) as count_timber',
                        'sum(volume_timber (t.length, t.diam)) AS volume_boards'
                    )
            ->leftJoin('t.species', 's')
            ->andWhere('t.drec BETWEEN :start AND :end')
            ->groupBy('s.name', 't.diam', 'length' )
            ->setParameter('start', $period->start->format(DATE_RFC3339_EXTENDED))
            ->setParameter('end', $period->end->format(DATE_RFC3339_EXTENDED))
            ->orderBy('s.name, t.diam, length')
            ->getQuery()
            ->getResult();
    }

    // /**
    //  * @return Timber[] Returns an array of Timber objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Timber
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
