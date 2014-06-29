<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * session 封装
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-4-20
 */
class Session
{
    /**
     * session 工厂
     * 创造一个session存储方式
     */
    public static function factory()
    {
        if (!isset($_SESSION)) {
            $instance = null;
            $storageType = Templi::get_config('session_storage');
            $func = 'session_'.$storageType;
            if (method_exists('Session', $func)) {
                self::$func();
            } else {
                throw new Abnormal('session 存储方式'.$storageType.'不支持', 500);
            }
        }
    }
    /**
     * 获取session值
     */
    public static function get($name)
    {
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }else{
            return NULL;
        }
    }
    /**
     * 设置session值
     */
    public static function set($name, $val='')
    {
        if(!$name) return;
        if(is_array($name)){
            foreach($name as $key=>$value)
               self::set($key, $value);
        }else{
            if($val === NUll){
                unset($_SESSION[$name]);
            }else{
                $_SESSION[$name] =$val;
            }
        }
    }
    /**
     * 设置session值
     */
    public static function remove($name)
    {
        if(is_array($name)){
            foreach($name as $val){
                self::remove($val);
            }
        }else{
            unset($_SESSION[$name]);
        } 
    }
    /**
     * 判断session是否设置
     */
    static function is_set($name){
        return isset($_SESSION[$name]);
    }
    /**
     * 获得 当前 session id
     */
    static function id($id = null)
    {
        return isset($id) ? session_id($id) : session_id();
    }
    /**
     * 获得 当前session 名
     */
    static function name()
    {
         return isset($name) ? session_name($name) : session_name();
    }
    /**
     * 以文件形式存储session
     * @return mixed
     */
    private static function session_file()
    {
        $class_name ='Session_file';
        require_once('Session/'.$class_name.'.class.php');
        return new $class_name(Templi::get_config('session_savepath'));
    }

    /**
     * session 存储到mysql数据库
     * @return mixed
     */
    private static function session_mysql()
    {
        $class_name = 'Session_mysql';
        $sessionModel = Templi::get_config('session_model');
        $lifetime = Templi::get_config('session_lifetime');
        require_once('Session/'.$class_name.'.class.php');
        return new $class_name($sessionModel, $lifetime);
    }

    /**
     * session 存储到memcached
     */
    private static function session_memcached()
    {
        $class_name = 'Session_memcached';
        $memcaheHost = Templi::get_config('session_memcache_host');
        $memcachePort = Templi::get_config('session_memcache_port');
        if (class_exists('Memcached')) {
            $memcache = new Memcached();
        } else {
            throw new Abnormal('未安装memcached扩展', 500);
        }
        $memcache->addServer($memcaheHost, $memcachePort);
        require_once('Session/' . $class_name. '.class.php');
        return new $class_name($memcache);
    }

    /**
     * session 存储到memcache
     *
     * @throws Abnormal
     * @return mixed
     */
    private static function session_memcache()
    {
        $class_name = 'Session_memcache';
        $memcaheHost = Templi::get_config('session_memcache_host');
        $memcachePort = Templi::get_config('session_memcache_port');
        if(class_exists('Memcache')) {
            $memcache = new Memcache();
        } else {
            throw new Abnormal('未安装memcached扩展', 500);
        }
        $memcache->connect($memcaheHost, $memcachePort);
        require_once('Session/' . $class_name. '.class.php');
        return new $class_name($memcache);
    }
}