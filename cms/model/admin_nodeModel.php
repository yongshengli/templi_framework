<?php
defined('IN_TEMPLI') or die('非法引用');
class admin_nodeModel extends Model{
    function __construct(){
        parent::__construct('admin_node');
    }
    public function get_child_node($parent_id=NUll, $reset_cache=false){
        $cacheid ='get_child_node';
        $list = $this->cache->get($cacheid);
        //print_r($list);
        if($reset_cache || !$list){
            $result =$this->select(array('display'=>1), '*','listorder desc');
            foreach($result as $v){
                $list[$v['parent_id']][$v['node_id']]=$v;
            }
            $this->cache->set($cacheid, $list);
            //echo 'ffff';
        }
        return is_null($parent_id)?$list:$list[$parent_id];
    }
    public function update($data=NULL, $where=''){
        $this->cache->clean('get_child_node');
        return parent::update($data, $where);
    }
    public function insert($data=NULL, $return_insert_id = false, $replace = false){
        $this->cache->clean('get_child_node');
        return parent::insert($data, $where);
    }
}

?>