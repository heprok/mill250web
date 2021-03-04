<?php

namespace App\Repository;

use App\Entity\Timber;
use DatePeriod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
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
    private function getBaseQueryFromPeriod(DatePeriod $period, array $sqlWhere = []): QueryBuilder
    {
        $qb = $this->createQueryBuilder('t')
            ->where('t.drec BETWEEN :start AND :end')
            ->setParameter('start', $period->getStartDate()->format(DATE_RFC3339_EXTENDED))
            ->setParameter('end', $period->getEndDate()->format(DATE_RFC3339_EXTENDED))
            ->leftJoin('t.species', 's')
            ->leftJoin('t.postav', 'p');

        foreach ($sqlWhere as $where) {
            $query = $where->nameTable . $where->id . ' ' . $where->operator . ' ' . $where->value;
            if ($where->logicalOperator == 'AND')
                $qb->andWhere($query);
            else
                $qb->orWhere($query);
        }
        return $qb;

        // ->orderBy('t.drec', 'ASC');
    }
    /**
     * @return Timber[]
     */
    public function findByPeriod(DatePeriod $period, array $sqlWhere = []): array
    {
        return $this->getBaseQueryFromPeriod($period, $sqlWhere)
            ->addSelect('volume_timber(t.length, t.diam) as volume_timber')
            ->addSelect('standard_length(t.length) as standart_length')
            ->setMaxResults(1000)
            ->getQuery()
            ->getResult();
    }
    
    public function getCountBoardsByPeriod(DatePeriod $period, array $sqlWhere = []): int
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select('sum(array_length(t.boards, 1)) as count_boards')
            ->getQuery()
            ->getResult()[0]['count_boards'] ?? 0;
    }


    public function getCountTimberByPeriod(DatePeriod $period, array $sqlWhere = []): int
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select('count(1) as count_timber')
            ->getQuery()
            ->getResult()[0]['count_timber'] ?? 0;
    }

    public function getVolumeTimberByPeriod(DatePeriod $period, array $sqlWhere = []): float
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select('sum(volume_timber(t.length, t.diam)) as volume_timber')
            ->getQuery()
            ->getResult()[0]['volume_timber'] ?? 0;
    }    
    
    public function getCountBoardsByPeriodSimpleSql(DatePeriod $period, array $sqlWhere = []): int
    {

        $addWhereSql = $this->getStringSqlWhere($sqlWhere);
        $sql =
            "SELECT
            sum(array_length(t.boards, 1)) AS count_boards
        FROM
            mill.timber t
            LEFT JOIN mill.postav AS p ON (t.postav_id = p.id)
            LEFT JOIN dic.species AS s ON (t.species_id = s.id)
        WHERE 
            t.drec BETWEEN :start AND :end $addWhereSql
        ";
        $params = [
            'start' => $period->getStartDate()->format(DATE_RFC3339_EXTENDED),
            'end' => $period->getEndDate()->format(DATE_RFC3339_EXTENDED),
        ];
        $query = $this->getEntityManager()->getConnection()->prepare($sql);
        $query->execute($params);
        return $query->fetchAllAssociative()[0]['count_boards'] ?? 0;
    }


    public function getCountTimberByPeriodSimpleSql(DatePeriod $period, array $sqlWhere = []): int
    {
        $addWhereSql = $this->getStringSqlWhere($sqlWhere);
        $sql =
            "SELECT
            count(1) AS count_timber
        FROM
            mill.timber t
            LEFT JOIN mill.postav AS p ON (t.postav_id = p.id)
            LEFT JOIN dic.species AS s ON (t.species_id = s.id)
        WHERE 
            t.drec BETWEEN :start AND :end $addWhereSql
        ";
        $params = [
            'start' => $period->getStartDate()->format(DATE_RFC3339_EXTENDED),
            'end' => $period->getEndDate()->format(DATE_RFC3339_EXTENDED),
        ];
        $query = $this->getEntityManager()->getConnection()->prepare($sql);
        $query->execute($params);
        return $query->fetchAllAssociative()[0]['count_timber'] ?? 0;
    }

    public function getVolumeTimberByPeriodSimpleSql(DatePeriod $period, array $sqlWhere = []): float
    {
        $addWhereSql = $this->getStringSqlWhere($sqlWhere);
        $sql =
            "SELECT
            sum(mill.volume_timber(t.length, t.diam)) as volume_timber
        FROM
            mill.timber t
            LEFT JOIN mill.postav AS p ON (t.postav_id = p.id)
            LEFT JOIN dic.species AS s ON (t.species_id = s.id)
        WHERE 
            t.drec BETWEEN :start AND :end $addWhereSql
        ";
        $params = [
            'start' => $period->getStartDate()->format(DATE_RFC3339_EXTENDED),
            'end' => $period->getEndDate()->format(DATE_RFC3339_EXTENDED),
        ];
        $query = $this->getEntityManager()->getConnection()->prepare($sql);
        $query->execute($params);
        return $query->fetchAllAssociative()[0]['volume_timber'] ?? 0;
    }

    public function getVolumeBoardsByPeriod(DatePeriod $period, array $sqlWhere = []): float
    {
        $newSqlWhere = [];
        // ( CAST(p.postav->'top' AS float) / 10 )
        $regExp = [];
        $regExp[] = '/(get_int_into_by_key)\((.\..*),\s\'([A-Za-z]+)\'\).*/m';
        $regExp[] = '/(standard_length)\(([A-Za-z\.]+)\).*/m';
        foreach($sqlWhere as $where ){
            $newWhere = clone $where;
                $matches = [];
                preg_match_all($regExp[0], $where->id, $matches, PREG_SET_ORDER);
                if($matches){
                    $match = $matches[0];
                    $fullResult = $match[0];
                    $function = $match[1];
                    $column = $match[2];
                    $key = $match[3];
                    $fullResult = str_replace($function, 'CAST', $fullResult);
                    $fullResult = str_replace(', ', '->>', $fullResult);
                    $fullResult = str_replace("'$key'", "'$key' AS FLOAT", $fullResult);
                    $newWhere->id =  '('. $fullResult . ')';
                }                
                
                $matches = [];
                preg_match_all($regExp[1], $where->id, $matches, PREG_SET_ORDER);
                if($matches){
                    $match = $matches[0];
                    $fullResult = $match[0];
                    $function = $match[1];
                    $column = $match[2];
                    $fullResult = str_replace($function, 'mill.' . $function, $fullResult);
                    $newWhere->id =  '('. $fullResult . ')';
                }
            $newSqlWhere[] = $newWhere;
        }

        // Print the entire match result

        $addWhereSql = $this->getStringSqlWhere($newSqlWhere);
        $sql =
            "SELECT
            sum(mill.volume_boards (bo, t.length)) AS volume_boards
        FROM
            mill.timber t
        LEFT JOIN mill.postav AS p ON (t.postav_id = p.id)
        LEFT JOIN dic.species AS s ON (t.species_id = s.id)
        CROSS JOIN unnest(boards) as bo
        WHERE 
            t.drec BETWEEN :start AND :end $addWhereSql
        ";
        $params = [
            'start' => $period->getStartDate()->format(DATE_RFC3339_EXTENDED),
            'end' => $period->getEndDate()->format(DATE_RFC3339_EXTENDED),
        ];
        $query = $this->getEntityManager()->getConnection()->prepare($sql);
        $query->execute($params);
        return $query->fetchAllAssociative()[0]['volume_boards'] ?? 0;
    }

    public function getReportVolumeTimberByPeriod(DatePeriod $period, array $sqlWhere = [])
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select(
                's.name as name_species',
                't.diam',
                'standard_length(t.length) as st_length',
                'count(1) as count_timber',
                'sum(volume_timber (t.length, t.diam)) AS volume_boards'
            )
            ->addOrderBy('name_species, t.diam, st_length')
            ->addGroupBy('name_species', 't.diam', 'st_length')
            ->getQuery()
            ->getResult();
    }

    public function getReportVolumeBoardFromPostavByPeriod(DatePeriod $period, array $sqlWhere = [])
    {
        $qb = $this->getBaseQueryFromPeriod($period, $sqlWhere);
        return $qb
            ->select(
                "CASE WHEN get_json_filed_by_key(p.postav, 'name' ) = '' THEN
                            p.comm
                        ELSE
                            get_json_filed_by_key(p.postav, 'name')
                        END AS name_postav",
                // "p.postav AS name_postav",
                "get_json_filed_by_key(p.postav, 'top' ) AS diam_postav",
                's.name as name_species',
                'standard_length (t.length) AS st_length',
                'unnest(t.boards) AS cut',
                'count(1) AS count_board',
                'volume_boards (unnest(t.boards), t.length) AS volume_boards'
            )
            ->addGroupBy('name_postav', 'diam_postav', 'name_species', 'cut', 'st_length', 'volume_boards')
            ->addOrderBy('diam_postav, st_length, name_species')
            ->getQuery()
            ->getResult();
    }

    public function getStringSqlWhere(array $sqlWhere)
    {
        $addWhereSql = '';
        if ($sqlWhere) {
            $addWhereSql = 'AND ';
            foreach ($sqlWhere as $key => $where) {
                $addWhereSql .= 
                    $where->nameTable .
                    $where->id . ' ' .
                    $where->operator . ' ' .
                    $where->value . ' ' .
                    (($key == count($sqlWhere) - 1) ? '' : $where->logicalOperator)  . ' ';
            }
        }
        return $addWhereSql;
    }   
    public function getReportVolumeTimberFromPostavByPeriod(DatePeriod $period, array $sqlWhere = [])
    {
        $addWhereSql = $this-> getStringSqlWhere($sqlWhere);
        $sql =
            "SELECT
            max(CASE WHEN p.postav->>'name' = '' THEN
                p.comm
            ELSE
                p.postav->>'name'
            END) AS name_postav,
            p.postav->>'top' AS diam_postav,
            s.name AS name_species,
            diam as diam_timber,
            max(o.min_date) AS start_date,
            max(o.max_date) AS end_date,
            count(1) AS count_timber,
            sum(mill.volume_timber (length, diam)) AS volume_timber
        FROM
            mill.timber t
            LEFT JOIN mill.postav AS p ON (t.postav_id = p.id)
            LEFT JOIN dic.species AS s ON (t.species_id = s.id)
        LEFT JOIN (
            SELECT
                COALESCE(postav_id, - 1) AS postav_id,
                COALESCE(species_id, '_-') AS species_id,
                min(t.drec) AS min_date,
                max(t.drec) AS max_date
            FROM
                mill.timber t
            WHERE 
                t.drec BETWEEN :start AND :end
            GROUP BY
                t.postav_id,
                t.species_id 
            ) AS o 
            ON COALESCE(t.postav_id, - 1) = o.postav_id
            AND COALESCE(t.species_id, '_-') = o.species_id
        WHERE 
            t.drec BETWEEN :start AND :end $addWhereSql
        GROUP BY
            t.postav_id,
            diam_postav,
            name_species,
            diam_timber
        ORDER BY
            t.postav_id,
            diam_postav,
            name_species,
            diam_timber
        ";
        $params = [
            'start' => $period->getStartDate()->format(DATE_RFC3339_EXTENDED),
            'end' => $period->getEndDate()->format(DATE_RFC3339_EXTENDED),
        ];
        $query = $this->getEntityManager()->getConnection()->prepare($sql);
        $query->execute($params);
        return $query->fetchAllAssociative();
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
