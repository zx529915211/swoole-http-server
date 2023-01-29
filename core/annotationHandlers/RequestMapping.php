<?php

namespace core\annotationHandlers;

use core\annotations\RequestMapping;
use core\BeanFactory;

return [
    /**
     * RequestMapping的方法注解处理函数  传入$method方法反射对象、$instance实例化的类、$anno_ref注解反射对象
     */
    //属性注解
    RequestMapping::class => function (\ReflectionMethod $method, $instance, $anno_ref) {
        $param = $anno_ref->getArguments();
        $path = $param['value'];
        $request_method = $param['method'] ?? "GET";
        /**@var $router \core\init\RouterCollector */
        $router = BeanFactory::getBean("RouterCollector");
        //收集路由
        $router->addRouter($request_method, $path, function () use ($method, $instance) {
            return $method->invoke($instance);
        });
    },

];