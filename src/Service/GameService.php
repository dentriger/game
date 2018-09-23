<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 23.09.2018
 * Time: 18:04
 */

namespace App\Service;


use App\Entity\Bet;
use App\Entity\Game;
use App\Repository\BetRepository;
use App\Repository\GameRepository;
use App\Repository\WalletRepository;

abstract class GameService
{
    protected $betRepository;

    protected $walletRepository;

    protected $gameRepository;

    public function __construct(GameRepository $gameRepository, WalletRepository $walletRepository, BetRepository $betRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->walletRepository = $walletRepository;
        $this->betRepository = $betRepository;
    }

    abstract function startGame();

    abstract function getMultiplier($event);

    public function createNewGame()
    {
        return $this->gameRepository->createGame();
    }

    public static function createMessage($msg, $data)
    {
        $message = [static::class, 'message' => $msg, 'data' => $data];

        return json_encode($message);
    }

    public function calculateRates(Game $game)
    {
        $game_bets = $this->betRepository->findBy(['game_id' => $game->getId()]);

        foreach ($game_bets as $bet) {
            if ($this->isBetWin($bet, $game)){
                $this->betRepository->updateBetStatus($bet, 'win');
                $this->walletRepository->updateBalance($bet->getUserId(), $bet->getStake(),$this->getMultiplier($game->getAnticipatedEvent()));
            } else {
                $this->betRepository->updateBetStatus($bet, 'lost');
            }
        }
    }

    public function isBetWin(Bet $bet, Game $game)
    {
        return $bet->getAnticipatedEvent() == $game->getAnticipatedEvent() ? true : false;
    }

    public function closeGame(Game $game, $number, $color)
    {
        $game->setStatus('closed');
        $game->setAnticipatedEvent($color);
        $game->setWinNumber($number);

        $this->gameRepository->updateGame($game);
    }
}