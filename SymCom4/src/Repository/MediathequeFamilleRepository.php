<?php

namespace App\Repository;

use App\Entity\MediathequeFamille;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediathequeFamille|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediathequeFamille|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediathequeFamille[]    findAll()
 * @method MediathequeFamille[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediathequeFamilleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediathequeFamille::class);
    }

    // /**
    //  * @return MediathequeFamille[] Returns an array of MediathequeFamille objects
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
    public function findOneBySomeField($value): ?MediathequeFamille
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
