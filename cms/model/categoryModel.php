<?php
defined('IN_TEMPLI') or die('无权访问');
class categoryModel extends Model{
    function __construct(){
        parent::__construct('category');
    }
    public function get_child_category($parent_id=NUll, $reset_cache=false){
        $cacheid ='get_child_category';
        $list = $this->cache->get($cacheid);
        //print_r($list);
        if($reset_cache || !$list){
            $result =$this->select(array('display'=>1), '*','listorder desc');
            foreach($result as $v){
                $list[$v['parent_id']][$v['id']]=$v;
            }
            $this->cache->set($cacheid, $list);
            //echo 'ffff';
        }
        return is_null($parent_id)?$list:$list[$parent_id];
    }
    public function get_category($id=NUll, $reset_cache=false){
        $cacheid ='get_category';
        $list = $this->cache->get($cacheid);
        //print_r($list);
        if($reset_cache || !$list){
            $result =$this->select(array('display'=>1), '*','listorder desc');
            foreach($result as $v){
                $list[$v['id']]=$v;
            }
            $this->cache->set($cacheid, $list);
        }
        return is_null($id)?$list:$list[$id];
    }
}
?>