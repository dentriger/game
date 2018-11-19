<?php

namespace App\Repository;

use App\Entity\DoubleGameBet;
use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method DoubleGameBet|null find($id, $lockMode = null, $lockVersion = null)
 * @method DoubleGameBet|null findOneBy(array $criteria, array $orderBy = null)
 * @method DoubleGameBet[]    findAll()
 * @method DoubleGameBet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DoubleGameBetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DoubleGameBet::class);
    }

    public function createBetFromRequest(Request $request, $userId, Game $game) {
        $bet = new DoubleGameBet();
        $bet->setUserId($userId);
        $bet->setBetTime(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));
        $bet->setStatus('pending');
        $bet->setGameType('double');
        $bet->setAmount($request->get('amount'));
        $bet->setCurrency('RUB');
        $bet->setAnticipatedEvent($request->get('anticipated_event'));
        $bet->setGameId($game->getId());

        $this->_em->persist($bet);
        $this->_em->flush();

        return $bet;
    }
}
