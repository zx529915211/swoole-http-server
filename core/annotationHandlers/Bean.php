<?php

namespace core\annotationHandlers;

use core\annotations\Bean;

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


];