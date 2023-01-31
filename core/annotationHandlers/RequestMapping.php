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
        $router->addRouter($request_method, $path, function ($params) use ($method, $instance) {
            $inputParam = [];
            //得到方法的反射参数
            $ref_params = $method->getParameters();
            $param_keys = array_keys($params);
            foreach ($ref_params as $ref_param) {
                $param_type = $ref_param->getType();
                //isBuiltin方法判断类型是否为PHP内置类型，不是的话则是类
                if (in_array($param_type->getName(), $param_keys) && !$param_type->isBuiltin()) {
                    //request response类会符合这个条件
                    $params[$ref_param->getName()] = $params[$ref_param->getType()->getName()];
                }
                if (isset($params[$ref_param->getName()])) {
                    $inputParam[] = $params[$ref_param->getName()];
                } else {
                    $inputParam[] = $ref_param->isOptional() ? $ref_param->getDefaultValue() : false;
                }
            }
            return $method->invokeArgs($instance, $inputParam);
        });
    },

];