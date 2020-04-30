<?php

namespace App\Repository;

use App\Entity\CommandeDriveMediatheque;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CommandeDriveMediatheque|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommandeDriveMediatheque|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommandeDriveMediatheque[]    findAll()
 * @method CommandeDriveMediatheque[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommandeDriveMediathequeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommandeDriveMediatheque::class);
    }

    // /**
    //  * @return CommandeDriveMediatheque[] Returns an array of CommandeDriveMediatheque objects
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
    public function findOneBySomeField($value): ?CommandeDriveMediatheque
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
