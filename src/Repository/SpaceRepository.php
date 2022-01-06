<?php

namespace App\Repository;

use App\Entity\Space;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Space|null find($id, $lockMode = null, $lockVersion = null)
 * @method Space|null findOneBy(array $criteria, array $orderBy = null)
 * @method Space[]    findAll()
 * @method Space[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SpaceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Space::class);
    }

    // /**
    //  * @return Space[] Returns an array of Space objects
    //  */

    public function findByCriterias(array $options): mixed
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.location = :location')
            ->andWhere('s.surface > :surface')
            ->andWhere('s.price < :price')
            ->setParameter('location', $options['location'] ?? null)
            ->setParameter('price', $options['price'] ?? 0)
            ->setParameter('surface', $options['surface'] ?? 0)
            ->orderBy('s.id', 'ASC')
            ->getQuery()
            ->getResult()

        ;
    }

    /*
    public function findOneBySomeField($value): ?Space
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
