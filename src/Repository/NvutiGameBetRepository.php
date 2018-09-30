<?php

namespace App\Repository;

use App\Entity\NvutiGameBet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NvutiGameBet|null find($id, $lockMode = null, $lockVersion = null)
 * @method NvutiGameBet|null findOneBy(array $criteria, array $orderBy = null)
 * @method NvutiGameBet[]    findAll()
 * @method NvutiGameBet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NvutiGameBetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NvutiGameBet::class);
    }

//    /**
//     * @return NvutiGameBet[] Returns an array of NvutiGameBet objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NvutiGameBet
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
