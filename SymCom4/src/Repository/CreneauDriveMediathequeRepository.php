<?php

namespace App\Repository;

use App\Entity\CreneauDriveMediatheque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CreneauDriveMediatheque|null find($id, $lockMode = null, $lockVersion = null)
 * @method CreneauDriveMediatheque|null findOneBy(array $criteria, array $orderBy = null)
 * @method CreneauDriveMediatheque[]    findAll()
 * @method CreneauDriveMediatheque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CreneauDriveMediathequeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CreneauDriveMediatheque::class);
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


    // /**
    //  * @return CreneauDriveMediatheque[] Returns an array of CreneauDriveMediatheque objects
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
    public function findOneBySomeField($value): ?CreneauDriveMediatheque
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
