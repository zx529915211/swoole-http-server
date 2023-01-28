<?php


namespace App\test;


use App\annotations\Bean;
use App\annotations\MyAttribute;
use App\annotations\MyRedis as RedisAnno;

#[Bean()]
class MyUser
{
   public $url = '127.0.0.1';
}