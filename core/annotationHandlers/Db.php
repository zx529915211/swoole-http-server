<?php

namespace core\annotationHandlers;

use core\annotations\Db;
use core\BeanFactory;

return [
    //属性注解  传入$prop属性反射对象、$instance实例化的类、$anno_ref注解反射对象
    Db::class => function (\ReflectionProperty $prop, $instance, $anno_ref) {
        $prop->setAccessible(true);
        $prop->setValue($instance, BeanFactory::getBean(\core\init\Db::class));
    },

];