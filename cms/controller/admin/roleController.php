<?php
/**
 * 网站后台管理 后台角色管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class roleController extends admin{
    public function init(){
        parent::init();
        $this->M_role =Templi::model('admin_role',true);
        Templi::include_common_file('Formcheck.class.php');
    }
    public function index(){
        $page = (int)$_GET['page'];
        $data = $this->M_role->getlist($where='', $field='*', $order='role_id desc', $page, $listNum=20, $pageNum=8);
        //print_r($data);
        include $this->display();
    } 
    public function add(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $result = Formcheck::checking($info,$message,$rule=array(
                'title'=>array('func'=>'isnotnull','note'=>'角色名称不能为空！')
            ));
            if(!$result){
                $this->show_message($message);
                die;
            }
            $result = $this->M_role->find(array('title'=>$info['title']));
            if($result){
                $this->show_message('此角色已存在现在跳转到此角色编辑页','index.php?m=admin&c=role&a=edit&id='.$result['role_id']);
                die;
            }
            unset($info['role_id']);
            $info['ctime'] = SYS_TIME;
            if($this->M_role->insert($info)){
                $this->show_message('添加成功');
            }else{
                $this->show_message('添加失败');
            }
        }else{
            include $this->display('role_edit.tpl.php');
        }
    }
    public function edit(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $result = Formcheck::checking($info,$message,$rule=array(
                'role_id'=>array('func'=>'isnotnull','note'=>'内部错误！'),
                'title'=>array('func'=>'isnotnull','note'=>'角色名称不能为空！')
            ));
            if(!$result){
                $this->show_message($message);
                die;
            }
            $info['ctime'] = SYS_TIME;
            if($this->M_role->update($info,array('role_id'=>$info['role_id']))){
                $this->show_message('修改成功');
            }else{
                $this->show_message('修改失败');
            }
        }else{
            $role_id =(int)$_GET['id'];
            $info = $this->M_role->find(array('role_id'=>$role_id));
            //print_r($info);
            include $this->display();
        }
        
    }
    public function del(){
        $id =(int)$_GET['id'] or die('参数错误呀');
        $info = $this->M_role->find(array('role_id'=>$id));
        if(!$info)
            $this->show_message('您查看的页面不存在,或者已删除');
        
        if($this->M_role->delete(array('role_id'=>$info['role_id']))){
            $this->show_message('删除成功');
        }else{
            $this->show_message('删除失败');
        }
    }
    public function role_access(){
        $M_node = Templi::model('admin_node',true);
        $M_access = Templi::model('admin_access',true);
        Templi::include_common_file('Tree.class.php');
        
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $M_access->delete(array('role_id'=>$info['role_id']));
            foreach($info['node_ids'] as $val){
                $data = $M_node->find(array('node_id'=>$val),'node_id,module,controller,action');
                $data['role_id'] =$info['role_id'];
                //print_r($data);
                $result[]= $M_access->insert($data);
            }
            if(is_array($result)){
                $this->show_message('操作成功');
            }else{
                $this->show_message('操作失败');
            }
            
        }else{
            $role_id =$_GET['id']?(int)$_GET['id']:0 or $this->show_message('参数错误');
            $info = $this->M_role->find(array('role_id'=>$role_id));
            if(!$info){
                $this->show_message('你查看的页面不存在');
            }
            $access =$M_access->select(array('role_id'=>$role_id),'node_id');
            foreach($access  as $val){
                $check_ids[] =$val['node_id'];
            }
            //print_r($check_ids);
            $data = $M_node->select();
            $config = array('id'=>'node_id','select'=>'checked','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
            foreach($data as $key=>$val){
                $data[$key]['class'] = 'menu-node-'.$val['parent_id'];
            }
            $O_tree = new Tree($data, $config);
            $rule = "<tr id='node-\$node_id' class='\$class'><td align='left'>\$spacer 
            <input name='info[node_ids][]' type='checkbox' value='\$node_id' pid='\$parent_id' onclick='check_node(this);' \$selected/> \$title
            </td></tr>";
            $tree_html = $O_tree->get_tree(0,$rule,$check_ids);
            include $this->display();
        }
        
    }
}
?>