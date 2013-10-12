<?php
/**
 * 网站后台管理 网站配置
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class websetController extends admin{
    public function init(){
        parent::init();
        $this->M_webset =Templi::model('webset',true);
        Templi::include_common_file('Formcheck.class.php');
    }
    public function index(){
        $data = $this->M_webset->getlist();
        //print_r($list);
        //$this->M_webset->get_webset();
        include $this->display();
    } 
    public function add(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $checked = Formcheck::checking($info, $message, array(
              'title'=>array('func'=>'isnotnull','note'=>'标题不能为空！'),
              'name'=>array('func'=>'isnotnull','note'=>'字段名不能为空！'),
              'data'=>array('func'=>'isnotnull','note'=>'字段值能为空！'))
              );
            if(!$checked){
                $this->show_message($message);
            }
            if($this->M_webset->find(array('name'=>$info['name']))){
                $this->show_message('此字段已存在,请勿重复添加');  
            }
            unset($info['id']);
            if($this->M_webset->insert($info)){
                $this->show_message('添加成功');
            }else{
                $this->show_message('添加失败');
            }
        }else{
            
            include $this->display('webset_edit.tpl.php');
        }
    }
     public function edit(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $checked = Formcheck::checking($info, $message, array(
              'id'=>array('func'=>array('isnotnull','isnumber'),'note'=>array('内部错误！','内部错误')),
              'title'=>array('func'=>'isnotnull','note'=>'标题不能为空！'),
              'name'=>array('func'=>'isnotnull','note'=>'字段名不能为空！'),
              'data'=>array('func'=>'isnotnull','note'=>'字段值能为空！'))
              );
            if(!$checked){
                $this->show_message($message);
            }
            $where = " `name`='{$info['name']}' and `id` !={$info['id']}";
            if($this->M_webset->find($where)){
                $this->show_message('此字段已存在,请勿重复添加');  
            }
            //print_r($info);die;
            if($this->M_webset->update($info,array('id'=>$info['id']))){
                $this->show_message('修改成功');
            }else{
                $this->show_message('修改失败，请稍后重试');
            }
        }else{
            $id = intval($_GET['id']) or die;
            $info = $this->M_webset->find(array('id'=>$id));
            if(!$info) 
                $this->show_message('你查看的页面不存在或者已删除！');
                
            include $this->display();
        }
    }
    public function del(){
            $id =((int)$_GET['id']) or die;
            $info = $this->M_webset->find(array('id'=>$id));
            if(!$info)
                $this->show_message('您查看的页面不存在,或者已删除');
            
            if($this->M_webset->delete(array('id'=>$info['id']))){
                $this->show_message('删除成功');
            }else{
                $this->show_message('删除失败');
            }
      }
    public function clean_cache(){
        $result = $this->M_webset->cache->clean();
        //生成网站配置缓存
        $this->M_webset->get_webset();
        if($result){
            $this->show_message('缓存清理成功');
        }else{
            $this->show_message('缓存清理失败');
        }
    }
    /**
     * 服务器信息
     */
    public function server_info(){
        $disable_submenu= true;
        $server['time'] = date('Y-m-d H:i:s', time());
        $server['upfile'] = (ini_get('file_uploads')) ? '允许 ' . ini_get('upload_max_filesize') : '关闭';
        $server['register_globals'] = (ini_get('register_globals')) ? '允许' : '关闭';
        $server['safe_mode'] = (ini_get('safe_mode')) ? '允许' : '关闭';
        $server['software'] = $_SERVER['SERVER_SOFTWARE'];
        $server['memory_limit'] =  ini_get('memory_limit');
        if (function_exists('memory_get_usage')) {
            $server['memory'] = sizecount(memory_get_usage());
        }
        include $this->display();
    }
}
?>