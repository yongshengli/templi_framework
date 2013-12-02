<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * session 工厂函数
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-4-20
 */
class Session{
    /**
     * 初始化session 
     */
    public static function factory(){
        if(!isset($_SESSION)){
            $session_storage =templi::get_config('session_storage');
            $class_name ='Session_'.$session_storage;
            require_once('Session/'.$class_name.'.class.php');
            return new $class_name;
        }
    }
    /**
     * 获取session值
     */
    public static function get($name){
        if(isset($_SESSION[$name])){
            return $_SESSION[$name];
        }else{
            return NULL;
        }
    }
    /**
     * 设置session值
     */
    public static function set($name, $val='') {
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
    public static function remove($name) {
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
    static function id($id = null) {
        return isset($id) ? session_id($id) : session_id();
    }
    /**
     * 获得 当前session 名
     */
    static function name(){
         return isset($name) ? session_name($name) : session_name();
    }
}