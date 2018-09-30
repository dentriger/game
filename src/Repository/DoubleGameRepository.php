<?php

namespace App\Repository;

use App\Entity\DoubleGame;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DoubleGame|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoubleGame|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoubleGame[]    findAll()
 * @method DoubleGame[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoubleGameRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DoubleGame::class);
    }

    public function createDoubleGame($params)
    {
        $game = new DoubleGame();
        $game->setName('double');
        $game->setTime(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
        $game->setStatus('pending');
        $game->setGameHash($params->hash);
        $game->setGameSalt($params->salt);
        $game->setGameNumber($params->number);
        $this->_em->persist($game);
        $this->_em->flush();
        return $game;
    }
//    /**
//     * @return DoubleGame[] Returns an array of DoubleGame objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DoubleGame
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
