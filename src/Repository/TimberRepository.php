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
    private function getBaseQueryFromPeriod(DatePeriod $period):QueryBuilder
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.drec BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_RFC3339_EXTENDED))
            ->setParameter('end', $period->getEndDate()->format(DATE_RFC3339_EXTENDED))
            ->leftJoin('t.species', 's');
            // ->orderBy('t.drec', 'ASC');
    }
    
    public function findVolumeTimberByPeriod(DatePeriod $period)
    {
        $qb = $this->getBaseQueryFromPeriod($period);
        return $qb
            ->select(
                        's.name as name_species',
                        't.diam',
                        'standard_length(t.length) as st_length',
                        'count(1) as count_timber',
                        'sum(volume_timber (t.length, t.diam)) AS volume_boards'
                    )
            ->addGroupBy('name_species', 't.diam', 'st_length' )
            ->addOrderBy('name_species, t.diam, st_length')
            ->getQuery()
            ->getResult();
    }    
    
    public function findVolumeTimberFromPostavByPeriod(DatePeriod $period)
    {
        $qb = $this->getBaseQueryFromPeriod($period);
        return $qb
            ->select(
                        "CASE WHEN get_json_filed_by_key(p.postav, 'name' ) = '' THEN
                            get_json_filed_by_key(p.postav, 'name')
                        ELSE
                            p.comm
                        END AS name_postav",
                        // "p.postav AS name_postav",
                        "get_json_filed_by_key(p.postav, 'top' ) AS diam_postav",
                        's.name as name_species',
                        'standard_length (t.length) AS st_length',
                        'unnest(t.boards) AS cut',
                        'count(1) AS count_timber',
                        'volume_boards (unnest(t.boards), t.length) AS volume_boards'
                    )
            ->leftJoin('t.postav', 'p')
            ->addGroupBy('name_postav', 'diam_postav', 'name_species', 'cut', 'st_length', 'volume_boards' )
            ->addOrderBy('diam_postav, st_length, name_species')
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
