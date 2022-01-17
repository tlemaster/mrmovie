<?php

namespace App\Repository;

use App\Entity\ApiAttribute;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApiAttribute|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApiAttribute|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApiAttribute[]    findAll()
 * @method ApiAttribute[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApiAttributeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApiAttribute::class);
    }

    // /**
    //  * @return ApiAttribute[] Returns an array of ApiAttribute objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApiAttribute
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
