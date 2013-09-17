<?php
/**
 * 网站后台管理 后台账户管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-6-25
 */
defined('IN_TEMPLI') or die('非法引用');
Templi::include_module_file('admin.class.php','admin');
class member_manageController extends admin{
    public function init(){
        parent::init();
        $this->M_member =Templi::model('member');
        //Templi::include_common_file('Tree.class.php');
        //Templi::include_common_file('Formcheck.class.php');
    }
    public function index(){
        $page = (int)$_GET['page'];
        $data = $this->M_member->getlist($where='', $field='*', $order='userid desc', $page, $listNum=20, $pageNum=8);
        //print_r($data);
        include $this->display();
    }
}
?>