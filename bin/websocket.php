<?php
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Wamp\WampServer;
use React\Socket\Server;
use App\Services\Websocket;

require dirname(__DIR__) . '/vendor/autoload.php';

$websocket = new Websocket();

$loop = React\EventLoop\Factory::create();

$context = new React\ZMQ\Context($loop);
$pull = $context->getSocket(ZMQ::SOCKET_PULL);
$pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
$pull->on('message', array($websocket, 'onBlogEntry'));

$webSock = new React\Socket\Server('0.0.0.0:8080', $loop);


$server = new IoServer(
    new HttpServer(
        new WsServer(
            new WampServer(
                $websocket
            )
        )
    ),
    $webSock,
    $loop
);

echo "Server start\n";
$server->run();