<?php


namespace core;


class Container
{
    private static $container = null;//容器实例
    protected static $instance = [];//对象存储的数组


    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getContainer()
    {
        if (static::$container == null) {
            static::$container = new self();
            self::set(self::class, static::$container);
        }
        return static::$container;
    }

    public static function set($class_name, $instance)
    {
        if (!isset(static::$instance[$class_name])) {
            static::$instance[$class_name] = $instance;
        }
    }


    public static function build($className)
    {
        if (is_string($className) and static::has($className)) {
            return static::get($className);
        }
        //反射
        $reflector = new \ReflectionClass($className);
        // 检查类是否可实例化, 排除抽象类abstract和对象接口interface
        if (!$reflector->isInstantiable()) {
            throw new \Exception("Can't instantiate " . $className);
        }
        /** @var \ReflectionMethod $constructor 获取类的构造函数 */
        $constructor = $reflector->getConstructor();
        // 若无构造函数，直接实例化并返回
        if (is_null($constructor)) {
            return new $className;
        }
        // 取构造函数参数,通过 ReflectionParameter 数组返回参数列表
        $parameters = $constructor->getParameters();
        // 递归解析构造函数的参数
        $dependencies = static::getDependencies($parameters);
        // 创建一个类的新实例，给出的参数将传递到类的构造函数。
        $class = $reflector->newInstanceArgs($dependencies);
        static::$instance[$className] = $class;
        return $class;
    }

    /**
     * @param array $parameters
     * @return array
     */
    public static function getDependencies(array $parameters)
    {
        $dependencies = [];
        /** @var \ReflectionParameter $parameter */
        foreach ($parameters as $parameter) {
            /** @var \ReflectionType $param_type */
            $param_type = $parameter->getType();
            //参数类型是否为非类的内置类型 array string int
            $isBuiltin = $param_type->isBuiltin();
            if ($isBuiltin) {
                // 是变量,有默认值则设置默认值
                $dependencies[] = static::resolveNonClass($parameter);
            } else {
                // 是一个类，递归解析
                $dependencies[] = static::build($param_type->getName());
            }
        }
        return $dependencies;
    }

    /**
     * @param \ReflectionParameter $parameter
     * @return mixed
     * @throws \Exception
     */
    public static function resolveNonClass(\ReflectionParameter $parameter)
    {
        // 有默认值则返回默认值
        if ($parameter->isDefaultValueAvailable()) {
            return $parameter->getDefaultValue();
        }
        throw new \Exception($parameter->getName() . ' must be not null');
    }


    public static function get($id)
    {
        if (static::has($id)) {
            return static::$instance[$id];
        }
        if (class_exists($id)) {
            return static::build($id);
        }
        throw new \Exception('class not found');  //实现的PSR规范的异常
    }

    public static function has($id)
    {
        return isset(static::$instance[$id]) ? true : false;
    }


    /**
     * 调用方法
     * @param $instance
     * @param string $method_string
     */
    public static function invokeMethod($instance, string $method_string)
    {
        $class_ref = new \ReflectionClass($instance);
        $method_ref = $class_ref->getMethod($method_string);
        $parameters = $method_ref->getParameters();
        $dependencies = static::getDependencies($parameters);
        $method_ref->invokeArgs($instance, $dependencies);
    }
}