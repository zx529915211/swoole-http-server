<?php

use core\BeanFactory;
use core\server\HttpServer;
use DI\ContainerBuilder;

require_once __DIR__ . "/vendor/autoload.php";
require_once __DIR__ . "/app/config/define.php";

$builder = new ContainerBuilder();
$builder->useAnnotations(true);
BeanFactory::setContainer($builder->build());
if ($argc == 2) {
    $cmd = $argv[1];
    switch ($cmd){
        case 'start':
            /**
             * @var $http HttpServer
             */
            $http = BeanFactory::getBean(HttpServer::class);
            $http->run();
            break;
        case 'stop':
            $master_pid = intval(file_get_contents('./guyue.pid'));
            if($master_pid && trim($master_pid)!=0){
                \Swoole\Process::kill($master_pid);
            }
            break;
        default:
            echo 'cmd is error';
    }
}else{
    echo 'cmd num error';
}