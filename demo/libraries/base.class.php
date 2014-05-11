<?php 
/**
 * 前台 基础 控制器类
 * @author 七殇酒
 * @qq 739800600
 * @email 739800600@qq.com
 * @date 2013-07-09
 */
class base extends Controller{
    
    private static $_member_info = array(); //登录用户信息
    private static $_group_info = array();  //用户组信息
    function init(){
        $this->load_files();
    }
    
    /**
     * 获取用户信息
     */
    protected function get_member_info(){
        if(empty(self::$_member_info)){
            if(Session::get('userid')){
                self::$_member_info['userid']   = Session::get('userid');
                self::$_member_info['username'] = Session::get('username');
                self::$_member_info['info']     = Templi::model('member',true)->get_member_info();
                self::$_member_info['data']     = Templi::model('member',true)->get_member_data();
                self::$_member_info['group_id'] = self::$_member_info['info']['group_id'];
            }
        }
        return self::$_member_info;
    }
    /**
     * 获取用户组信息
     *
     * @param int $group_id 用户组id
     *
     * @return array
     */
    protected function get_group_info($group_id=''){
        if(!$group_id) $group_id = Session::get('group_id');
        if(!empty(self::$_group_info[$group_id])){
            self::$_group_info[$group_id]['info']   =   Templi::model('member',true)->get_group_info();
            self::$_group_info[$group_id]['access'] =   Templi::model('member',true)->get_group_access();
        }
        return self::$_group_info[$group_id];
    }
    /**
     * 加载项目所需文件
     */
    private function load_files(){
        Templi::getAPP()->include_common_file('common.func.php');
    }
}