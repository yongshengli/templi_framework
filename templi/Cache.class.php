<?php
defined('IN_TEMPLI') or die('非法引用');
class Cache{
    public static function factory(){
        $cache_type =templi::get_config('cache_type');
        $class_name ='Cache_'.$cache_type;
        require_once('Cache/'.$class_name.'.class.php');
        return new $class_name;
    }
    
}
?>