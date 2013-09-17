<?php
/**
 * 网站后台管理 后台文章管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
Templi::include_module_file('admin.class.php','admin');
class manageController extends admin{
    public function init(){
        parent::init();
        $this->M_news =Templi::model('news');
        Templi::include_common_file('Tree.class.php');
        Templi::include_common_file('Formcheck.class.php');
    }
    public function index(){
        $page = (int)$_GET['page'];
        $data = $this->M_news->getlist($where='', $field='*', $order='id desc', $page, $listNum=20, $pageNum=8);
        //print_r($data);
        include $this->display();
    } 
    public function add(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $result = Formcheck::checking($info,$message,$rule=array(
                'title'=>array('func'=>'isnotnull','note'=>'标题不能为空！'),
                'content'=>array('func'=>array('isnotnull'),'note'=>array('内容不能为空！'))
            ));
            if(!$result){
                $this->show_message($message);
            }
            unset($info['id']);
            if($this->M_news->add_news($info)){
                $this->show_message('添加成功');
            }else{
                $this->show_message('添加失败');
            }
        }else{
            $M_menu =Templi::model('menu');
            $config =array('id'=>'id','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
            $data = $M_menu->select(array('module'=>'news'));
            ///print_r($data);
            $O_tree =new Tree($data,$config);
            $rule ="<option value=\$id \$selected>\$spacer \$title</option>";
            $category_html = $O_tree->get_tree(0,$rule);
            include $this->display('manage_edit.tpl.php');
        }
    }
    public function edit(){
        if(isset($_POST['dosubmit'])){
            $info = $_POST['info'];
            $result = Formcheck::checking($info,$message,$rule=array(
                'title'=>array('func'=>'isnotnull','note'=>'标题不能为空！'),
                'content'=>array('func'=>array('isnotnull'),'note'=>array('内容不能为空！'))
            ));
            if(!$result){
                $this->show_message($message);
            }
            if($this->M_news->update($info,array('role_id'=>$info['role_id']))){
                $this->show_message('修改成功');
            }else{
                $this->show_message('修改失败');
            }
        }else{
            $id =(int)$_GET['id'];
            $info = $this->M_news->find(array('id'=>$id));
            //print_r($info);
            include $this->display();
        }
        
    }
    public function del(){
        
    }
    public function role_access(){
        $M_node = Templi::model('admin_node');
        $M_access = Templi::model('admin_access');
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