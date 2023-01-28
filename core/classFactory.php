<?php

namespace App\core;

use App\annotations\Bean;

class classFactory
{
    private static $beans = [];

    /**
     * 扫描目录获取所有的类实例存放到beans静态变量中
     * @param string $path
     * @param string $namespace
     * @throws \ReflectionException
     */
    public static function scanBeans(string $path,string $namespace)
    {
        $phpfile = glob($path.'/*.php');
        foreach ($phpfile as $file){
            require ($file);
        }
        $classes = get_declared_classes();
        foreach ($classes as $class){
            if(strstr($class,$namespace)){
                $ref_class = new \ReflectionClass($class);
                $class_annos = $ref_class->getAttributes();
                foreach ($class_annos as $anno){
                    if($anno->newInstance() instanceof Bean){
                        self::$beans[$ref_class->getName()] = self::loadClass($ref_class->getName(),$ref_class->newInstance());
                    }
                }
            }
        }
    }

    public static function getBean($beanName)
    {
        if(isset(self::$beans[$beanName])){
            return self::$beans[$beanName];
        }
        return null;
    }

    /**
     * 获取类实例，并通过注解自动装载属性
     * @param $class_name
     * @param false $obj
     * @return false|mixed|object
     * @throws \ReflectionException
     */
    public static function loadClass($class_name,$obj = false)
    {
        $ref_class = new \ReflectionClass($class_name);
        //属性注解处理
        $properties = $ref_class->getProperties();
        foreach ($properties as $property) {
            $property_annos = $property->getAttributes();
            foreach ($property_annos as $anno) {
//                var_dump($anno->getArguments());
                $getValue = $anno->newInstance()->do();
                $retObj = $obj ?: $ref_class->newInstance();
                $property->setValue($retObj, $getValue);
                return $retObj;
            }
        }
        return $obj ?: $ref_class->newInstance();
    }
}