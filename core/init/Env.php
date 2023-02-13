<?php


namespace core\init;


class Env
{
    private static $env = [];

    public static function init()
    {
        self::$env = parse_ini_file(ROOT_PATH . "/env");
    }

    public static function get(string $key, string $default = '')
    {
        return self::$env[$key] ?? $default;
    }

    public static function set(string $key, string $value)
    {
        self::$env[$key] =  $value;
    }

}