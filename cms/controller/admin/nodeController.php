<?php
/**
 * 网站后台管理 后台菜单管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class nodeController extends admin{
      private $M_node;
      public function __construct(){
         parent::__construct();
         $this->M_node =templi::model('admin_node',true);
         Templi::include_common_file('Tree.class.php');
         Templi::include_common_file('Formcheck.class.php');
      }
      public function index(){
            $config =array(
                'id'=>'node_id',
                'icon'=> array('│ ','├─ ','└─ '),
                'nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;"
            );
            
            $data = $this->M_node->select();
            //print_r($data);
            $O_tree =new Tree($data,$config);
            $rule ="<tr><td>\$node_id</td><td align='left'>\$spacer \$title</td>
            <td><a href='index.php?m=admin&c=node&a=add&pid=\$node_id&menu_pid=$_GET[menu_pid]'>添加子类</a> | <a href='index.php?m=admin&c=node&a=edit&node_id=\$node_id&menu_pid=$_GET[menu_pid]'>修改</a> | <a href='index.php?m=admin&c=node&a=del&node_id=\$node_id'>删除</a></td></tr>";
            $tree_html = $O_tree->get_tree(0,$rule);
            include $this->display();
      }
      public function add(){
            if(isset($_POST['dosubmit'])){
                $info = $_POST['info'];
                $checked = Formcheck::checking($info, $message, array(
                  'parent_id'=>array('func'=>'isnotnull','note'=>'所属父类不能为空！'),
                  'module'=>array('func'=>'isnotnull','note'=>'模块不能为空！'),
                  'controller'=>array('func'=>'isnotnull','note'=>'控制器不能为空！'),
                  'action'=>array('func'=>'isnotnull','note'=>'方法不能为空！'))
                  );
                if(!$checked){
                    $this->show_message($message);
                }
                if($this->M_node->insert($info)){
                    $this->show_message('添加成功');
                    die;
                }else{
                    $this->show_message('添加失败');
                }
            }else{
                $parent_id =$_GET['pid']?(int)$_GET['pid']:'';
                $config =array('id'=>'node_id','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
                $data = $this->M_node->select();
                $O_tree =new Tree($data,$config);
                $rule ="<option value=\$node_id \$selected>\$spacer \$title</option>";
                $option_html = $O_tree->get_tree(0,$rule,$parent_id);
                //print_r($list);
                include $this->display('node_edit.tpl.php');
            }
      }
      public function edit(){
            if(isset($_POST['dosubmit'])){
                $info = $_POST['info'];
                $node_id =intval($_POST['node_id']);
                if(!$node_id){
                    $this->show_message('参数错误');
                }
                templi::include_common_file('Formcheck.class.php');
                $checked = Formcheck::checking($info, $message, array(
                  'parent_id'=>array('func'=>'isnotnull','note'=>'所属父类不能为空！'),
                  'module'=>array('func'=>'isnotnull','note'=>'模块不能为空！'),
                  'controller'=>array('func'=>'isnotnull','note'=>'控制器不能为空！'),
                  'action'=>array('func'=>'isnotnull','note'=>'方法不能为空！'))
                  );
                if(!$checked){
                    $this->show_message($message);
                }
                //print_r($info);die;
                if($this->M_node->update($info,array('node_id'=>$node_id))){
                    $this->show_message('修改成功');
                    die;
                }else{
                    $this->show_message('修改失败');
                }
            }else{
                $node_id =isset($_GET['node_id'])?(int)$_GET['node_id']:'';
                $info=$this->M_node->find(array('node_id'=>$node_id));
                if(!$node_id || !$info) $this->show_message('页面不存在');
                
                $config =array('id'=>'node_id','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
                $data = $this->M_node->select();
                //print_r($data);
                $O_tree =new Tree($data,$config);
                $rule ="<option value='\$node_id' \$selected>\$spacer \$title</option>";
                $option_html = $O_tree->get_tree(0, $rule, $info['parent_id']);
                //print_r($info);
                include $this->display('node_edit.tpl.php');
            }
      }
      public function del(){
            $node_id =(int)$_GET['node_id'];
            if(!$node_id){
                $this->show_message('您查看的页面不存在');
            }
            $info = $this->M_node->find(array('node'=>$node_id));
            if(!$info){
                $this->show_message('您查看的页面不存在,或者已删除');
            }
            if($this->M_node->delete(array('node_id'=>$info['node_id']))){
                $this->show_message('删除成功');
            }else{
                $this->show_message('删除失败');
            }
      }



}
?>