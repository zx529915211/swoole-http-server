<?php
require_once __DIR__ . "/vendor/autoload.php";

require_once __DIR__ . "/app/config/define.php";

\core\BeanFactory::init();

$user = \core\BeanFactory::getBean("RouterCollector");
var_dump($user->routes);