<?php

namespace core\annotations;

use core\BeanFactory;

return [
    /**
     * Bean的类注解处理函数   传入$instance实例化的类、$container容器对象、$anno_ref注解反射对象
     */
    Bean::class => function ($instance, $container, $anno_ref) {
        $param = $anno_ref->getArguments();
        if (isset($param['name']) && $param['name'] != '') {
            $beanName = $param['name'];
        }else{
            $arrs = explode("\\", get_class($instance));
            $beanName = end($arrs);
        }
        $container->set($beanName, $instance);
    },

    //属性注解
    Value::class => function (\ReflectionProperty $prop,$instance,$anno_ref) {
        $param = $anno_ref->getArguments();
        $value = BeanFactory::getEnv($param['name'],'');
        if (empty($value) || $param['name'] == '') {
            return $instance;
        }
        $prop->setValue($instance,BeanFactory::getEnv($param['name']));
    },
];