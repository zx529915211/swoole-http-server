<?php
require_once __DIR__."/vendor/autoload.php";

$builder = new \DI\ContainerBuilder();

$builder->useAnnotations(true);

$container = $builder->build();

$myClass = $container->get(\App\test\MyClass::class);
var_dump($myClass);