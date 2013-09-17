<?php
/**
 * 用户资料 控制器
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-06-25
 */
defined('IN_TEMPLI') or die('非法引用');
templi::include_module_file('member.class.php');
class member_infoController extends member{
    public function init(){
        parent::init();
        $this->M_member = Templi::model('member',true);
        Templi::include_common_file('Formcheck.class.php');
        Templi::include_common_file('String.class.php');
        $this->leftmenu();
    }
    /**
     * 修改基本资料
     */
    public function profile(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            //print_r($info);
            $result =Formcheck::checking($info, $message, array(
                'gender'=>array('func'=>'isnotnull','note'=>'性别不能为空'),
                'birthday'=>array('func'=>'isnotnull','note'=>'出生年月不能为空'),
            ));
            if(!$result){
               $this->show_message($message); 
            }
            
            if(Templi::model('member_info')->update($info,array('userid'=>Session::get('userid')))){
                $this->show_message('修改成功');
            }else{
                $this->show_message('修改失败,稍后重试！');
            }
        }else{
            $info = $this->M_member->get_member_info(Session::get('userid'));
            //print_r($info);
            $seo['title'] = '个人资料设置-'.Templi::get_config('app_name');
            $this->assign('info',$info);
            $this->assign('seo',$seo);
            $this->display('profile_layout');
        }
    }
    /**
     * 修改密码
     */
    public function password(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $result =Formcheck::checking($info,$message,array(
                'old_password'=>array('func'=>'isnotnull','note'=>'原密码不能为空'),
                'password'=>array('func'=>array('isnotnull','is_password','isequal'),
                                    'note'=>array('新密码不能为空','密码长度为6-20个字符','两次输入密码不一致'),
                                    'param'=>array('','',$info['repassword'])),
            )); 
            if(!$result){
               $this->show_message($message); 
            }
            $member = $this->M_member->find(array('userid'=>Session::get('userid')));
            $old_password =String::password($info['old_password'],$member['encrypt']);
            if($old_password != $member['password']){
                $this->show_message('旧密码输入错误');
            }
            $password =String::password($info['password'],$member['encrypt']);
            if($this->M_member->update(array('password'=>$password),array('userid'=>Session::get('userid')))){
                $this->show_message('密码修改成功');
            }else{
                $this->show_message('密码修改失败,请稍后重试');
            }
        }else{
            $info = $this->M_member->find(array('userid'=>Session::get('userid')));
            $seo['title'] ='修改密码-'.Templi::get_config('app_name');
            $this->assign('info',$info);
            $this->assign('seo',$seo);
            $this->display();
        }
    }
    /**
     * 上传头像
     */
    public function avatar(){
        if(isset($_POST['dosubmit'])){
            Templi::include_common_file('UploadFile.class.php');
            $config =array(
                        //'maxSize'=>30000,
                        'allowExts'=>array('jpg','jpeg','png','gif'),
                        'supportMulti'=>false,
                        'thumb'=>true,
                        'thumbMaxWidth'=>200,
                        'thumbRemoveOrigin'=>true,
                        'thumbPrefix'=>'',
                        'thumbFile'=>'original'
                    );
            $O_upload =new UploadFile($config);
            $savePath = UPLOAD_PATH.'avatar/'.Session::get('userid').'/';
            if(true ==$O_upload->uploadOne($_FILES['avatar'],$savePath)){
                return $O_upload->getUploadFileInfo();
            }else{
                return $O_upload->getErrorMsg();
            }
        }else{
            $seo['title'] ='上传头像-'.Templi::get_config('app_name');
            $this->assign('seo',$seo);
            $this->display();
        }
    }
    /**
     * 选择地区
     */
    public function location(){
        //$parent_id =isset($_GET['pid'])?intval($_GET['pid']):0;
        $area = Templi::model('location',true)->get_area();
        $this->assign('field',strval($_GET['field']));
        $this->assign('area',json_encode($area));
        $this->display();
    }
    /**
     * 左边菜单栏
     */
    private function leftmenu(){
        $leftmenu =Templi::model('menu')->select(array('st_name'=>'my_setnav'));
        foreach($leftmenu as $key=>$val){
            $sign = explode('|',$val['sign']);
            if($GLOBALS['controller']==$sign[1] && $GLOBALS['action']==$sign[2])
                  $leftmenu[$key]['current'] ='current';  
            else
                $leftmenu[$key]['current'] ='';
        }
        $this->assign('leftmenu',$leftmenu);
    }
}
?>