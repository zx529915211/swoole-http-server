<?php


namespace App\controllers;

use core\annotations\Bean;
use core\annotations\Value;
use core\annotations\RequestMapping;

#[Bean(name: "user")]
class UserController
{
    #[Value(name: "version")]
    public $version = '1.0';

    #[RequestMapping(value: "/test")]
    public function test()
    {
        return 'test';
    }
}