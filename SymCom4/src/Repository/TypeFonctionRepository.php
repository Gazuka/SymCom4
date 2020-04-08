<?php

namespace App\Repository;

use App\Entity\TypeFonction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TypeFonction|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeFonction|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeFonction[]    findAll()
 * @method TypeFonction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeFonctionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeFonction::class);
    }

    // /**
    //  * @return TypeFonction[] Returns an array of TypeFonction objects
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
    public function findOneBySomeField($value): ?TypeFonction
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
