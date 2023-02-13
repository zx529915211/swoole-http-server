<?php

namespace core\annotationHandlers;

use core\annotations\Db;
use core\BeanFactory;

return [
    //属性注解  传入$prop属性反射对象、$instance实例化的类、$anno_ref注解反射对象
    Db::class => function (\ReflectionProperty $prop, $instance, $anno_ref) {
        /**@var $myDb \core\init\Db*/
        $myDb = BeanFactory::getBean(\core\init\Db::class);
        $dbSource = $anno_ref->getArguments()['source'];
        if ($dbSource != 'default') {
            $bean_name = \core\init\Db::class . "_" . $dbSource;
            $otherDb = BeanFactory::getBean($bean_name);
            if (!$otherDb) {
                $otherDb = clone $myDb;
                $otherDb->setDbSource($dbSource);
                BeanFactory::setBean($bean_name, $otherDb);
            }
            $myDb = $otherDb;
        }
        $prop->setAccessible(true);
        $prop->setValue($instance, $myDb);
    },

];