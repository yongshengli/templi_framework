<?php
/**
 * 用户登陆
 * 此控制器中的 所有方法 不进行登录判断
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-06-25
 */
defined('IN_TEMPLI') or die('非法引用');
Templi::include_module_file('member.class.php');
class memberController extends member{
    public function init(){
        parent::init();
        $this->M_member = Templi::model('member',true);
        Templi::include_common_file('Formcheck.class.php');
        Templi::include_common_file('String.class.php');
    }
    public function login(){
        if(isset($_POST['dosubmit'])){
            $_POST['info']['captcha'] = strtolower($_POST['info']['captcha']);
            $info = $_POST['info'];
            if(Cookie::get('login_error_num')>=3){
                if(!$info['captcha'])
                    $this->show_message('请填写验证码');
                if($info['captcha'] != Session::get('verify'))
                    $this->show_message('验证码错误');
            }
            $result =Formcheck::checking($info,$message,array(
                'username'=>array('func'=>array('isnotnull','is_username'),'note'=>array('账户不能为空','用户名为3-30个字符的字符串，不可包含特殊符号')),
                'password'=>array('func'=>array('isnotnull'),'note'=>array('密码不能为空')),
            ));
            if(!$result){
               $this->show_message($message); 
            }
            $message= $this->do_login($info['username'],$info['password']);
            if($message=='success'){
                $this->show_message('登陆成功');
            }else{
                $this->show_message($message);
            }
            
        }else{
            if(Cookie::get('login_error_num')>=3)
                $show_captcha = true;
            
            $seo['title'] = '用户登录';
            $this->assign('show_captcha', $show_captcha);
            $this->assign('seo',$seo);
            $this->display();
        }
    }
    public function ajax_login(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST;
            $message['status'] = false;
            $result =Formcheck::checking($info,$message['msg'],array(
                'username'=>array('func'=>array('isnotnull','is_username'),'note'=>array('账户不能为空','用户名为3-30个字符的字符串，不可包含特殊符号')),
                'password'=>array('func'=>array('isnotnull'),'note'=>array('密码不能为空')),
            ));
            if(!$result){
                die(json_encode($message));
            }
            $message['msg']= $this->do_login($info['username'],$info['password']);
            if($message['msg']=='success'){
                $message['status'] = true;
            }elseif(Cookie::get('login_error_num')>=3){
                redirect(url('home/member/login'));
            }
            die(json_decode($message));
        }
    }
    public function register(){
        if(isset($_POST['dosubmit'])){
            $_POST['info']['captcha'] = strtolower($_POST['info']['captcha']);
            $info = $_POST['info'];
            $result =Formcheck::checking($info,$message,array(
                'captcha'=>array('func'=>array('isnotnull','isequal'),'note'=>array('验证码不能为空','验证码错误'),'param'=>array('',Session::get('verify'))),
                'username'=>array('func'=>array('isnotnull','is_username'),'note'=>array('账户不能为空','用户名为3-30个字符的字符串，不可包含特殊符号')),
                'email'=>array('func'=>array('isnotnull','isemail'),'note'=>array('Email不能为空','Email格式不正确')),
                'password'=>array('func'=>array('isnotnull','is_password','isequal'),
                                    'note'=>array('密码不能为空','密码长度为6-20个字符','两次输入密码不一致'),
                                    'param'=>array('','',$info['repassword'])),
            ));
            $info['register_time'] =SYS_TIME;
            if(!$result){
               $this->show_message($message); 
            }
            unset($info['repassword'],$info['captcha']);
            $password =String::password($info['password']);
            $info['password'] = $password['password'];
            $info['encrypt']  = $password['encrypt'];
            if($this->M_member->register_member($info)){
                $this->do_login($info['username'],$info['password']);
                $this->show_message('注册成功',url('home/member_info/profile'));
            }else{
                $this->show_message('注册失败,请联系管理员或者稍后重试');
            }
        }else{
            $seo['title'] = '用户注册';
            $this->assign('seo',$seo);
            $this->display();
        }
    }
    /**
     * 登录
     */
    private function do_login($username,$password){
        $member = $this->M_member->get_member_base($username);
        if(!$member)
            return '账号不存在';
        $password =String::password($password,$member['encrypt']);
        if($member && $password==$member['password']){
            $this->set_member_status($member);
            Cookie::set('login_error_num',null);
            return 'success';
        }else{
            Cookie::set('login_error_num',(Cookie::get('login_error_num')+1));  
            return '密码错误';
        }
    }
    public function logout(){
        $this->set_member_status();
        $this->show_message('安全退出');
    }
    /**
     * ajax 方法
     * 检查用户名是否存在
     */
    public function check_username(){
        if(isset($_GET['clientid'])){
            $info['username'] = $_GET['info']['username'];
            $result =Formcheck::checking($info,$message,array(
                'username'=>array('func'=>array('isnotnull','is_username'),'note'=>array('账户不能为空','用户名为3-30个字符的字符串，不可包含特殊符号'))
            ));
            if(!$result){
               die($message);
            }
            if($this->M_member->find(array('username'=>$info['username']))){
                //用户名已存在
                die('existent');
            }else{
                //用户名不存在
                die('inexistence');
            }
        }
    }
    /**
     * ajax 方法 检查验证码 是否 正确
     */
    public function check_captcha(){
        if(isset($_GET['clientid'])){
            $captcha = trim($_GET['info']['captcha']);
            if(!$captcha){
                die('error');
            }
            if($captcha==Session::get('verify')){
                die('correct');
            }else{
                die('wrong');
            }
        }
    }
}