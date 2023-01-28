<?php


namespace core;


use DI\ContainerBuilder;

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
     * @var IOC容器
     */
    private static $container;

    /**
     * 初始化函数
     */
    public static function init()
    {
        self::$env = parse_ini_file(ROOT_PATH . "/env");
        self::$handlers = require_once(ROOT_PATH . "/core/annotations/AnnotationHandlers.php");
        $builder = new ContainerBuilder();
        $builder->useAnnotations(true);
        self::$container = $builder->build();
        self::ScanBeans();
    }


    public static function getEnv(string $key, string $default = '')
    {
        return self::$env[$key] ?? $default;
    }

    public static function getBean($name)
    {
        try {
            return self::$container->get($name);
        } catch (\Throwable $e) {
            echo $e->getMessage();
        }
    }

    /**
     * 扫描所有的类文件 并且解析注解
     * @throws \ReflectionException
     */
    public static function ScanBeans()
    {
        //注解处理函数
        $scan_dir = self::getEnv('scan_dir', ROOT_PATH . "/app");
        $scan_root_namespace = self::getEnv('scan_root_namespace', "App\\");
        $phpfile = glob($scan_dir . '/*.php');
        foreach ($phpfile as $file) {
            require_once($file);
        }
        //循环处理已加载的类里的所有注解
        foreach (get_declared_classes() as $class) {
            if (strstr($class, $scan_root_namespace)) {
                $ref_class = new \ReflectionClass($class);
                $instance = self::$container->get($ref_class->getName());
                //处理属性注解
                self::handlerPropertyAnnotations($instance, $ref_class);
                //处理方法注解

                //处理类注解
                self::handlerClassAnnotations($instance, $ref_class);
            }
        }
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
            $handler = self::$handlers[$class_anno->getName()];
            //执行类注解处理函数
            $handler($instance, self::$container, $class_anno);
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
        $props= $ref_class->getProperties();
        foreach ($props as $prop) {
            $prop_annos = $prop->getAttributes();
            foreach ($prop_annos as $prop_anno){
                $handler = self::$handlers[$prop_anno->getName()];
                //执行类注解处理函数
                $handler($prop, $instance, $prop_anno);
            }
        }
    }
}