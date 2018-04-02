<?php

namespace andreybolonin;

use Gos\Component\WebSocketClient\Wamp\Client;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\Topic;

trait RatchetMultiInstanceTrait
{
    /**
     * @var array
     */
    private $wampserver_broadcast;

    /**
     * BroadcastTopic.
     *
     * @param ConnectionInterface $connection
     * @param Topic               $topic
     * @param $event
     * @param array $exclude
     * @param array $eligible
     *
     * @return mixed|void
     */
    public function BroadcastTopic(ConnectionInterface $connection, $topic, $event, array $exclude, array $eligible)
    {
        $topic->broadcast($event);
    }

    /**
     * @param array $event
     *
     * @throws \Gos\Component\WebSocketClient\Exception\BadResponseException
     * @throws \Gos\Component\WebSocketClient\Exception\WebsocketException
     */
    public function broadcast(array $event)
    {
        foreach ($this->wampserver_broadcast as $broadcast) {
            $host = parse_url($broadcast, PHP_URL_HOST);
            $port = parse_url($broadcast, PHP_URL_PORT);

            $client = new Client($host, $port);
            $client->connect();
            $client->publish('broadcast/channel', $event);
            $client->disconnect();
        }
    }
}
