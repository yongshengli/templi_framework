<?php
/**
 * 管理员登陆 及管理员 管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
templi::include_module_file('admin.class.php');
class AttachmentController extends admin{
    protected function init(){
        parent::init();
    }
    public function index(){
        die;
    }
    public function swfupload(){
        if(isset($_POST['doupload'])){
            Templi::include_common_file('UploadFile.class.php');
            Templi::include_common_file('dir.func.php');
            $module =trim($_POST['module']);
            if(!$module){
                die(json_encode(array('status'=>'0','message'=>'未知模块,参数错误')));
            }
            $file_save_path = 'uploads/'.$module.'/'.Date('Y/m/d/'); 
            $config['maxSize'] = $_POST['maxSize']?intval($_POST['maxSize']):2048000;
            $config['allowExts']= $_POST['allowExts']?explode(',',trim($_POST['allowExts'])):array('jpg','jpeg','png','gif');
            $config['thumb'] = false;
            $config['thumbMaxWidth']= $_POST['thumbMaxWidth']?intval($_POST['thumbMaxWidth']):150;
            $config['thumbMaxHeight']= $_POST['thumbMaxHeight']?intval($_POST['thumbMaxHeight']):150;
            //die($_POST['config']);
            $config['savePath'] = PUBLIC_PATH.$file_save_path;
            dir_create($config['savePath']);
            $O_Upload = new UploadFile($config);
            //print_r($_FILES);die;
            $upload_info =$O_Upload->uploadOne($_FILES['Filedata']);
            if(!$upload_info) {// 上传错误提示错误信息
                die(json_encode(array('status'=>'0','message'=>$O_Upload->getErrorMsg())));
            }else{// 上传成功 获取上传文件信息
                $this->add_to_db($upload_info,$file_save_path,$module);
                die(json_encode(array('status'=>true,'url'=>$file_save_path.$upload_info[0]['savename'],'filename'=>$upload_info[0]['name'],'ext'=>$upload_info[0]['extension'])));
            }
        }else{
            $this->display();
        }
    }
    public function ueditor(){
        //die("{'state':'为啥不行'}");
        //header("Content-Type: text/html; charset=utf-8");
        if(isset($_POST['doupload'])){
            Templi::include_common_file('UploadFile.class.php');
            Templi::include_common_file('dir.func.php');
            $module =trim($_GET['module']);
            
            if(!$module){
                die("{'state':'未知模块，参数错误'}");
            }
            
            $file_save_path = $module.'/'.Date('Y/m/d/'); 
            $config['maxSize'] = $_POST['maxSize']?intval($_POST['maxSize']):2048000;
            $config['allowExts']= $_POST['allowExts']?explode(',',trim($_POST['allowExts'])):array('jpg','jpeg','png','gif');
            $config['thumb'] = false;
            $config['thumbMaxWidth']= $_POST['thumbMaxWidth']?intval($_POST['thumbMaxWidth']):150;
            $config['thumbMaxHeight']= $_POST['thumbMaxHeight']?intval($_POST['thumbMaxHeight']):150;
            //die($_POST['config']);
            $config['savePath'] = UPLOAD_PATH.$file_save_path;
            dir_create($config['savePath']);
            $O_Upload = new UploadFile($config);
            //die("{'state':'为啥不行'}");
            $upload_info =$O_Upload->uploadOne($_FILES['Filedata']);
            if(!$upload_info) {// 上传错误提示错误信息
                //die(json_encode(array('status'=>'0','message'=>$O_Upload->getErrorMsg())));
                die("{'state':'".$O_Upload->getErrorMsg()."'}");
            }else{// 上传成功 获取上传文件信息
                $this->add_to_db($upload_info,$file_save_path,$module);
                //die(json_encode(array('status'=>true,'url'=>$file_save_path.$upload_info[0]['savename'],'filename'=>$upload_info[0]['name'],'ext'=>$upload_info[0]['extension'])));
                die("{'url':'".UPLOAD_URL.$file_save_path.$upload_info[0]['savename']. "','name':'" . $upload_info['savename'] . "','original':'" . $upload_info["name"] . "','state':'SUCCESS'}");
            }
        }else{
            die("{'state':'不能提交'}");
        }
    }
    /**
     * 将上传文件信息插入数据表
     */
    public function add_to_db(&$upload_info,$file_save_path,$module){
        $M_attachment = Templi::model('attachment');
        $data['module']         =   $module;
        $data['userid']         =   Session::get('adminid');;
        $data['file_name']      =   $upload_info[0]['name'];
        $data['file_path']      =   $file_save_path;
        $data['file_savename']  =   $upload_info[0]['savename'];
        $data['file_type']      =   $upload_info[0]['type'];
        $data['file_size']      =   $upload_info[0]['size'];
        $data['file_ext']       =   $upload_info[0]['extension'];
        $data['file_hash']      =   $upload_info[0]['hash'];
        $data['is_admin']       =   1;
        $data['upload_ip']      =   getip();
        $data['upload_time']    =   SYS_TIME;
        
        $upload_info[0]['aid']  =   $M_attachment->insert($data);
        return $upload_info[0]['aid']?true:false;
    }
}
?>