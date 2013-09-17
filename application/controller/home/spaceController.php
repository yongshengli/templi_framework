<?php
/**
 * 用户登陆
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class spaceController extends base{
    
    private $userid     =   NULL;   //空间所有者id
    private $username   =   NULL;   //空间所有者 用户名
    private $userInfo   =   array();//空间所有者信息
    private $userData   =   array();//空间所有者数据
    private $permission =   array();//空间权限设置
    
    public function init(){
        $this->M_member = Templi::model('member',true);
        Templi::include_common_file('Formcheck.class.php');
        Templi::include_common_file('String.class.php');
        $this->get_space_info();
    }
    public function index(){
        $data['seo']['title']=$this->username.'的空间'.Templi::get_config('app_name');
        $this->setOutput($data);
        $this->display('space/space_index');
    }
    public function profile(){
        
    }
    /**
     * 获取空间的访问 皮肤等设置信息
     */
    private function get_space_info(){
        $this->userid   =   $_GET['id']?intval($_GET['id']):Session::get('userid') or show_404();
        $this->userInfo =   $this->M_member->get_member_info($this->userid);
        $this->username =   $this->userInfo['username'];
        $this->userData =   $this->M_member->get_member_data($this->userid);
        //print_r($this->userInfo);
        // 获取空间访问权限 信息
        
        $this->assign('userInfo',$this->userInfo);
    }
}
?>