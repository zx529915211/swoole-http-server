<?php


namespace core;


use core\lib\Config;
use core\lib\Env;

class BeanFactory
{
    /**
     * @var array env配置文件
     */
    private static $env = [];

    /**
     * 注解处理函数
     * @var array
     */
    private static $handlers = [];

    /**
     * @var Container
     */
    private static $container;

    /**
     * 初始化函数
     */
    public static function init()
    {
        //初始化env配置
        Env::init();
        //初始化config配置
        Config::init();
        $handlers = glob(ROOT_PATH . "/core/annotationHandlers/*.php");
        foreach ($handlers as $handler) {
            self::$handlers = array_merge(self::$handlers, require_once($handler));
        }

        $scans = [
            ROOT_PATH . "/core/init" => "core\\",
            Env::get('scan_dir', ROOT_PATH . "/app") => Env::get('scan_root_namespace', "App\\")
        ];
        //预先扫描目录，解析其中的注解
        foreach ($scans as $scan_dir => $namespace) {
            self::ScanBeans($scan_dir, $namespace);
        }
    }

    public static function setContainer($container)
    {
        self::$container = $container;
    }



    public static function getBean($name)
    {
        try {
            return self::$container->get($name);
        } catch (\Throwable $e) {
            return null;
        }
    }

    public static function setBean($name,$class)
    {
            return self::$container->set($name,$class);
    }


    private static function getAllBeanFiles($dir)
    {
        $files = glob($dir . '/*');
        $ret = [];
        foreach ($files as $file) {
            if (is_dir($file)) {
                $ret = array_merge($ret, self::getAllBeanFiles($file));
            } elseif (pathinfo($file)['extension'] == 'php') {
                $ret[] = $file;
            }
        }
        return $ret;
    }

    /**
     * 扫描所有的类文件 并且解析注解
     * @throws \ReflectionException
     */
    public static function ScanBeans($scan_dir, $scan_root_namespace)
    {
        $allFiles = self::getAllBeanFiles($scan_dir);
        foreach ($allFiles as $file) {
            require_once $file;
        }
        //循环处理已加载的类里的所有注解
        foreach (get_declared_classes() as $class) {
            if (strstr($class, $scan_root_namespace)) {
                $ref_class = new \ReflectionClass($class);
                $instance = self::$container->get($ref_class->getName());
                //处理属性注解
                self::handlerPropertyAnnotations($instance, $ref_class);
                //处理方法注解
                self::handlerMethodAnnotations($instance, $ref_class);
                //处理类注解
                self::handlerClassAnnotations($instance, $ref_class);
            }
        }
    }

    /**
     * 获取注解处理函数
     * @param $annotation_ref
     * @return mixed|null
     */
    public static function getHandlerFunc($annotation_ref)
    {
        return self::$handlers[$annotation_ref->getName()] ?? null;
    }

    /**
     * 处理类注解
     * @param $instance
     * @param \ReflectionClass $ref_class
     */
    private static function handlerClassAnnotations($instance, \ReflectionClass $ref_class)
    {
        //获取所有类注解
        $class_annos = $ref_class->getAttributes();
        foreach ($class_annos as $class_anno) {
            $handler = self::getHandlerFunc($class_anno);
            if ($handler) {
                //执行类注解处理函数
                $handler($instance, self::$container, $class_anno);
            }
        }
    }

    /**
     * 处理属性注解
     * @param $instance
     * @param \ReflectionClass $ref_class
     */
    private static function handlerPropertyAnnotations($instance, \ReflectionClass $ref_class)
    {
        //获取对象的所有属性
        $props = $ref_class->getProperties();
        foreach ($props as $prop) {
            $prop_annos = $prop->getAttributes();
            foreach ($prop_annos as $prop_anno) {
                $handler = self::getHandlerFunc($prop_anno);
                if ($handler) {
                    //执行类注解处理函数
                    $handler($prop, $instance, $prop_anno);
                }
            }
        }
    }

    /**
     * 处理方法注解
     * @param $instance
     * @param \ReflectionClass $ref_class
     */
    private static function handlerMethodAnnotations($instance, \ReflectionClass $ref_class)
    {
        //获取对象的所有属性
        $methods = $ref_class->getMethods();
        foreach ($methods as $method) {
            $method_annos = $method->getAttributes();
            foreach ($method_annos as $method_anno) {
                $handler = self::getHandlerFunc($method_anno);
                if ($handler) {
                    //执行类注解处理函数
                    $handler($method, $instance, $method_anno);
                }
            }
        }
    }
}