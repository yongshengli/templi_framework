<?php
defined('IN_TEMPLI') or die('无权访问');
class admin_accessModel extends Model{
    function __construct(){
        parent::__construct('admin_access');
    }
    /**
     * 检查某用户组是否有 访问权限
     */
    public function cheack_access($role_id, $module='', $controller='', $action=''){
        $where1 =" role_id ={$role_id} ";
        $where2 = $module?" and module='{$module}'":'';
        $where2 .= $controller?" and controller='{$controller}'":'';
        $where2 .= $action?" and action='{$action}'":'';
        $M_node = Templi::model('admin_node');
        if(!$M_node->find('1 '.$where2)){
            return true;
        }elseif($this->find($where1.$where2)){
            return true;
        }
        return false;
    }
}
?>