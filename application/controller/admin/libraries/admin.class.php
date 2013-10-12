<?php
/**
 * 后台管理基础类
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or define('IN_TEMPLI', true);
//开启session
Templi::include_common_file('Session.class.php');
Session::factory();
//print_r($_SESSION);
class admin extends Controller{
    function init(){
        //检查是否登录
        if(!($GLOBALS['controller']=='index' && $GLOBALS['action']=='login')){
            $this->check_login();
            //检查用户所在组 是否有权访问
            $this->access_check();
        }
        
    }
    /**
     * 登录检查器
     */
    protected function check_login(){
        if(!$this->check_admin_login()){
            $this->show_message('请先登录','index.php?m=admin&c=index&a=login');
        }
    }
    /**
     * 检查用户是否登录
     * @return true 已登录 false 未登录
     */
    protected function check_admin_login(){
        if((Session::is_set('adminid')) && (Session::get('adminid'))){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 获得菜单
     */
    public function getmenu($parent_id=0){
        $_node= templi::model('admin_node',true);
        $menulist = $_node->get_child_node($parent_id);
        //print_r($menulist);
        return $menulist;
    }
    /**
     * 权限检查器
     */
    protected function access_check(){
        $role_id =Session::get('admin_role_id');
        if($role_id==1) return true;
        $M_access = Templi::model('admin_access',true);
        if(!($M_access->cheack_access($role_id, $GLOBALS['module'], $GLOBALS['controller'], $GLOBALS['action']))){
            $this->show_message('您所在的用户组无权访问');
        }  
    }
    /**
     * 提示跳转页
     */
    public function show_message($msg, $url_forward=null, $ms=null,$template_dir=null){
        $url_forward = $url_forward?$url_forward:'goback';
        $ms = $ms?$ms:1250;
        include $this->display('showmessage.tpl.php','admin');
        die;
    }
    /**
     * 加载模板页
     */
    protected function display($file=null,$module=''){
        if(is_null($file))
            $file = $GLOBALS['controller'].'_'.$GLOBALS['action'].'.tpl.php';
        $module = $module?$module:$GLOBALS['module'];
        if($module=='admin'){
            return Templi::get_config('app_path').'view/'.$module.'/'.$file;
        }else{
            return Templi::get_config('app_path').'view/'.$module.'/admin/'.$file;
        }
        
    }
    public function tpl($file){
        $path = Templi::get_config('app_path').'view/admin/';
        return $path.$file.'.tpl.php';
    }
    /**
     * ip检查
     */
    protected static function ip_check($type=null){
        $type=$type?$type:'white_list';
        $ip = Zend_Adver_Tool::getIp();
        //$ip ='192.168.1.33';
        $ip_options =array('192.168.3.*','127.0.0.1');
        if($type=='white_list'){
            for($i=0;$i<count($ip_options);$i++){
                //echo $i;
                $reslut = self::ip_check_tool($ip,$ip_options[$i]);
                if($reslut) break;
                
            }
            if(!$reslut){
               $this->show_message('你的ip'.$ip.',不允许访问'); 
            } 
        }
    }
    /**
     * ip检查工具
     * @param $curr_ip  待检查的ip
     * @param $access_ip ip范围 127.0.0.1 或 127.0.0.* 或 127.*.*.* 或 127.0.1.3-105
     */
    protected static function ip_check_tool($curr_ip,$access_ip){
        if($curr_ip==$access_ip){
            return true;
        }
        $ip_arr=explode('.',$curr_ip);
        $access_ip_arr = explode('.',$access_ip);
        $count = count($access_ip_arr)-1;;
        for($i=0;$i<=$count;$i++){
            //echo $access_ip_arr[$i];
            if($access_ip_arr[$i]=='*'){
                  return true;
            }elseif(is_numeric($access_ip_arr[$i])){
                if($ip_arr[$i]!=$access_ip_arr[$i]){
                    return false;
                }
            }elseif(preg_match('/(\d+)-(\d+)/i',$access_ip_arr[$i],$opt)){
                //print_r($opt);
                if(!($ip_arr[$i]>=$opt[1] && $ip_arr[$i]<=$opt[2])){
                    return false;
                }
            }
            //echo '<br>'.$access_ip_arr[$i].'<br>';
         }
         //die($i);
         return true;
    }
    /*public function _empty($action,$param){
        echo $action;
    }*/
}
?>