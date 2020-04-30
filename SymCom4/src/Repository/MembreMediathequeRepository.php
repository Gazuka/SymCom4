<?php

namespace App\Repository;

use App\Entity\MembreMediatheque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MembreMediatheque|null find($id, $lockMode = null, $lockVersion = null)
 * @method MembreMediatheque|null findOneBy(array $criteria, array $orderBy = null)
 * @method MembreMediatheque[]    findAll()
 * @method MembreMediatheque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MembreMediathequeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MembreMediatheque::class);
    }

    // /**
    //  * @return MembreMediatheque[] Returns an array of MembreMediatheque objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MembreMediatheque
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
