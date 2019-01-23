<?php

namespace App\Repository;

use App\Entity\Game;
use App\Entity\NvutiGame;
use App\Entity\NvutiGameBet;
use App\Entity\Wallet;
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

    public function setBet($userId, array $payload)
    {
        $game = $this->_em->getRepository(NvutiGame::class)->findOneBy(['user_id' => $userId, 'status' => 'pending']);

        $chance = intval($payload['chance']);
        $amount = floatval($payload['amount']);
        $stake = floatval($payload['stake']);
        $event = $this->closeGame($game, $amount, $chance, $stake);

        $nvutiBet = new NvutiGameBet();
        $nvutiBet->setUserId($userId);
        $nvutiBet->setGameId($game->getId());
        $nvutiBet->setGameType($game->getName());
        $nvutiBet->setAmount($amount);
        $nvutiBet->setCurrency('RUB');
        $nvutiBet->setStatus($event);
        $nvutiBet->setBetTime(\DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s')));

        if($stake == NvutiGame::STAKE_LESS) {
            $range_max = round($chance/100 * NvutiGame::MAX_NUMBER);
            $range_min = NvutiGame::MIN_NUMBER;
        } elseif ($stake == NvutiGame::STAKE_MORE) {
            $range_min = round(NvutiGame::MAX_NUMBER - $chance/100 * NvutiGame::MAX_NUMBER);
            $range_max = NvutiGame::MAX_NUMBER;
        }
        $nvutiBet->setNumbersRange("$range_min-$range_max");
        $this->_em->persist($nvutiBet);
        $this->_em->flush();

    }

    private function closeGame(NvutiGame $game, $amount, $chance, $stake)
    {
        $win_number = $game->getGameNumber();
        $wallet = $this->_em->getRepository(Wallet::class)->getUserWallet($game->getUserId());
        $current_balance = $wallet->getBalance();
        $current_balance -= $amount;
        $event = 'lost';

        if($this->isStakeWin($stake, $chance, $win_number)) {
           $current_balance += round($amount/$chance * 100, 2);
           $event = 'win';
        }
        $wallet->setBalance($current_balance);
        $game->setStatus('closed');
        $this->_em->persist($wallet);
        $this->_em->persist($game);
        $this->_em->flush();

        return $event;
    }

    public function isStakeWin($stake, $chance, $number)
    {
        if($stake == NvutiGame::STAKE_LESS) {
            $range_max = round($chance/100 * NvutiGame::MAX_NUMBER);
            $range_min = NvutiGame::MIN_NUMBER;
        } elseif ($stake == NvutiGame::STAKE_MORE) {
            $range_min = round(NvutiGame::MAX_NUMBER - $chance/100 * NvutiGame::MAX_NUMBER);
            $range_max = NvutiGame::MAX_NUMBER;
        }

        if(in_array($number, range($range_min, $range_max, 1))) {
            return true;
        }

        return false;
    }
}
