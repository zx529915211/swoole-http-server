<?php
require_once __DIR__."/vendor/autoload.php";

use App\annotations\MyAttribute;
use App\annotations\MyRedis;
use App\core\classFactory;


class Thing
{
    #[MyRedis(xxx: 777)]
    public $name;

    #[MyAttribute(value: 666)]
    #[MyRedis(url: 888)]
    function test()
    {

    }
}

function dumpAttributeData($reflection) {
    $attributes = $reflection->getAttributes();
    foreach ($attributes as $attribute) {
        var_dump($attribute->getName());
        var_dump($attribute->getArguments());
        var_dump($attribute->newInstance());
    }
}

/**
 * @throws ReflectionException
 */
function dumpMethodAttributeData(ReflectionClass $reflection, $method, $attr_type) {
    $methodReflect = $reflection->getMethod($method);
    $attributes = $methodReflect->getAttributes();
    foreach ($attributes as $attribute) {
        if ($attribute->getName() == $attr_type) {
            var_dump($attribute->getName());
            var_dump($attribute->getArguments());
            var_dump($attribute->newInstance());
        }
    }
}

function dumpPropertyAttributeData($reflection,$property) {
    $propertyReflect = $reflection->getProperty($property);
    $attributes = $propertyReflect->getAttributes();
    foreach ($attributes as $attribute) {
        var_dump($attribute->getName());
        var_dump($attribute->getArguments());
        var_dump($attribute->newInstance());
    }
}


//try {
//    dumpMethodAttributeData(new ReflectionClass(Thing::class), 'test', \App\annotations\MyRedis::class);
//} catch (ReflectionException $e) {
//}
$class = classFactory::loadClass(Thing::class);
var_dump($class);