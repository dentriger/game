<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 30.09.2018
 * Time: 21:26
 */

namespace App\Service;

use App\Entity\Game;
use App\Repository\BetRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\GameService;
use RandomOrg\Random;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use WebSocket\Client;
use WebSocket\BadOpcodeException;

class DoubleGameService
{
    private $socket;

    private $round_time;

    private $prize_segments = [
        1 => 0,
        2 => 1,
        3 => 8,
        4 => 2,
        5 => 9,
        6 => 3,
        7 => 10,
        8 => 4,
        9 => 11,
        10 => 5,
        11 => 12,
        12 => 6,
        13 => 13,
        14 => 7,
        15 => 14
    ];

    protected $gameRules = [
        'green' => 14,
        'red' => 2,
        'black' => 2,
    ];

    public function __construct(GameRepository $gameRepository, BetRepository $betRepository, WalletRepository $walletRepository)
    {
        $host = env('WEBSOCKET_SERVER_URL');
        $port = env('WEBSOCKET_SERVER_PORT');
        $this->socket = new Client("ws://$host:$port");
        $this->round_time = 30;

        parent::__construct($gameRepository, $walletRepository, $betRepository);

    }

    public function startGame()
    {
        $game = new \App\Entity\DoubleGame();

    }

    public function getRandomNumber()
    {
        $number = 0;
        try {

            $random = new Random(env('RANDOM_ORG_API_KEY'));
            $result = $random->generateIntegers(1, 3600, 7200, false);
            $number = $result['result']['random']['data'][0];
        } catch (\Exception $e) {

        }

        return $number;
    }

    public function getMultiplier($event)
    {
        // TODO: Implement getMultiplier() method.
    }

}