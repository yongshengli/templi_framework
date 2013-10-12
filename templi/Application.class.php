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
class Appliction{
    /**
     * 初始化应用
     */
    public static function init(){
        $GLOBALS['module']     = (isset($_GET['m']) && $_GET['m'])?trim($_GET['m']):(isset($_POST['m'])?trim($_POST['m']):'index');
        $GLOBALS['controller'] = (isset($_GET['c']) && $_GET['c'])?trim($_GET['c']):(isset($_POST['c'])?trim($_POST['c']):'index');
        $GLOBALS['action']     = (isset($_GET['a']) && $_GET['a'])?trim($_GET['a']):(isset($_POST['a'])?trim($_POST['a']):'index');
        $controller = self::loade_controller($GLOBALS['controller'],$GLOBALS['module']);
        if(substr($GLOBALS['action'],0,2)=='__'){
            if(APP_DEBUG)
                throw new Abnormal('受保护的方法不可访问',0,true);
            else
                show_404();
        }else{
	// 关闭APP_DUBUG时 对页面压缩
	if(APP_DEBUG || !ob_start('ob_gzhandler')) ob_start();
            call_user_func(array(&$controller,$GLOBALS['action']));
	    ob_end_flush();
        }
    }
    /**
     * 加载控制器
     */
    private static function loade_controller($classname,$module){
        if(!is_dir(Templi::get_config('app_path').'controller/'.$module)){
            if(APP_DEBUG)
                throw new Abnormal($module.'模块不存在',0,true);
            else
                show_404();
        }
        $classname .= 'Controller';
        $path = Templi::get_config('app_path').'controller/'.$module.'/'.$classname.'.php';
        if(file_exists($path)){
            Templi::include_file($path);
            return new $classname;
        }else{
            //如果设置了empty控制器 则调用该控制器
            $path =Templi::get_config('app_path').'controller/'.$module.'/emptyController.php';
            if(file_exists($path)){
                Templi::include_file($path);
                return new emptyController();
            }else{
                if(APP_DEBUG)
                    throw new Abnormal($classname.'控制器不存在',0,true);
                else
                    show_404();
            }  
        }
    }
}
?>