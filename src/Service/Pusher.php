<?php
/**
 * Created by PhpStorm.
 * User: andrei
 * Date: 10/8/18
 * Time: 10:55 AM
 */

namespace App\Service;


class Pusher
{

    private $instance;

    private $socket;

    private function __construct()
    {
        $context = new \ZMQContext();
        $this->socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $this->socket->connect("tcp://localhost:5555");
    }

    public function getInstance()
    {
        if(is_null($this->instance))
        {
            $this->instance = new Pusher();
        }

        return $this->instance;
    }

    public function pushMessage($data)
    {
        try {
            $msg = json_encode($data);
            $this->socket->send($msg);
        } catch (\ZMQSocketException $e) {

        }
    }

}