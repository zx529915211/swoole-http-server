<?php


namespace core\server;


use FastRoute\Dispatcher;
use Swoole\Http\Request;
use Swoole\Http\Response;
use Swoole\Http\Server;

class HttpServer
{
    /**
     * @var Server
     */
    private Server $server;
    private $dispatcher;

    public function __construct()
    {
        $this->server = new Server('0.0.0.0', '8000');
        $this->server->set([
            'worker_num' => 1,
            'daemonize' => false
        ]);
        $this->server->on('start', [$this, 'onStart']);
        $this->server->on('request', [$this, 'onRequest']);
        $this->server->on('shutDown', [$this, 'onShutDown']);
        $this->server->on('WorkerStart', [$this, 'onWorkerStart']);
        $this->server->on('ManagerStart', [$this, 'onManagerStart']);
    }

    public function onStart(Server $server)
    {
        cli_set_process_title('guyue-master');
        $pid = $server->master_pid;
        file_put_contents('./guyue.pid', $pid);
    }

    public function onShutDown(Server $server)
    {
        unlink('./guyue.pid');
    }

    public function onRequest(Request $request, Response $response)
    {
        $my_request = \core\http\Request::init($request);
        $my_response = \core\http\Response::init($response);
        $routeInfo = $this->dispatcher->dispatch($my_request->getMethod(), $my_request->getUri());
        switch ($routeInfo[0]) {
            case Dispatcher::NOT_FOUND:
                $response->status(404);
                $response->end();
                // ... 404 Not Found
                break;
            case Dispatcher::METHOD_NOT_ALLOWED:
                $response->status(405);
                $response->end();
                // ... 405 Method Not Allowed
                break;
            case Dispatcher::FOUND:
                $handler = $routeInfo[1];
                $vars = $routeInfo[2];
                $vars[get_class($my_request)] = $my_request;
                $vars[get_class($my_response)] = $my_response;
                $my_response->setBody($handler($vars));
                $my_response->end();
                break;
        }
    }

    public function onWorkerStart(Server $server, $work_id)
    {
        \core\BeanFactory::init();
        $this->dispatcher = \core\BeanFactory::getBean('RouterCollector')->getDispatcher();
        cli_set_process_title('guyue-worker');

    }

    public function onManagerStart(Server $server)
    {
        cli_set_process_title('guyue-manager');
    }

    public function run()
    {
        $this->server->start();
    }


}