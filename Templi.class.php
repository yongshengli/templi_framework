<?php
/**
 * TempLi框架 基于mvc开发模式 简单 小巧 易用
 * 核心类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-07-08
 */
require_once 'Application.class.php';

class Templi extends Application
{
    private static $_app = null;
    /**
     * 应用配置
     * @var array
     */
    private static $_config = array();
    /**
     * 当前模块名
     * @var null
     */
    private $_module = null;
    /**
     * 当前控制器
     * @var null
     */
    private $_controller = null;
    /**
     * 当前操作
     * @var null
     */
    private $_action = null;

    /**
     * 初始化 app
     */
    function __construct()
    {
        defined('IN_TEMPLI') or define('IN_TEMPLI', true);
        //TEMPLI 目录
        defined('TEMPLI_PATH') or  define('TEMPLI_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
        //当前时间
        defined('SYS_TIME') or define('SYS_TIME', time());
        //系统脚本开始时间
        defined('SYS_START_TIME') or define('SYS_START_TIME', microtime(true));
        //注册 __autoload 方法
        spl_autoload_register(array('self', '__autoload'));
    }

    /**
     * 获取版本信息
     */
    public static function getVersion()
    {
	    return '1.0.0';
    }

    /**
     * 获取 当前应用
     * @return application
     */
    public static function getAPP()
    {
        if (empty(self::$_app)) {
            self::$_app = new static();
        }
        var_dump(self::$_app);
        return self::$_app;
    }
    /**
     * 获取模块名
     */
    public function getModuleName()
    {
        return $this->_module;
    }

    /**
     * 获取控制器名
     */
    public function getControllerName()
    {
        return $this->_controller;
    }

    /**
     * 获取当前操作名
     */
    public function getActionName()
    {
        return $this->_action;
    }
    /**
     * 创建应用
     * @param type $config
     * @return \Templi
     */
    public static function createWebApp($config)
    {
        $app = self::getAPP();
        $conf = self::get_config($config);
        switch($conf['run_mode']){
            case 'development':
                 define('ERROR_TYPE', E_ALL & ~E_NOTICE);
                 defined('APP_DEBUG') or define('APP_DEBUG',true);
                 error_reporting(ERROR_TYPE);
                 break;
            case 'testing':
            case 'production':
                 define('ERROR_TYPE', 0);
                 defined('APP_DEBUG') or define('APP_DEBUG',false);
                 error_reporting(0);
                 break;
            default:
                exit('项目 run_mode 配置错误');
        }
        //自定义异常处理
        if(method_exists('Templi', 'appException') && function_exists('set_exception_handler')){
            set_exception_handler(array('Templi','appException'));
        }
        //自定义错误处理
        if(method_exists('Templi', 'appError') && function_exists('set_error_handler')){
            set_error_handler(array('Templi','appError'), ERROR_TYPE);
        }
        self::load_http_lib();
        return $app;
    }

    /**
     * 载入 http应用必须的 类库
     */
    private static function load_http_lib()
    {
        //载入公共函数库
        self::include_file(TEMPLI_PATH.'function.func.php');
        //载入异常处理类
        self::include_file(TEMPLI_PATH.'Abnormal.class.php');
        //载入路由配类
        self::include_file(TEMPLI_PATH.'Router.class.php');
        //载入控制器分配类
        self::include_file(TEMPLI_PATH.'Dispatcher.class.php');
        //载入 控制器类
        self::include_file(TEMPLI_PATH.'Controller.class.php');
        //载入 模型类
        self::include_file(TEMPLI_PATH.'Model.class.php');
        //载入 cookie 类
        self::include_file(TEMPLI_PATH.'Cookie.class.php');
    }

    /**
     * 初始化函数
     */
    public function run()
    {
        $router = new Router();
        $this->_module = $router->module;
        $this->_controller = $router->controller;
        $this->_action = $router->action;

        $dispatcher = new Dispatcher($router->module, $router->controller, $router->action);
        $dispatcher->execute();
    }
    /**
     * 获取配置文件信息
     * $field 为空时 获取全部配置信息
     * $field 为字符串时 返回当前 索引 配置值
     * $field 为数组时 设置配置信息
     * @param $field
     * @param null $default
     * @return array|mixed
     */
    public static function get_config($field = NULL, $default = NULL)
    {
        //设置配置信息
        if(is_array($field)){
            self::$_config = array_merge(self::$_config, $field);
        }
        if(is_string($field)){
            //return isset(self::$_config[$field])? self::$_config[$field]:$default;
            return self::getArrVal(self::$_config, $field, $default);
        }
        return self::$_config;
    }

    /**
     * 加载并实例化模型类
     * @param $model
     * @param bool $type true 高级载入模型文件 false 快捷实例化模型
     * @return
     * @internal param string $file
     */
    public static function model($model, $type=false)
    {
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
     * @return string
     */
    public function include_html($file,$module=null)
    {
        $this->include_common_file('View.class.php');
        $view = new View();
        if ($module) {
            $file = $module.'/'.$file;
        } else {
            $file = $this->getModuleName().'/'.$file;
        }
        return $view->loadView($file);
    }

    /**
     * 加载模块 函数库 类库文件
     * @param $file 文件名
     * @param $module 模块名
     * @return bool
     */
    public function include_module_file($file, $module=null)
    {
        if ($module) {
            $path = self::get_config('app_path').'controller/'.trim($module,'/').'/libraries/';
        } else {
            $path = self::get_config('app_path').'controller/'.$this->getModuleName().'/libraries/';
        }
        return self::include_file($path.$file);
    }

    /**
     * 加载公共 函数库 类库文件
     * @param $file 文件名
     * @param null $path
     * @internal param $module 模块名
     * @return bool
     */
    public function include_common_file($file, $path=null)
    {
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
}