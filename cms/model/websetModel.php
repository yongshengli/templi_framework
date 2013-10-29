<?php
defined('IN_TEMPLI') or die('无权访问');
class websetModel extends Model{
    function __construct(){
        parent::__construct('webset');
    }
    /**
     * 获取网站配置
     */
    public function get_webset($field=NUll, $reset_cache=false){
        $cacheid = 'get_webset';
        $list = $this->cache->get($cacheid);
        if($reset_cache || !$list){
            $result = $this->select();
            foreach($result as $k=>$v){
                $list[$v['name']] =$v;
            }
            $this->cache->set($cacheid, $list);
        }
        return $field?$list[$field]:$list;
    }
    public function update($data=NULL, $where=''){
        $this->cache->clean('get_webset');
        return parent::update($data, $where);
    }
    public function insert($data=NULL,$return_insert_id = false, $replace = false){
        $this->cache->clean('get_webset');
        return parent::insert($data, $where);
    }
}
?>