<?php
/**
 * 管理员登陆 及管理员 管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class adminController extends admin{
    public function init(){
        parent::init();
        $this->M_admin = Templi::model('admin',true);
        Templi::include_common_file('Formcheck.class.php');
        Templi::include_common_file('String.class.php');
    }
    public function index(){
        //$data = $this->M_admin->getlist();
        //echo Templi::get_config('db.master.prefix');die;
        $page =(int)$_GET['page'];
        $select = "SELECT a.*,ar.title AS role_name";
        $from  =" from ".Templi::get_config('db.master.prefix')."admin as a LEFT JOIN ".Templi::get_config('db.master.prefix')."admin_role as ar ON ar.role_id=a.role_id";
        $count = $this->M_admin->query("select count(*) as num".$from.' limit 1');
        $total = $count[0]['num'];
        Templi::include_common_file('Page.class.php');
        $O_page =new Page(array('total'=>$total,'current_page'=>$page));
        $data['page_html'] =$O_page->page_html();
        $data['list'] = $this->M_admin->query($select.$from);
        include $this->display();
    }
    /**
     * 添加管理员
     */
    public function add(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $result  = Formcheck::checking($info, $message, array(
                    'adminname'=>array('func'=>array('isnotnull','is_username'),'note'=>array('账户不能为空','账户格式不正确')),
                    'password'=>array('func'=>array('isnotnull','is_password'),'note'=>array('密码不能为空','密码长度6-20个字符')),
                    'role_id'=>array('func'=>'isnotnull','note'=>'请选择角色')
                )
            );
            if(!$result){
                $this->show_message($message); 
            }
            if($this->M_admin->find(array('adminname'=>$info['adminname']))){
                $this->show_message('此账号已存在');
            }
            $password =String::password($info['password']);
            $info['password'] = $password['password'];
            $info['encrypt'] = $password['encrypt'];
            unset($info['adminid']);
            //print_r($info);
            if($this->M_admin->insert($info)){
                $this->show_message('添加账户成功');
            }else{
                $this->show_message('添加账户失败，稍后重试');
            }
        }else{
            $M_role = Templi::model('admin_role',true);
            $role_list = $M_role->get_role();
            include $this->display('admin_edit.tpl.php');
        }
        
    }
    public function edit(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $result  = Formcheck::checking($info, $message, array(
                    'adminid'=>array('func'=>'isnotnull','note'=>'内部错误'),
                    'adminname'=>array('func'=>array('isnotnull','is_username'),'note'=>array('账户不能为空','账户格式不正确')),
                    'role_id'=>array('func'=>'isnotnull','note'=>'请选择角色')
                )
            );
            if(!$result){
                $this->show_message($message); 
            }
			if($info['password']){
				$password =String::password($info['password']);
				$info['password'] = $password['password'];
				$info['encrypt'] = $password['encrypt'];
			}else{
				unset($info['password']);
			}
            //print_r($info);
            if($this->M_admin->update($info,array('adminid'=>$info['adminid']))){
                $this->show_message('修改账户成功');
            }else{
                $this->show_message('修改账户失败，稍后重试');
            }
        }else{
            $adminid = (int)$_GET['id'] or $this->show_message('您查看的页面不存在，或者已经删除');
            $info = $this->M_admin->find(array('adminid'=>$adminid));
            if(!$info){
                $this->show_message('您查看的页面不存在，或者已经删除');
            }
            $M_role = Templi::model('admin_role',true);
            $role_list = $M_role->get_role();
            include $this->display('admin_edit.tpl.php');
        }
    }
    public function del(){
        $id =(int)$_GET['id'] or die('参数错误呀');
        $info = $this->M_admin->find(array('adminid'=>$id));
        if(!$info)
            $this->show_message('您查看的页面不存在,或者已删除');
        
        if($this->M_admin->delete(array('adminid'=>$info['adminid']))){
            $this->show_message('删除成功');
        }else{
            $this->show_message('删除失败');
        }
    }
}
?>