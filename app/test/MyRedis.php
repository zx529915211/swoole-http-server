<?php


namespace App\test;


use App\annotations\Bean;
use App\annotations\MyAttribute;
use App\annotations\MyRedis as RedisAnno;

#[Bean()]
class MyRedis
{
    #[RedisAnno(xxx: 777)]
    public $name;

    #[MyAttribute(value: 666)]
    #[RedisAnno(xxx: 888)]
    function test()
    {

    }
}