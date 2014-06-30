<?php
/**
 * 应用抽象类
 * User: 七觞酒
 * Date: 14-5-9
 * Time: 下午4:16
 */

require_once 'Loader.class.php';

abstract class Application
{
    use Loader;
    /**
     * 获取当前的木块名
     * @return string
     */
    abstract  function getModuleName();

    /**
     * 获取当前的 控制器名
     * @return string
     */
    abstract function getControllerName();

    /**
     * 获取当前方法名
     * @return string
     */
    abstract function getActionName();
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
    public function __autoload($class)
    {
        switch(true){
            case substr($class,-5)=='Model':
                self::load(self::get_config('app_path').'model/'.$class.'.php');
                break;
            case substr($class,-10)=='Controller':
                self::array_include(array(
                    $this->get_config('app_path').'controller/'.$this->getModuleName().'/libraries/'.$class.'.php',
                    self::get_config('app_path').'controller/'.$class.'.php'
                ));
                break;
            default :
                //libraries 必须以 .class.php 结尾才可自动载入
                self::array_include(
                    array(
                        $this->get_config('app_path').'controller/'.$this->getModuleName().'/libraries/'.$class.'.class.php',
                        $this->get_config('app_path').'libraries/'.$class.'.class.php',
                        TEMPLI_PATH.$class.'.class.php',
                    )
                );
        }
    }
} 