<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * cookie 类
 * @author 七殇酒
 * @email 739800600@qq.com
 * @lastmodify   2013-4-25
 *
 */
 class Cookie{
    /**
     * 读取cookie
     * @param string  $name 变量名
     * @param bool $encrtypt 是否需要对其进行 base64_decode 解密 
     */
     
    public static function get($name, $encrtypt=true){
        
        return $_COOKIE[$name]?base64_decode($_COOKIE[$name]):'';
    }
    /**
     * 设置写入cookie 支持批量写入
     * @param mid $name  变量名 $name = array('a'=>'a','b'=>b)
     * @param mixed $value  值
     * @param int $time 过期时间
     * @param bool $encrypt 是否用base64_encode 进行加密
     */
    public static function set($name, $value='', $time =0, $encrypt=true){
        if(!$name) return false;
        if(is_array($name)){
            foreach($name as $key=>$val)
                self::set($key, $val, $time, $encrypt);
        }else{
            $time =$time>0?$time:($value? 0:SYS_TIME-3600);
            $_COOKIE[$name] = $value;
            if(is_array($value)){
                foreach($value as $k=>$v){
                    if($encrypt)$v = base64_encode($v);
                    setcookie($name[$k], $v, $time, templi::get_config('cookie_path'));
                }
            }else{
                if($encrypt)$value = base64_encode($value);
                setcookie($name, $value, $time, templi::get_config('cookie_path'));
            }
        }
    }

}
?>