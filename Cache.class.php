<?php
defined('IN_TEMPLI') or die('非法引用');
class Cache
{
    public static function factory()
    {
        $instance =  null;
        $cache_type = Templi::get_config('cache_type');
        switch ($cache_type)
        {
            case 'file':
                $class_name ='Cache_file';
                $dateType = Templi::get_config('cache_datatype');
                $timeout = Templi::get_config('cache_timeout');
                require_once('Cache/' . $class_name . '.class.php');
                $instance =  new $class_name($dateType, $timeout);
                break;
            case 'memcache':
                $class_name = 'Cache_memcache';
                require_once('Cache/' . $class_name . '.class.php');
                $instance = new $class_name(Templi::get_config('cache_timeout'));
                $instance->connect(
                    Templi::get_config('cache_host'),
                    Templi::get_config('cache_port')
                );
                break;
            case 'memcached':
                break;
        }
        return $instance;
    }
    
}