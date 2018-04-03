# QuickStart

### 1) Define your pool (config/services.yaml)
`wampserver_broadcast: ['127.0.0.1:8095', '127.0.0.1:8097', '127.0.0.1:8099']`

### 2) Exclude this node
`$key = array_search($this->websocket_this_node, $this->wampserver_broadcast);`

`unset($this->wampserver_broadcast[$key]);`

### 3) Run your nodes
`bin/console server:run --port=8095`

`bin/console server:run --port=8097`

`bin/console server:run --port=8099`

### 4) Setup NGINX (as load balancer)

```sh
upstream socket {
    server 127.0.0.1:8095;
    server 127.0.0.1:8097;
    server 127.0.0.1:8099;
}

map $http_upgrade $connection_upgrade {
    default upgrade;
    ''      close;
}

server {
	server_name 127.0.0.1;
	listen 8090;

	proxy_next_upstream error;
	proxy_set_header X-Real-IP $remote_addr;
	proxy_set_header X-Scheme $scheme;
	proxy_set_header Host $http_host;

	location / {
		proxy_pass http://socket;
                proxy_http_version 1.1;
                proxy_set_header Upgrade $http_upgrade;
                proxy_set_header Connection "upgrade";
                proxy_set_header Host $host;

                proxy_set_header X-Real-IP $remote_addr;
                proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
                proxy_set_header X-Forwarded-Proto https;
                proxy_read_timeout 86400; # neccessary to avoid websocket timeout disconnect
                proxy_redirect off;
	}
}
```

### Installation

1) `composer req andreybolonin/ratchet-multi-instance`

2) Inject `use RatchetMultiInstanceTrait;` into your Topic class

3) Add `broadcast/channel` into you Topic class

```sh
public function onPublish(ConnectionInterface $connection, $topic, $event, array $exclude, array $eligible)
{
    switch ($topic->getId()) {
     case 'counter/channel':
         $this->CounterTopic($connection, $topic, $event, $exclude, $eligible);
         break;
    
     case 'price/channel':
         $this->PriceTopic($connection, $topic, $event, $exclude, $eligible);
         break;
    
     case 'broadcast/channel':
         $this->BroadcastTopic($connection, $topic, $event, $exclude, $eligible);
         break;
    }
}
```

4) Send the `$topic->broadcast($event)` with `$this->broadcast($event)` for broadcasting in another WampServer nodes

### Diagram

<img src="https://github.com/andreybolonin/RatchetMultiInstance/master/RatchetMultiInstance.png">