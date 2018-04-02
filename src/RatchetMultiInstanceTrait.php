<?php

namespace andreybolonin;

use Gos\Component\WebSocketClient\Wamp\Client;

trait RatchetMultiInstanceTrait
{
    /**
     * @var array
     */
    private $wampserver_broadcast;

    /**
     * @param array $event
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
