<?php
//require_once __DIR__ . "/vendor/autoload.php";
//
//require_once __DIR__ . "/app/config/define.php";
//
//\core\BeanFactory::init();
//
//$user = \core\BeanFactory::getBean("RouterCollector");
//var_dump($user->routes);


//一次启动多个服务
$server = new  Swoole\Http\Server('0.0.0.0', '8000');

$server->on('request',function($request,$response){
    $response->end('port 8000');
});

$port2 = $server->listen('0.0.0.0','8001',SWOOLE_SOCK_TCP);

$port2->on('request',function($request,$response){
    $response->end('port 8001');
});

$server->start();
