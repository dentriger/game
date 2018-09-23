<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use App\Repository\BetRepository;
use App\Repository\GameRepository;
use App\Repository\WalletRepository;

require dirname(__DIR__) . '/vendor/autoload.php';

$double = new \App\Services\DoubleGame();

$double->start();