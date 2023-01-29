<?php


namespace core\init;

use core\annotations\Bean;

/**
 * 路由收集器
 * Class RouterCollector
 * @package core\init
 */
#[Bean()]
class RouterCollector
{
    public $routes = [];

    public function addRouter($method, $uri, $handler)
    {
        $this->routes[] = ['method' => $method, 'uri' => $uri, 'handler' => $handler];
    }

    public function getDispatcher()
    {
        return \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            foreach ($this->routes as $route) {
                $r->addRoute($route['method'], $route['uri'], $route['handler']);
            }
        });

    }
}