<?php
/**
 * 网站后台管理 地区管理
 * 
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
defined('IN_TEMPLI') or die('非法引用');
class locationController extends admin{
      public $_node;
      public function __construct(){
         parent::__construct();
         $this->M_location =templi::model('location',true);
         //Templi::include_common_file('Tree.class.php');
         Templi::include_common_file('Formcheck.class.php');
      }
      /**
       * 国家列表
       */
      public function index(){
            $where = '1 ';
            $page  = intval($_GET['page']);
            $parent_id =intval($_GET['parent_id']);
            $where .= $parent_id?" and `parent_id`={$parent_id}":' and `parent_id` =0';
            //echo $where;
            $data = $this->M_location->getlist($where,$field='*', '`listorder` desc, `location_id` asc', $page, 20, 8);
            //print_r($data);
            include $this->display();
      }
      public function add(){
            if(isset($_POST['dosubmit'])){
                
                
            }else{
               
            }
      }
      public function edit(){
            if(isset($_POST['dosubmit'])){
                
            }else{
                
                include $this->display();
            }
      }
      public function del(){
            
      }



}
?>