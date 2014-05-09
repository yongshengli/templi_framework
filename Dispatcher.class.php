<?php 
defined('IN_TEMPLI') or die('非法引用');
/**
 * 控制器分配类
 * 
 * php 模板引擎
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-3-19
 */
class Dispatcher
{
    public $class = '';
    public $method = '';

    /**
     * 初始化应用
     */
    function __construct($class, $method)
    {
        $this->class = $class;
        $this->method = $method;
    }

    /**
     * 执行
     */
    public function execute()
    {
        $class = $this->__loade_controller();

        // 关闭APP_DUBUG时 对页面压缩
        if(APP_DEBUG || !ob_start('ob_gzhandler')) ob_start();
        call_user_func(array(&$class, $this->action));
        ob_end_flush();
    }
    /**
     * 加载控制器
     */
    private function __loade_controller()
    {
        $path = Templi::get_config('app_path').'controller/'.$this->class.'.php';
        if (file_exists($path)) {
            Templi::include_file($path);
            return new $this->class;
        } else {
            if(APP_DEBUG){
                throw new Abnormal($path.'控制器不存在', 0, true);
            } else {
                show_404();
            }
        }
    }
}