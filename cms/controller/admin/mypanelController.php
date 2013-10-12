<?php
/**
 * 网站后台管理 后台角色管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class mypanelController extends admin{
    public function init(){
        parent::init();
        $this->M_admin =Templi::model('admin',true);
        Templi::include_common_file('Tree.class.php');
        Templi::include_common_file('Formcheck.class.php');
    }
    public function index(){
        $M_role = Templi::model('admin_role',true);
        $info = Session::get('admin_info');
        //print_r($info);
        $info['role']= $M_role->get_role($info['role_id']);
        include $this->display();
    }
    /**
     * 修改个人资料
     */
    public function edit_info(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $result = Formcheck::checking($info,$message,$rule=array(
                'adminid'=>array('func'=>'isnotnull','note'=>'参数错误！'),
                'email'=>array('func'=>array('isnotnull','isemail'),'note'=>array('email不能为空！','email格式不正确')),
            ));
            if(!$result){
                $this->show_message($message);
            }
            if($this->M_admin->update(array('email'=>$info['email']),array('adminid'=>Session::get('adminid')))){
                $this->show_message('修改成功');
            }else{
                $this->show_message('修改失败');
            }
        }else{
            $info= $this->M_admin->find(array('adminid'=>Session::get('adminid')));
            $disable_submenu = true;
            include $this->display();
        }
    }
    /**
     * 修改密码
     */
    public function edit_password(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $result = Formcheck::checking($info,$message,$rule=array(
                'adminid'=>array('func'=>'isnotnull','note'=>'参数错误！'),
                'old'=>array('func'=>'isnotnull','note'=>'旧密码不能为空！'),
                'new'=>array('func'=>'isnotnull','note'=>'新密码不能为空！'),
                'new2'=>array('func'=>array('isnotnull','isequal'), 'note'=>array('重复密码不能为空！','两次输入密码不一致'), 'param'=>array('',$info['new']))
            ));
            if(!$result){
                $this->show_message($message);
            }
            Templi::include_common_file('String.class.php');
            $admin_info =$this->M_admin->find(array('adminid'=>Session::get('adminid')));
            $password = String::password($info['old'],$admin_info['encrypt']);
            //echo $password,'==',$admin_info['password'];
            if($password !=$admin_info['password']){
                $this->show_message('输入的旧密码错误');
            }
            $password = String::password($info['new']);
            if($this->M_admin->update(array('password'=>$password['password'],'encrypt'=>$password['encrypt']),array('adminid'=>$admin_info['adminid']))){
                $this->show_message('密码修改成功');
            }else{
                $this->show_message('密码修改失败');
            }
        }else{
            $disable_submenu = true;
            $info =$this->M_admin->find(array('adminid'=>Session::get('adminid')));
            include $this->display('mypanel_edit_password.tpl.php');
        }
    }
    
    
}
?>