<?php
defined('IN_TEMPLI') or die('无权访问');
class locationModel extends Model{
    function __construct(){
        parent::__construct('location');
    }
    /**
     * 获取下级地区列表 根据父id
     */
    function get_area($parent_id=NULL){
        $cacheid ='get_area';
        if(!$area = $this->cache->get($cacheid)){
            $data = $this->select('','*','location_id asc');
            foreach($data as $val){
                $area[$val['parent_id']][$val['location_id']]=$val;
            }
            $this->cache->set($cacheid,$area);
        }
        return $parent_id==NULL?$area:$area[$parent_id];
    }
}
?>