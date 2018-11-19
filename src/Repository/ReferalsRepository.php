<?php

namespace App\Repository;

use App\Entity\Referals;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Referals|null find($id, $lockMode = null, $lockVersion = null)
 * @method Referals|null findOneBy(array $criteria, array $orderBy = null)
 * @method Referals[]    findAll()
 * @method Referals[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReferalsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Referals::class);
    }

//    /**
//     * @return Referals[] Returns an array of Referals objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Referals
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
