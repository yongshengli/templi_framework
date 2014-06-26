<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * 路由类
 *
 * php 模板引擎
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-3-19
 */
class Router
{
    public $module = '';
    public $controller = '';
    public $action = '';

    /**
     * 初始化应用
     */
    function __construct(){

        $this->module     = $this->__getCurrentModule();
        $this->controller = $this->__getCurrentController();
        $this->action     = $this->__getCurrentAction();

    }
    /**
     * 获取当前 模块
     * @return string
     * @throws Abnormal
     */
    private  function __getCurrentModule(){
        if (isset($_GET['m']) && $_GET['m']){
            $module = trim(strval($_GET['m']));
        } else {
            $module = Templi::get_config('default_module');
        }
        return $module;
    }

    /**
     * 获取当前 控制器
     *
     * @return string
     */
    private function __getCurrentController() {
        if (isset($_GET['c']) && $_GET['c']) {
           $controller = trim(strval($_GET['c']));
        }else {
            $controller = Templi::get_config('default_controller');
        }
        return $controller;
    }

    /**
     * 获取当前 操作
     *
     * @return string
     */
    public function __getCurrentAction() {
        if (isset($_GET['a']) && $_GET['a']) {
            $action = trim(strval($_GET['a']));
        } else {
            $action = Templi::get_config('default_action');
        }
        return $action;
    }
}