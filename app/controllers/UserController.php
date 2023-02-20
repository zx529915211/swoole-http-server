<?php


namespace App\controllers;

use App\models\User;
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
    #[Db(source: 'db1')]
    public $db;

    /**
     * @var \core\init\Db
     */
    #[Db(source: 'default')]
    public $db2;

    #[Value(name: "version")]
    public $version = '1.0';

    #[RequestMapping(value: "/test/{uid:\d+}")]
    public function test(int $uid,Request $request,Response $response)
    {
//        $response->write('6666');
//        var_dump($request->getQueryParams());
//        $response->redirect('http://www.baidu.com');
//        return $this->db->table('user')->where('id','=','1')->get();
//          return User::all();
        return [$this->db2->test];
    }

    #[RequestMapping(value: "/test2/{uid:\d+}")]
    public function test2(int $uid,Request $request,Response $response)
    {
//        $response->write('6666');
//        var_dump($request->getQueryParams());
//        $response->redirect('http://www.baidu.com');
        return $this->db2->table('user')->where('id','=','1')->get();

//        return ['id' => 1,'name' => '111'];
    }
}