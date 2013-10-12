<?php
/**
 * 用户登陆
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class indexController extends base{
    public function init(){
        $this->M_member = Templi::model('member');
    }
    public function index(){
        //$seo['title'] = '家园首页';
        //$this->display();
        $this->show_message('假设中');
    }
}
?>