<?php

namespace App\Repository;

use App\Entity\Space;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use PhpParser\Node\Expr\Cast\Array_;

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
        if (isset($options['category'])) {
            $query->andWhere('s.category = :category')
            ->setParameter('category', $options['category']);
        }
        if (isset($options['location'])) {
            $query->andWhere('s.location = :location')
            ->setParameter('location', $options['location']);
        }
        if (isset($options['minsurface'])) {
            $query->andWhere('s.surface >= :surface')
            ->setParameter('surface', $options['minsurface']);
        }
        if (isset($options['maxprice'])) {
            $query->andWhere("s.price <= :price")
            ->setParameter('price', $options['maxprice']);
        }
        if (isset($options['capacity'])) {
            $query->andWhere("s.capacity > :capacity")
            ->setParameter('capacity', $options['capacity']);
        }
        if (isset($options['job'])) {
            $query->join('s.owner', 'o')->orderBy('CASE WHEN o.job=:job then 0 else 1 end')
            ->setParameter('job', $options['job']);
        }
        if (isset($options['date'])) {
            $query->andWhere("s.availability LIKE :date")
            ->setParameter('date', "%" . $options['date'] . "%");
        }
        return $query->getQuery()->getResult();
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
