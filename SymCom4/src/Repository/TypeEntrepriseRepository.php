<?php

namespace App\Repository;

use App\Entity\TypeEntreprise;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method TypeEntreprise|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeEntreprise|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeEntreprise[]    findAll()
 * @method TypeEntreprise[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeEntrepriseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeEntreprise::class);
    }

    // /**
    //  * @return TypeEntreprise[] Returns an array of TypeEntreprise objects
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
    public function findOneBySomeField($value): ?TypeEntreprise
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
