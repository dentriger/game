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
use App\Repository\DoubleGameRepository;
use App\Repository\WalletRepository;

abstract class GameService
{

    protected $walletRepository;

    protected $gameRepository;

    public function __construct(DoubleGameRepository $gameRepository, WalletRepository $walletRepository)
    {
        $this->gameRepository = $gameRepository;
        $this->walletRepository = $walletRepository;
    }

    abstract function startGame();

    abstract function createNewGame();

    public static function createMessage($msg, $data)
    {
        $message = [static::class, 'message' => $msg, 'data' => $data];

        return json_encode($message);
    }
}