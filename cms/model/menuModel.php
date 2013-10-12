<?php
defined('IN_TEMPLI') or die('无权访问');
class menuModel extends Model{
    function __construct(){
        parent::__construct('menu');
    }
    public function get_child_menu($parent_id=NUll, $reset_cache=false){
        $cacheid ='get_child_menu';
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
    public function get_menu($id=NUll, $reset_cache=false){
        $cacheid ='get_menu';
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