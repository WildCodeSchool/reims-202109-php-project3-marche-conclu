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
        $query = $this->createQueryBuilder('s');
        if (isset($options['location']) && !empty($options['location'])) {
            $query->where('s.location = :location')
            ->setParameter('location', $options['location']);
        }
        if (isset($options['surface']) && !empty($options['surface'])) {
            $query->andWhere('s.surface >= :surface');
            $query->setParameter('surface', $options['surface']);
        }
        if (isset($options['price']) && !empty($options['price'])) {
            $query->andWhere('s.price <= :price');
            $query->setParameter('price', $options['price']);
        }
        return $query->getQuery()->getResult()
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
