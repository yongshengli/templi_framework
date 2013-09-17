<?php
/**
 * 网站后台管理 后台角色管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class categoryController extends admin{
    public function init(){
        parent::init();
        $this->M_category =Templi::model('category',true);
        $this->M_st =Templi::model('systematization');
        Templi::include_common_file('Tree.class.php');
        Templi::include_common_file('Formcheck.class.php');
    }
    public function index(){
        $config =array(
            'id'=>'id',
            'icon'=> array('│ ','├─ ','└─ '),
            'nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;"
        );
        
        $data = $this->M_category->select();
        //print_r($data);
        $O_tree =new Tree($data,$config);
        $rule ="<tr><td>\$id</td><td align='left'>\$spacer \$title</td><td align='left'>\$st_name</td>
        <td><a href='index.php?m=admin&c=category&a=add&pid=\$id&menu_pid=$_GET[menu_pid]'>添加子类</a> | <a href='index.php?m=admin&c=category&a=edit&id=\$id&menu_pid=$_GET[menu_pid]'>修改</a> | <a href=''>删除</a></td></tr>";
        $tree_html = $O_tree->get_tree(0,$rule);
        include $this->display();
    }
    public function add(){
        if(isset($_POST['dosubmit'])){
            $info =$_POST['info'];
            $checked = Formcheck::checking($info, $message, array(
                  'title'=>array('func'=>'isnotnull','note'=>'中文名不能为空！'),
                  'category'=>array('func'=>'isnotnull','note'=>'英文名不能为空！'),
                  'parent_id'=>array('func'=>'isnotnull','note'=>'所属父类不能为空！'),
                  'st_name'=>array('func'=>'isnotnull','note'=>'模块不能为空！'),
                  )
            );
            if(!$checked){
                $this->show_message($message);
            }
            if($this->M_category->find(array('category'=>$info['category']))){
                $this->show_message('此栏目已存在,勿重复添加');
            }
            unset($info['id']);
            if($this->M_category->insert($info)){
                $this->show_message('添加成功');
            }else{
                $this->show_message('添加失败');
            }
        }else{
            $parent_id =$_GET['pid']?(int)$_GET['pid']:'';
            //父分类
            $config['parent'] =array('id'=>'id','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
            $data = $this->M_category->select();
            $O_tree =new Tree($data,$config['parent']);
            $rule ="<option value=\$id \$selected>\$spacer \$title</option>";
            $option_html = $O_tree->get_tree(0,$rule,$parent_id);
            
            //所属类别
            $config['st'] =array('id'=>'name','parent_id'=>'parent_name','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
            $st_data = $this->M_st->select(array('parent_name'=>'category'));
            $O_tree =new Tree($st_data,$config['st']);
            $rule ="<option value=\$name \$selected>\$spacer \$title</option>";
            $st_option_html = $O_tree->get_tree('category',$rule);
            
            include $this->display('category_edit.tpl.php'); 
        }
    }
    public function edit(){
        if(isset($_POST['dosubmit'])){
            $info =$_POST['info'];
            $checked = Formcheck::checking($info, $message, array(
                  'id'=>array('func'=>'isnotnull','note'=>'参数错误！'),
                  'title'=>array('func'=>'isnotnull','note'=>'中文名不能为空！'),
                  'category'=>array('func'=>'isnotnull','note'=>'英文名不能为空！'),
                  'parent_id'=>array('func'=>'isnotnull','note'=>'所属父类不能为空！'),
                  'st_name'=>array('func'=>'isnotnull','note'=>'模块不能为空！'),
                  )
            );
            if(!$checked){
                $this->show_message($message);
            }
            if($this->M_category->update($info,array('id'=>$info['id']))){
                $this->show_message('添加成功');
            }else{
                $this->show_message('添加失败');
            }
        }else{
            $id =$_GET['id']?(int)$_GET['id']:'';
            $info =$this->M_category->find(array('id'=>$id));
            //print_r($info);
            if(!$info){
                $this->show_message('你查看的页面不存在,或者已删除');
            }
            $config =array('id'=>'id','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
            $data = $this->M_category->select();
            $O_tree =new Tree($data,$config);
            $rule ="<option value=\$id \$selected>\$spacer \$title</option>";
            $option_html = $O_tree->get_tree(0,$rule,$info['parent_id']);
            
            //所属类别
            $config['st'] =array('id'=>'name','parent_id'=>'parent_name','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
            $st_data = $this->M_st->select(array('parent_name'=>'category'));
            $O_tree =new Tree($st_data,$config['st']);
            $rule ="<option value=\$name \$selected>\$spacer \$title</option>";
            $st_option_html = $O_tree->get_tree('category',$rule,$info['st_name']);
            include $this->display(); 
        }
    }
    
}
?>