#QuickStart

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
