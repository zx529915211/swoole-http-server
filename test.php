<?php
require_once __DIR__ . "/vendor/autoload.php";

require_once __DIR__ . "/app/config/define.php";

\core\BeanFactory::init();

$user = \core\BeanFactory::getBean("user");
var_dump($user);