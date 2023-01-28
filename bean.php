<?php
require_once __DIR__."/vendor/autoload.php";

use App\core\classFactory;

classFactory::scanBeans(__DIR__."/app/test","App\\test");

$class = classFactory::getBean(\App\test\MyUser::class);
var_dump($class);