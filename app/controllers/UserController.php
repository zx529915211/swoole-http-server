<?php


namespace App\controllers;

use core\annotations\Bean;
use core\annotations\Value;
use core\annotations\RequestMapping;
use core\http\Request;
use core\http\Response;

#[Bean(name: "user")]
class UserController
{
    #[Value(name: "version")]
    public $version = '1.0';

    #[RequestMapping(value: "/test/{uid:\d+}")]
    public function test(int $uid,Request $request,Response $response)
    {
//        $response->write('6666');
//        var_dump($request->getQueryParams());
//        $response->redirect('http://www.baidu.com');
        return ['id' => 1,'name' => 'gzf'];
    }
}