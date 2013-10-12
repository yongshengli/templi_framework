<?php
/**
 * 网站后台管理入口文件
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
//templi::include_module_file('admin.class.php');
class indexController extends admin{
    function init(){
        parent::init();
        Templi::include_common_file('Formcheck.class.php');
        Templi::include_common_file('String.class.php');
    }
    public function index(){
        $M_role = Templi::model('admin_role',true);
        $info = Session::get('admin_info');
        $info['role']= $M_role->get_role($info['role_id']);
        //默认后台首页模块id
        $info['default_pid'] =29;
        $menulist =$this->getmenu();
        //print_r($_SESSION);
        $title =Templi::get_config('app_name').'管理后台';
        include $this->display('index_index.tpl.php');
    }
    public function menu(){
        $parent_id =isset($_GET['pid'])?(int)$_GET['pid']:$_GET['pid']=29;
        $menulist =$this->getmenu($parent_id);
        //print_r($menulist);
        include $this->display('index_menu.tpl.php');
    }
    public function login(){
        if(isset($_POST['dosubmit'])){
            $_POST = array_map('trim',$_POST);
            $result =Formcheck::checking($_POST,$message,array(
                'verify'=>array('func'=>array('isnotnull','isequal'),'note'=>array('验证码不能为空','验证码错误'),'param'=>array('',Session::get('verify'))),
                'username'=>array('func'=>array('isnotnull','is_username'),'note'=>array('账户不能为空','账户格式错误')),
                'password'=>array('func'=>'isnotnull','note'=>'密码不能为空'),
            ));
            if(!$result){
               $this->show_message($message); 
            }
            $username=trim($_POST['username']);
            $_user = templi::model('admin',true);
            $info  = $_user->find(array('adminname'=>$username));
            //print_r($info);
            $password = String::password($_POST['password'],$info['encrypt']);
            //die;
            if($info['password'] == $password){
                Session::set('adminid',$info['adminid']);
                Session::set('adminname',$info['adminname']);
                Session::set('admin_role_id',$info['role_id']);
                Session::set('admin_info',$info);
                //print_r($_SESSION);
                $_user->update(array('login_num'=>"+=1",'last_logintime'=>SYS_TIME,'last_loginip'=>getip()),array('adminid'=>$info['adminid']));
                $this->show_message('登陆成功','index.php?m=admin&c=index&a=index');
                die;
            }else{
                $this->show_message('密码错误');
            }
        }else{
            if($this->check_admin_login()){
                url_skip(APP_URL.'index.php?m=admin&c=index&a=index');
            }else{
                include $this->display('index_login.tpl.php');
            }
        }
    }
    /**
     * 退出
     */
    public function logout(){
        unset($_SESSION['adminid'],$_SESSION['adminname'],$_SESSION['admin_role_id'],$_SESSION['admin_info']);
        session_destroy();
        $this->show_message('安全退出',APP_URL.'index.php?m=admin&c=index&a=login');
    }
    /**
     * 修改密码
     */
    public function editpassword(){
        include $this->display('editpassword.tpl.php');
    }
}
?>