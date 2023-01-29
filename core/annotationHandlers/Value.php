<?php

namespace core\annotationHandlers;

use core\annotations\Value;
use core\BeanFactory;

return [
    //属性注解  传入$prop属性反射对象、$instance实例化的类、$anno_ref注解反射对象
    Value::class => function (\ReflectionProperty $prop,$instance,$anno_ref) {
        $param = $anno_ref->getArguments();
        $value = BeanFactory::getEnv($param['name'],'');
        if (empty($value) || $param['name'] == '') {
            return $instance;
        }
        $prop->setValue($instance,BeanFactory::getEnv($param['name']));
    },

];