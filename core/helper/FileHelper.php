<?php


namespace core\helper;


class FileHelper
{
    /**
     * @param $dir
     * @param $ingore
     * @return string
     */
    public static function getFileMd5($dir, $ignore): string
    {
        $files = glob($dir);
        $ret = [];
        foreach ($files as $file) {
            if (is_dir($file) && strpos($file, $ignore) === false) {
                $ret[] = self::getFileMd5($file . "/*",$ignore);
            } elseif (pathinfo($file)['extension'] == 'php') {
                $ret[] = md5_file($file);
            }
        }
        return md5(implode("", $ret));
    }
}