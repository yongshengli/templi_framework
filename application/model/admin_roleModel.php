<?php
defined('IN_TEMPLI') or die('无权访问');
class admin_roleModel extends Model{
    function __construct(){
        parent::__construct('admin_role');
    }
    /**
     * 获得角色信息 有缓存
     * @param int $role_id 角色 id
     * @param bool $rest_cache 是否重新设置缓存
     * @return array
     */
    public function get_role($role_id=NULL, $rest_cache=false){
        $cacheid = 'get_role';
        $list =$this->cache->get($cacheid);
        if($rest_cache || !$list){
            $result = $this->select();
            foreach($result as $val){
                $list[$val['role_id']] =$val;
            }
            $this->cache->set($cacheid, $list);
        }
        return is_null($role_id)?$list:$list[$role_id];
    }
    public function update($data, $where=''){
        $this->cache->clean('get_role');
        return parent::update($data, $where);
    }
}
?>