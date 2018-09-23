<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 10.09.2018
 * Time: 23:32
 */

namespace App\Services;

use App\Entity\Game;
use App\Repository\BetRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use App\Repository\WalletRepository;
use App\Service\GameService;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use WebSocket\Client;
use WebSocket\BadOpcodeException;

class DoubleGame extends GameService
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
        $this->socket = new Client('ws://localhost:8080');
        $this->round_time = 30;

        parent::__construct($gameRepository, $walletRepository, $betRepository);

    }

    public function startGame()
    {
        try {
            echo "New Game\n";
            $game = $this->createNewGame();
            $current_time = $this->round_time;

            while ($current_time >= 0) {
                $msg = DoubleGame::createMessage('double', ['timer' => $current_time]);
                $this->socket->send($msg);

                $current_time -= 1;
                sleep(1);
            }

            $segment = $this->getPrizeSegment();
            $this->closeGame($game, $this->prize_segments[$segment], $this->getColor($segment));
            $msg = DoubleGame::createMessage('double', ['segment' => $segment]);

            $this->socket->send($msg);

            sleep(10);

            $this->calculateRates($game);

            $this->startGame();
        } catch (BadOpcodeException $e) {
            echo $e->getMessage();
        } catch (\Exception $e) {
            echo $e->getMessage();
        } finally {
            //$this->startGame();
        }
    }

    public function getRandomNumber()
    {
        return rand(0, 14);
    }

    private function getPrizeSegment()
    {
        return array_search($this->getRandomNumber(), $this->prize_segments);
    }

    public function getMultiplier($event)
    {
        return $this->gameRules[$event];
    }

    public function getColor($segment)
    {
        if($this->prize_segments[$segment] == 0) {
            return 'green';
        }

        if($this->prize_segments[$segment] <= 7) {
            return 'red';
        }

        if ($this->prize_segments[$segment] >= 8) {
            return 'black';
        }
    }
}