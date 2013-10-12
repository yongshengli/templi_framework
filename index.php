<?php
/**
 * 入口文件
 */
define('ROOT_PATH',dirname(__FILE__).DIRECTORY_SEPARATOR);
define('IN_TEMPLI',true);

//public 公共目录
define('PUBLIC_PATH',ROOT_PATH.'public'.DIRECTORY_SEPARATOR);
//上传文件目录
define('UPLOAD_PATH',PUBLIC_PATH.'uploads'.DIRECTORY_SEPARATOR);
//项目url
define('_PHP_FILE_',rtrim($_SERVER['PHP_SELF'],'/'));
$_root = dirname(_PHP_FILE_);
//网站根url  不包含 http://www.templi.cc
define('APP_URL',($_root=='/' || $_root=='\\')?'':$_root);
//公共目录
define('PUBLIC_URL',APP_URL.'/public/');
define('UPLOAD_URL',PUBLIC_URL.'uploads/');
//js 路径
define('JS_URL',PUBLIC_URL.'js/');
//主题 路径
define('THEMES_URL',PUBLIC_URL.'themes/');
require ROOT_PATH.'templi/Templi.class.php';
$config = require ROOT_PATH.'/application/config/config.php';

$app = new Templi();
$app->create_webapp($config)->run();

?>