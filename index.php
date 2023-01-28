<?php
require_once __DIR__."/vendor/autoload.php";
use Swoole\Http\Request;
use Swoole\Http\Response;

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/test', function(){
        return "my test";
    });
    // {id} must be a number (\d+)
   });

$swoole = new Swoole\Http\Server('0.0.0.0','8000');
$swoole->on('request',function(Request $request,Response $response) use($dispatcher){
    $my_request = \core\http\Request::init($request);
    $routeInfo = $dispatcher->dispatch($my_request->getMethod(), $my_request->getUri());
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            $response->status(404);
            $response->end();
            // ... 404 Not Found
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $response->status(405);
            $response->end();
            // ... 405 Method Not Allowed
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $response->end($handler());
            break;
    }
});
$swoole->start();