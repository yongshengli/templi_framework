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

class member extends base{
    function init(){
        //检查是否登录
        parent::init();
        $this->check_login();
    }
    /**
     * 登录检查器
     */
    protected function check_login(){
        if(!($GLOBALS['module']=='home' && $GLOBALS['controller']=='member')){
            if(!$this->check_member_login()){
                $this->show_message('请先登录','index.php?m=home&c=member&a=login');
            }
        }
    }
    /**
     * 检查用户是否登录
     * @return true 已登录 false 未登录
     */
    protected function check_member_login(){
        if((Session::is_set('userid')) && (Session::get('userid'))){
            return true;
        }else{
            return false;
        }
    }
    /**
     * 设置用户登录状态
     */
    protected function set_member_status($memeber_info=''){
        //设置session
        if($memeber_info){
            $map =array(
                        'userid'=>$memeber_info['userid'],
                        'username'=>$memeber_info['username'],
                    );
            Session::set($map);
            //设置cookie
            Cookie::set('userid',$memeber_info['userid']);
            Cookie::set('username',$memeber_info['username']);
            Cookie::set('login_error_num',NULL);
            Templi::model('member',true)->update_user_login($memeber_info['userid']);
        }else{
            Session::remove(array('userid','username'));
            Cookie::set('username',NULL);
            Cookie::set('userid',NULL);
        }
    }
}
?>