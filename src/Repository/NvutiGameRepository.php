<?php

namespace App\Repository;

use App\Entity\DoubleGame;
use App\Entity\NvutiGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NvutiGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method NvutiGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method NvutiGame[]    findAll()
 * @method NvutiGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NvutiGameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NvutiGame::class);
    }


//    /**
//     * @return NvutiGame[] Returns an array of NvutiGame objects
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
    public function findOneBySomeField($value): ?NvutiGame
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
