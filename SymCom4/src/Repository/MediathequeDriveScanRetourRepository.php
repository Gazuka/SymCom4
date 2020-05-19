<?php

namespace App\Repository;

use App\Entity\MediathequeDriveScanRetour;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediathequeDriveScanRetour|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediathequeDriveScanRetour|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediathequeDriveScanRetour[]    findAll()
 * @method MediathequeDriveScanRetour[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediathequeDriveScanRetourRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediathequeDriveScanRetour::class);
    }

    // /**
    //  * @return MediathequeDriveScanRetour[] Returns an array of MediathequeDriveScanRetour objects
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
    public function findOneBySomeField($value): ?MediathequeDriveScanRetour
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function findNonTraite()
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.traite = false')
            ->orderBy('m.dateScan', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findLastNonTraite()
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.traite = false')
            ->orderBy('m.dateScan', 'DESC')
            ->setMaxResults(11)
            ->getQuery()
            ->getResult()
        ;
    }
}
