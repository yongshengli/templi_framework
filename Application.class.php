<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 14-5-9
 * Time: 下午4:16
 */

class Application
{

    /**
     * 获取 数组中元素的值
     * @param array $arr
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function getArrVal(array $arr, $key, $default = NULL)
    {

        $temp =  explode('.', $key);
        $myKey = $temp[0];

        if (!isset($arr[$myKey])){
            return $default;
        }

        if(isset($temp[1])) {
            array_shift($temp);
            $temp = implode('.', $temp);
            return self::getArrVal($arr[$myKey], $temp, $default);
        }
        return $arr[$myKey];
    }
    /**
     * 引入文件
     * 默认加载路径是 系统类库
     */
    public static function include_file($file)
    {
        static $_request_files = array();
        if(!isset($_request_files[$file])){
            if(!file_exists($file)){
                return false;
            }
            $_request_files[$file] = require($file);
        }
        return $_request_files[$file];
    }

    /**
     * 自定义异常处理
     */
    public static function appException($e)
    {
        $error = array(
            'code'=>    $e->getCode(),
            'message'=> $e->getMessage(),
            'file'=>    $e->getFile(),
            'line'=>    $e->getLine(),
            'trace'=>   $e->__toString());
        halt($error);
    }
    /**
     * 自定义错误处理
     */
    public static function appError($errno, $errstr, $errfile, $errline)
    {
        //throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        $error = array('message'=>$errstr, 'file'=>$errfile, 'line'=>$errline);
        $trace = debug_backtrace();
        $trace = array_slice($trace,3); //丢弃 数组前三个 跟踪
        $error['trace'] = '';
        foreach ($trace as $key => $val){
            $error['trace'] .= sprintf("#%d [%s] %s(%d) %s%s%s(%s)\n",
                $key,
                date('y-m-d H:i:s'),
                $val['file'],
                $val['line'],
                $val['class'],
                $val['type'],
                $val['function'],
                implode(',', $val['args'])
            );
        }
        halt($error);
    }
    /**
     * 自动加载 类文件 包括 Model、controller、libraries 类
     */
    public static function __autoload($class)
    {
        switch(TRUE){
            case substr($class,-5)=='Model':
                self::include_file(self::get_config('app_path').'model/'.$class.'.php');
                break;
            case substr($class,-10)=='Controller':
                self::array_include(array(
                    self::get_config('app_path').'controller/'.$GLOBALS['module'].'/libraries/'.$class.'.php',
                    self::get_config('app_path').'controller/'.$class.'.php'
                ));
                break;
            default :
                //libraries 必须以 .class.php 结尾才可自动载入
                self::array_include(array(
                    self::get_config('app_path').'controller/'.$GLOBALS['module'].'/libraries/'.$class.'.class.php',
                    self::get_config('app_path').'libraries/'.$class.'.class.php',
                    TEMPLI_PATH.$class.'.class.php',
                ));
        }
    }
} 