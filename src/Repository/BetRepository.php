<?php

namespace App\Repository;

use App\Entity\Bet;
use App\Entity\User;
use App\Entity\Wallet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * @method Bet|null find($id, $lockMode = null, $lockVersion = null)
 * @method Bet|null findOneBy(array $criteria, array $orderBy = null)
 * @method Bet[]    findAll()
 * @method Bet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BetRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Bet::class);
    }

    public function createBetFromRequest(Request $request)
    {
        $bet = new Bet();

        $user_id = $request->query->get('user_id');
        $bet_amount = $request->query->get('bet_amount');
        $bet->setUserId($user_id);
        $bet->setGameType($request->query->get('game_type'));
        $bet->setStake($bet_amount);
        $bet->setStatus('pending');
        $bet->setGameId($request->query->get('game_id'));
        $bet->setAnticipatedEvent($request->query->get('anticipated_event'));

        $this->_em->persist($bet);
        $this->_em->getRepository(Wallet::class)->updateBalance($user_id, -$bet_amount);
        $this->_em->flush();

        return $bet;
    }

    public function updateBetStatus(Bet $bet, $status)
    {
        $bet->setStatus($status);
        $this->_em->persist($bet);
        $this->_em->flush();
    }
}
