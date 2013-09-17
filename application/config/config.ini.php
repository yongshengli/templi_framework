<?php
defined('IN_TEMPLI') or die('非法引用');
return array(
    /**
     * 网站配置
     * 'app_url'=>'http://localhost/TempLi/',//网站参数设置
     * 'admin_url'=>'http://localhost/TempLi/', //后台地址
     */
    'app_name'=>'TempliCMS 网站管理系统',
    //'app_url'=>'http://localhost/TempLi/',
    'web_domain'=>'www.templi.cc',
    //'admin_url'=>'http://localhost/TempLi/',
    /**
     * 运行模式
     * run_mode=>array('development','testing','production')
     */
     'run_mode'=>'development',
    /**
     * 数据库配置
     * 'dbhost'=>'localhost', //主机
     * 'dbuser'=>'root',      //数据库用户名
     * 'dbpsw'=>'liyongsheng',//数据库密码
     * 'prefix'=>'templi_',   //表前缀
     * 'dbdrive'=>'pdo_mysql', //数据库驱动
     */
    'dbhost'=>'localhost',
    'dbuser'=>'root',
    'dbpsw'=>'liyongsheng',
    'dbname'=>'templi',
    'prefix'=>'templi_',
    'dbdrive'=>'pdo_mysql',
    'charset'=>'utf8',
    /**
     * url 配置
     * 类型 http://www.TempLi.com/m/c-a-id-5.html 1 静态需要开启apache rewrite 模块 0 动态 
     */ 
    'url_mode'=>0,
    
	 /**
     * 缓存配置
     *     cache_file
     *      'cache_type'=>'file',
     *      'cache_datatype'=>'array',
     *      'cache_timeout'=>3600,
     *     cache_memcache
     */
	 
	'cache_type'=>'file',
    'cache_datatype'=>'array',
    'cache_timeout'=>3600,
    
	/**
     * cookie 配置
	 *     'cookie_domain' => '', //Cookie 作用域
	 *     'cookie_path' => '', //Cookie 作用路径
	 *     'cookie_pre' => 'TempLi_', //Cookie 前缀，同一域名下安装多套系统时，请修改Cookie前缀
	 *     'cookie_ttl' => 0, //Cookie 生命周期，0 表示随浏览器进程
	 */
     'cookie_domain' => '',
     'cookie_path' => '',
     'cookie_pre' =>'TempLi_',
     'cookie_ttl' => 0,
	/**
	 * Session配置
     * session_mysql
	 *     'session_storage' => 'mysql',
	 *     'session_lifetime' => 1800, //session 生命周期，0 表示随浏览器进程
	 *     'session_n' => 0
     * session_file
     *  'session_storage' => 'file',
     *  'session_savepath' => CACHE_PATH.'sessions/',
     * 
	 */
	'session_storage' => 'mysql',
	'session_lifetime' => 1800,
	'session_savepath' => APP_PATH.'cache/sessions/',
);

?>
