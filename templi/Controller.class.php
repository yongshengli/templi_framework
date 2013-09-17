<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * TempLi 共共函数库 常用函数  
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-1-20
 */
abstract class Controller{
    
    private $view = null;   //视图对象
    function __construct(){
        templi::include_common_file('View.class.php');
        $this->view =new View();
        if(method_exists($this,'init'))
            $this->init();
    }
    /**
     * 给模板文件分配变量
     * @param string $name 变量名称
     * @param mid $value   变量值
     */
    protected function assign($name, $value=''){
        $this->view->assign($name, $value); 
    }
    /**
     * 给模板文件 批量分配变量
     * @param array $data 变量名称
     */
    protected function setOutput($data){
        $this->view->setOutput($data); 
    }
    /**
     * 显示 视图
     * @param $file 模板文件名称
     * @param $module 所在模块
     */
    protected function display($file=NULL,$module=NULL){
        $path = $module?$module.'/':$GLOBALS['module'].'/';
        $file = $file?$file:$GLOBALS['controller'].'_'.$GLOBALS['action'];
        $this->view->display($path.$file); 
    }
    /**
     * 消息提示页
     * @param string $msg 消息内容
     * @param string $url 跳转地址 
     * @param int $ms 等待时间
     */
    protected function show_message($msg, $url_forward=null, $ms=null, $module='index'){
        $data['url_forward'] = $url_forward?$url_forward:'goback';
        $data['ms'] = $ms?$ms:1250;
        $data['msg'] =$msg;
        $this->setOutput($data);
        $this->view->display($module.'/showmessage');
        die;
    }
    /**
     * 魔术方法 有不存在的操作的时候执行
     */
    function __call($action, $param){
        if(method_exists($this,'_empty')){
            $GLOBALS['action'] ='_empty';
            $this->_empty($action, $param);
        }else{
			if(APP_DEBUG)
                throw new Abnormal($action.' 方法不存在',0,true);
            else
                show_404();
        }
    }
}
?>