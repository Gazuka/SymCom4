<?php

namespace App\Repository;

use App\Entity\MediathequeDriveCommande;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediathequeDriveCommande|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediathequeDriveCommande|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediathequeDriveCommande[]    findAll()
 * @method MediathequeDriveCommande[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediathequeDriveCommandeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediathequeDriveCommande::class);
    }

    // /**
    //  * @return MediathequeDriveCommande[] Returns an array of MediathequeDriveCommande objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MediathequeDriveCommande
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
