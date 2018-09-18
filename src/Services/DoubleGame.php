<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10.09.2018
 * Time: 23:32
 */

namespace App\Services;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use WebSocket\Client;

class DoubleGame
{
    private $socket;

    private $round_time;

    private $current_time;
//{'text' : '0'},
//{'text' : '1'},
//{'text' : '8'},
//{'text' : '2'},
//{'text' : '9'},
//{'text' : '3'},
//{'text' : '10'},
//{'text' : '4'},
//{'text' : '11'},
//{'text' : '5'},
//{'text' : '12'},
//{'text' : '6'},
//{'text' : '13'},
//{'text' : '7'},
//{'text' : '14'},
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

    public function __construct()
    {
        $this->socket = new Client('ws://localhost:8080');

        $this->round_time = 30;

    }

    public function start() {
        $this->current_time = $this->round_time;

        while ($this->current_time >= 0) {
            try {
                $msg = $this->createMessage('double', ['timer' => $this->current_time]);
                $this->socket->send($msg);
            } catch (\Websocket\BadOpcodeException $e) {
                echo $e->getMessage() . "\n";
            }
            $this->current_time -= 1;
            sleep(1);
        }

        $segment = $this->getPrizeSegment();
        $msg = $this->createMessage('double', ['segment' => $segment]);
        $this->socket->send($msg);

        sleep(10);

        $this->start();
    }

    public function createGame()
    {

    }

    public function getRandomNumber()
    {
        return rand(0, 14);
    }

    private function getPrizeSegment()
    {
        return array_search($this->getRandomNumber(), $this->prize_segments);
    }

    public function createMessage($msg, $data)
    {
        $message = [self::class, 'message' => $msg, 'data' => $data];

        return json_encode($message);
    }
}