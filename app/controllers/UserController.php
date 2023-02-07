<?php


namespace App\controllers;

use core\annotations\Bean;
use core\annotations\Db;
use core\annotations\Value;
use core\annotations\RequestMapping;
use core\http\Request;
use core\http\Response;

#[Bean(name: "user")]
class UserController
{
    /**
     * @var \core\init\Db
     */
    #[Db]
    public $db;

    #[Value(name: "version")]
    public $version = '1.0';

    #[RequestMapping(value: "/test/{uid:\d+}")]
    public function test(int $uid,Request $request,Response $response)
    {
//        $response->write('6666');
//        var_dump($request->getQueryParams());
//        $response->redirect('http://www.baidu.com');
        return $this->db->table('user')->where('id','=','1')->get();

//        return ['id' => 1,'name' => '111'];
    }
}