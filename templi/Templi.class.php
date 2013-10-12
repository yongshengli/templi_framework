<?php
/**
 * TempLi框架 基于mvc开发模式 简单 小巧 易用
 * 核心类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-07-08
 */
class Templi{
    
    private static $_config = array();
    
    /**
     * 获取版本信息
     */
    public static function getVersion()
   {
	return '1.0.0';
    }
    /**
     * 创建应用
     * @param type $config
     * @return \Templi
     */
    public function create_webapp($config){
        self::$_config = $config;
        return $this;
    }
    /**
     * 初始化函数
     */
    public function run(){
        defined('IN_TEMPLI') or define('IN_TEMPLI', true); 
        //TEMPLI 目录
        defined('TEMPLI_PATH') or  define('TEMPLI_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
        //当前时间
        defined('SYS_TIME') or define('SYS_TIME', time());
        //系统脚本开始时间
        defined('SYS_START_TIME') or define('SYS_START_TIME', microtime(true));
        //自定义异常处理
        set_exception_handler(array('Templi','appException'));
        //注册 __autoload 方法
        spl_autoload_register(array('self', '__autoload'));
        $this->init();
    }
    /**
     * 获取配置文件信息
     * $field 为空时 获取全部配置信息
     * $field 为字符串时 返回当前 索引 配置值
     * $field 为数组时 设置配置信息
     * @param $field 
     */
    public static function get_config($field = NULL){
        //设置配置信息
        if(is_array($field)){
            self::$_config = array_merge(self::$_config, $field);
        }
        if(is_string($field)){
            return isset(self::$_config[$field])? self::$_config[$field]:NULL;
        }
        return self::$_config; 
    }
    /**
     * 加载并实例化模型类
     * @param string $file 
     * @param bool $type true 高级载入模型文件 false 快捷实例化模型
     */
    public static function model($model,$type=false){
        static $_models;
        if(!isset($_models[$model.$type])){
            if($type){
                $class = $model.'Model';
                self::include_file(self::get_config('app_path').'model/'.$class.'.php');
                $_models[$model.$type] = new $class;
            }else{
                $_models[$model.$type] = new Model($model);
            }
        }
        return $_models[$model.$type];
    }
    /**
     * 加载模板视图文件
     * @param $file 文件名
     * @param $module 模块名 
     */
    public static function include_html($file,$module=null){
        self::include_common_file('View.class.php');
        $view =new View();
        $file = $module?($module.'/'.$file):($GLOBALS['module'].'/'.$file);
        return $view->loadView($file);
    }
    /**
     * 加载模块 函数库 类库文件 
     * @param $file 文件名
     * @param $module 模块名 
     */
    public static function include_module_file($file,$module=null){
        $path =$module?self::get_config('app_path').'controller/'.trim($module,'/').'/libraries/':self::get_config('app_path').'controller/'.$GLOBALS['module'].'/libraries/';
        return self::include_file($path.$file);
    }
    /**
     * 加载公共 函数库 类库文件
     * @param $file 文件名
     * @param $module 模块名 
     */
    public static function include_common_file($file,$path=null){
        
        if(is_null($path)){
            $result = self::include_file(self::get_config('app_path').'/libraries/'.$file);
            if($result == false){
                $result = self::include_file(TEMPLI_PATH.$file);
            }
        }else{
            $result = self::include_file(trim($path,'/').'/'.$file);
        }
        return $result;
    }
    /**
     * 多位置引入文件
     * 找到文件 后返回
     */
    public static function array_include($file_arr=array()){
        foreach($file_arr as $file){
            $result = self::include_file($file);
            if($result){
                return $result;
            }
        }
        return false;
    }
    /**
     * 引入文件
     * 默认加载路径是 系统类库
     */
    public static function include_file($file){
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
    public static function appException($e){
        halt($e->__toString());
    }
    /**
     * 自动加载 类文件 包括 Model、controller、libraries 类
     */
    public static function __autoload($class){
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
    /**
     * 初始化 app
     */
    private function init(){
        
        //设置运行模式
        define('ENVIRONMENT',self::get_config('run_mode'));
        //error_reporting(E_ERROR | E_WARNING | E_PARSE);
        switch(ENVIRONMENT){
            case 'development':
                 error_reporting(E_ALL & ~E_NOTICE); 
                 defined('APP_DEBUG') or define('APP_DEBUG',true);
                 break;
            case 'testing':
            case 'production':
                 error_reporting(0);
                 defined('APP_DEBUG') or define('APP_DEBUG',false);
                 break;
            default:
                exit('项目 run_mode 配置错误');
        }
        //载入公共函数库
        self::include_file(TEMPLI_PATH.'function.func.php');
        //载入异常处理类
        self::include_file(TEMPLI_PATH.'Abnormal.class.php');
        //载入控制器分配类
        self::include_file(TEMPLI_PATH.'Application.class.php');
        //载入 控制器类
        self::include_file(TEMPLI_PATH.'Controller.class.php');
        //载入 模型类
        self::include_file(TEMPLI_PATH.'Model.class.php');
        //载入 cookie 类
        self::include_file(TEMPLI_PATH.'Cookie.class.php');
        Appliction::init();
        
    }
}
?>