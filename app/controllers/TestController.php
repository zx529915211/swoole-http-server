<?php


namespace App\controllers;

use App\models\User;
use core\annotations\Bean;
use core\annotations\Db;
use core\annotations\Value;
use core\annotations\RequestMapping;
use core\http\Request;
use core\http\Response;

#[Bean(name: "test")]
class TestController
{
    /**
     * @var \core\init\Db
     */
    #[Db(source: 'default')]
    public $db;


    #[RequestMapping(value: "/test")]
    public function test(int $uid,Request $request,Response $response)
    {
        $this->db->test='bbbb';
        return [$this->db->test];
    }

}