<?php

namespace App\Repository;

use App\Entity\MediathequeMembre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediathequeMembre|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediathequeMembre|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediathequeMembre[]    findAll()
 * @method MediathequeMembre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediathequeMembreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediathequeMembre::class);
    }

    // /**
    //  * @return MediathequeMembre[] Returns an array of MediathequeMembre objects
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
