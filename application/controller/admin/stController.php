<?php
/**
 * 网站后台管理 后台角色管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class stController extends admin{
    public function init(){
        parent::init();
        $this->M_st =Templi::model('systematization');
        Templi::include_common_file('Tree.class.php');
        Templi::include_common_file('Formcheck.class.php');
    }
    public function index(){
        $config =array(
            'id'=>'name',
            'parent_id'=>'parent_name',
            'icon'=> array('│ ','├─ ','└─ '),
            'nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;",
        );
        
        $data = $this->M_st->select();
        //print_r($data);
        $O_tree =new Tree($data,$config);
        $rule ="<tr><td align='left'>\$name</td><td align='left'>\$spacer \$title</td>
        <td><a href='index.php?m=admin&c=st&a=edit&id=\$name&menu_pid=$_GET[menu_pid]'>修改</a> | <a href=''>删除</a></td></tr>";
        $tree_html = $O_tree->get_tree('_top',$rule);
        include $this->display();
    }
    public function add(){
        if(isset($_POST['dosubmit'])){
            $info =$_POST['info'];
            $checked = Formcheck::checking($info, $message, array(
                  'title'=>array('func'=>'isnotnull','note'=>'中文名不能为空！'),
                  'name'=>array('func'=>array('isnotnull','isunique'),'param'=>array('',array('table'=>'systematization','field'=>'name')),'note'=>array('英文名不能为空！','分类已存在')),
                  'parent_name'=>array('func'=>'isnotnull','note'=>'所属父类不能为空！'),
                  )
            );
            if(!$checked){
                $this->show_message($message);
            }
            unset($info['st_id']);
            if($this->M_st->insert($info)){
                $this->show_message('添加成功');
            }else{
                $this->show_message('添加失败');
            }
        }else{
            $parent_id =$_GET['pid']?strval($_GET['pid']):'';
            $config =array('id'=>'name','parent_id'=>'parent_name','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
            $data = $this->M_st->select(array('parent_name'=>'_top'));
            $O_tree =new Tree($data,$config);
            $rule ="<option value=\$name \$selected>\$spacer \$title</option>";
            $option_html = $O_tree->get_tree('_top',$rule,$parent_id);
            include $this->display('st_edit.tpl.php'); 
        }
    }
    public function edit(){
        if(isset($_POST['dosubmit'])){
            $info =$_POST['info'];
            $checked = Formcheck::checking($info, $message, array(
                  'title'=>array('func'=>'isnotnull','note'=>'中文名不能为空！'),
                  'name'=>array('func'=>'isnotnull','note'=>'英文名不能为空！'),
                  'parent_id'=>array('func'=>'isnotnull','note'=>'所属父类不能为空！'),
                  )
            );
            if(!$checked){
                $this->show_message($message);
            }
            if($this->M_st->update($info,array('id'=>$info['id']))){
                $this->show_message('修改成功');
            }else{
                $this->show_message('修改失败');
            }
        }else{
            $id =$_GET['id']?strval($_GET['id']):'';
            $info =$this->M_st->find(array('name'=>$id));
            //print_r($info);
            if(!$info){
                $this->show_message('你查看的页面不存在,或者已删除');
            }
            $config =array('id'=>'name','parent_id'=>'parent_name','nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;");
            $data = $this->M_st->select(array('parent_name'=>'_top'));
            $O_tree =new Tree($data,$config);
            $rule ="<option value=\$name \$selected>\$spacer \$title</option>";
            $option_html = $O_tree->get_tree('_top',$rule,$info['parent_name']);
            include $this->display(); 
        }
    }
    public function del(){
        
    }
    
}
?>