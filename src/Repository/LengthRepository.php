<?php

namespace App\Repository;

use App\Entity\Length;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Tlc\ManualBundle\Repository\StandardLengthRepository as BaseStandardLengthRepository;

/**
 * @method Length|null find($id, $lockMode = null, $lockVersion = null)
 * @method Length|null findOneBy(array $criteria, array $orderBy = null)
 * @method Length[]    findAll()
 * @method Length[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LengthRepository extends BaseStandardLengthRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        $this->nameClass = Length::class;
        parent::__construct($registry);
    }
}
