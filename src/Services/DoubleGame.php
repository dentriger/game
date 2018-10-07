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
use Ratchet\Wamp\Topic;
use WebSocket\Client;
use WebSocket\BadOpcodeException;
use React\Socket\Server;

class DoubleGame extends GameService
{
    private $socket;

    private $round_time;

    private $deg;

    private $random;

    private $topic;

    protected $gameRules = [
        'green' => 14,
        'red' => 2,
        'black' => 2,
    ];

    protected $numbers_sectors = [
        0 => [0, 11, 348, 360],
        14 => [12, 35],
        7 => [36, 59],
        13 => [60, 83],
        6 => [84, 107],
        12 => [108, 131],
        5 => [132, 155],
        11 => [156, 179],
        4 => [180, 203],
        10 => [204, 227],
        3 => [228, 251],
        9 => [252, 275],
        2 => [276, 299],
        8 => [300, 323],
        1 => [324, 347],
    ];

    public function __construct(DoubleGameRepository $gameRepository,  WalletRepository $walletRepository)
    {
        $host = env('WEBSOCKET_SERVER_URL');
        $port = env('WEBSOCKET_SERVER_PORT');
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');

        $this->socket = $socket;
        $this->socket->connect("tcp://localhost:5555");
        $this->random = new Random(env('RANDOM_ORG_API_KEY'));
        $this->round_time = 30;

        parent::__construct($gameRepository, $walletRepository);

    }

    public function startGame()
    {
        $this->getRandomNumber();
        $game = $this->createNewGame();

        $current_time = $this->round_time;

        while($current_time >= 0) {
            $msg = GameService::createMessage('room:double', ['timer'=>$current_time]);
            $this->socket->send($msg);
            $current_time -= 1;
            sleep(1);
        }

        $msg = GameService::createMessage('room:double', ['roll'=>$this->deg]);
        $this->socket->send($msg);
        sleep(10);
        $msg = GameService::createMessage('room:double', ['number' => $game->getGameNumber()]);
        $this->socket->send($msg);
        sleep(3);
        $this->startGame();
    }

    public function createNewGame()
    {
        $params = new \stdClass();
        $params->number = $this->parseNumber($this->deg % 360);
        $params->salt = $this->getSalt();
        $params->hash = hash('sha224', $params->number.$params->salt);
        $game = $this->gameRepository->createDoubleGame($params);

        return $game;

    }

    public function getRandomNumber()
    {
        $number = 0;
        try {
            $result = $this->random->generateIntegers(1, 3600, 7200, false);
            $number = $result['result']['random']['data'][0];
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        $this->deg = $number;
        return $number;
    }

    public function parseNumber($deg) {
        foreach ($this->numbers_sectors as $key => $sector) {
            if (in_array($deg, range($sector[0], $sector[1], 1))) {
                return $key;
            }

            if ($key = 0) {
                if (in_array($deg, range($sector[0], $sector[1], 1)) || in_array($deg, range($sector[2], $sector[3], 1))) {
                    return $key;
                }
            }
        }
    }

    public function getSalt()
    {
        $result = $this->random->generateStrings(1, 6);
        return $result['result']['random']['data'][0];
    }

    public function getMultiplier($event)
    {
        // TODO: Implement getMultiplier() method.
    }
}