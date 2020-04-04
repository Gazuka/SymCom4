<?php

namespace App\Repository;

use App\Entity\Humain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Humain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Humain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Humain[]    findAll()
 * @method Humain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HumainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Humain::class);
    }

    // /**
    //  * @return Humain[] Returns an array of Humain objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('h.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Humain
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
