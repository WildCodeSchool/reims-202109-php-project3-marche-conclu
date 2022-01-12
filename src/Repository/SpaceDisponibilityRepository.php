<?php

namespace App\Repository;

use App\Entity\SpaceDisponibility;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method SpaceDisponibility|null find($id, $lockMode = null, $lockVersion = null)
 * @method SpaceDisponibility|null findOneBy(array $criteria, array $orderBy = null)
 * @method SpaceDisponibility[]    findAll()
 * @method SpaceDisponibility[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceDisponibilityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SpaceDisponibility::class);
    }

    // /**
    //  * @return SpaceDisponibility[] Returns an array of SpaceDisponibility objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?SpaceDisponibility
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
