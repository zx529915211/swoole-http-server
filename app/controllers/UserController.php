<?php


namespace App\controllers;

use core\annotations\Bean;
use core\annotations\Value;

#[Bean(name: "user")]
class UserController
{
    #[Value(name: "version")]
    public $version = '1.0';
}