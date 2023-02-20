<?php


namespace core\lib;


class Config
{
    private static $config = [];

    public static function init()
    {
        $config_files = glob(ROOT_PATH . "/app/config/*.php");
        foreach ($config_files as $config_file){
            $config_data = require_once $config_file;
            $file_name = pathinfo($config_file)['filename'];
            self::$config[$file_name] = $config_data;
        }
    }

    public static function get(string $key, string $default = '')
    {
        if(empty($key)){
            return self::$config;
        }
        $key_array = explode('.', $key);
        $config = [];
        foreach ($key_array as $key) {
            $config = $config ? $config[$key] : self::$config[$key];
        }
        return $config ?? $default;
    }
}