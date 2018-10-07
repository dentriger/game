<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 16.09.2018
 * Time: 17:11
 */

namespace App\Services;

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;
use Ratchet\Wamp\WampServerInterface;

class Websocket implements WampServerInterface
{
    protected $clients;
    protected $subscribedTopics = array();

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }


    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
        echo "Subscriptions! {$this->subscribedTopics[0]}\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        echo sprintf('Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $msg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
                $client->send($msg);
            }
        }
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

    /**
     * @param ConnectionInterface $conn
     * @param Topic|string $topic
     */
    public function onSubscribe(ConnectionInterface $conn, $topic) {
        echo 'New subsribtion!!!'.json_encode($topic);
        if (!array_key_exists($topic->getId(), $this->subscribedTopics)) {
            $this->subscribedTopics[$topic->getId()] = $topic;
        }
    }
    public function onUnSubscribe(ConnectionInterface $conn, $topic) {
        $conn->channels->forget($topic->getId());
    }

    public function onCall(ConnectionInterface $conn, $id, $topic, array $params)
    {
        $conn->callError($id, $topic, 'You are not allowed to make calls')->close();
    }

    public function onPublish(ConnectionInterface $conn, $topic, $event, array $exclude, array $eligible)
    {
        $conn->close();
    }

    /**
     * @param string JSON'ified string we'll receive from ZeroMQ
     */
    public function onBlogEntry($entry) {
        $entryData = json_decode($entry, true);
        echo "New entry data!!! ".$entry."\n";
        $test = json_encode($this->subscribedTopics);
        echo "Subscribed topics! {$test}\n";
        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData[0], $this->subscribedTopics)) {
            return;
        }
        echo "Send\n";
        $topic = $this->subscribedTopics[$entryData[0]];

        // re-send the data to all the clients subscribed to that category
        $topic->broadcast($entryData);
    }

    /* The rest of our methods were as they were, omitted from docs to save space */
}