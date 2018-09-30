<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10.09.2018
 * Time: 23:32
 */

namespace App\Services;

use App\Entity\Game;
use App\Repository\DoubleGameRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\GameService;
use RandomOrg\Random;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use WebSocket\Client;
use WebSocket\BadOpcodeException;

class DoubleGame extends GameService
{
    private $socket;

    private $round_time;

    private $deg;

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

    public function __construct(DoubleGameRepository $gameRepository,  WalletRepository $walletRepository)
    {
        $host = env('WEBSOCKET_SERVER_URL');
        $port = env('WEBSOCKET_SERVER_PORT');
        $this->socket = new Client("ws://$host:$port");
        $this->round_time = 30;

        parent::__construct($gameRepository, $walletRepository);

    }

    public function startGame()
    {
        $game = $this->createNewGame();

        $current_time = $this->round_time;

        while($current_time >= 0) {
            $msg = GameService::createMessage('double', ['timer'=>$current_time]);
            $this->socket->send($msg);
            $current_time -= 1;
            sleep(1);
        }

        $msg = GameService::createMessage('double', ['roll'=>$this->deg]);
        $this->socket->send($msg);
        sleep(15);
        $this->startGame();
    }

    public function createNewGame()
    {
        $params = new \stdClass();
        $params->number = ($this->deg % 360 + 12) / 24;
        $params->salt = $this->getSalt();
        $params->hash = hash('sha224', $params->number.$params->salt);
        $game = $this->gameRepository->createDoubleGame($params);

        return $game;

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
        $this->deg = $number;
        return $number;
    }

    public function getSalt()
    {
        return 'salt';
    }

    public function getMultiplier($event)
    {
        // TODO: Implement getMultiplier() method.
    }
}