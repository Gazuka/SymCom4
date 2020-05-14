<?php

namespace App\Repository;

use App\Entity\MediathequeDriveCreneau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MediathequeDriveCreneau|null find($id, $lockMode = null, $lockVersion = null)
 * @method MediathequeDriveCreneau|null findOneBy(array $criteria, array $orderBy = null)
 * @method MediathequeDriveCreneau[]    findAll()
 * @method MediathequeDriveCreneau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MediathequeDriveCreneauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MediathequeDriveCreneau::class);
    }

    public function findAllinFutur($date, $limit)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.debut >= :val')
            ->setParameter('val', $date)
            ->orderBy('c.debut', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findNonFiniInPasse($date)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.fin <= :val')
            ->andWhere('c.etat != :val2')
            ->setParameter('val', $date)
            ->setParameter('val2', 'FINI')
            ->orderBy('c.fin', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOuvertinFutur($bool, $limit)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.ouvert == :val')
            ->setParameter('val', $bool)
            ->orderBy('c.debut', 'ASC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult()
        ;
    }


    // /**
    //  * @return MediathequeDriveCreneau[] Returns an array of MediathequeDriveCreneau objects
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
    public function findOneBySomeField($value): ?MediathequeDriveCreneau
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
