<?php


namespace core;


class App
{
    public static function run()
    {
        self::init();
    }

    public static function init()
    {
        //$builder = new ContainerBuilder();
        //$builder->useAnnotations(true);
        //BeanFactory::setContainer($builder->build());
        //把第三方容器替换成自定义容器
        BeanFactory::setContainer(\core\Container::getContainer());
    }
}