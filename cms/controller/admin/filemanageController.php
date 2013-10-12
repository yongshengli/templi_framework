<?php
/**
 * 管理员登陆 及管理员 管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class filemanageController extends admin{
    public function init(){
        parent::init();
        Templi::include_common_file('dir.func.php');
        Templi::include_common_file('Tree.class.php');
    }
    public function index(){
        $path = $_GET['path']?trim($_GET['path']):UPLOAD_URL;
        if(!is_dir($path)){
            $this->show_message('不是目录');
            die;
        }
        //$files = glob($path.'*');
        $handle = opendir($path);
        $i=0;
        while(false !== ($file = readdir($handle))){
            if($file=='.' || $file=='..') continue;
            $file_info = pathinfo($path.$file);
            $list[$i]['filetype'] = filetype($path.$file);
            $list[$i]['path'] = $path.$file;
            //$file[$i]['mime'] = mime_content_type($path.$file);
            $list[$i]['extension']   = $file_info['extension'];
            $list[$i]['name']     = $file;
            $i++;
        }
        //clearstatcache();
        closedir($handle);
        print_r($list);
        include $this->display();
        
    }
    public function dir_tree(){
        $data = dir_tree(UPLOAD_PATH);
        //print_r($data);
        $config =array(
            'id'=>'id',
            'title'=>'name',
            'icon'=> array('│ ','├─ ','└─ '),
            'nbsp'=> "&nbsp;&nbsp;&nbsp;&nbsp;"
        );
            
        //$data = $this->_node->select();
        //print_r($data);
        $O_tree =new Tree($data,$config);
        $rule ="<tr><td>\$id</td><td align='left'>\$spacer <a href='index.php?m=admin&c=filemanage&a=filelist&path=\$dir'>\$name</a></td>
        <td></td></tr>";
        $tree_html = $O_tree->get_tree(0,$rule);
        include $this->display();
        //echo $tree_html;
    }
    public function filelist(){
        $path = trim($_GET['path']);
        $list = dir_list($path);
        print_r($list);
    }
}
?>