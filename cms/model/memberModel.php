<?php
defined('IN_TEMPLI') or die('无权访问');
class memberModel extends Model{
    function __construct(){
        parent::__construct('member');
        $this->M_member_info =Templi::model('member_info');
        $this->M_member_data =Templi::model('member_data');
        Templi::include_common_file('Formcheck.class.php');
        Templi::include_common_file('String.class.php');
    }
    /**
     * 根据用户名、用户id、email 获取用户信息
     * @param mid $username 
     */
    public function get_member_base($username){
        if(Formcheck::isnumber($username)){
            $where = " `userid` ='{$username}'";
        }elseif(Formcheck::isemail($username)){
            $where = " `email` = '{$username}'";
        }else{
            $where = " `username` = '{$username}'";
        }
        return $this->find($where);
    }
    /**
     * 根据用户ID 获取用户资料
     */
    public function get_member_info($userid){
        return $this->M_member_info->find(array('userid'=>$userid));
    }
    /**
     * 更新用户登录信息
     */
    public function update_user_login($userid){
        $data = array();
        $data['last_logintime'] = SYS_TIME;
        $data['last_loginip']   = getip();
        $data['login_num']      = '+=1';
        $this->M_member_data->update($data,array('userid'=>$userid));
    }
    /**
     * 根据用户ID 获取用户 数据
     */
    public function get_member_data($userid){
        return $this->M_member_data->find(array('userid'=>$userid));
    }
    /**
     * 根据用户组ID 获取用户组信息
     */
    public function get_group_info($group_id){
        return Templi::model('member_group')->find(array('group_id'=>$group_id));
    }
    /**
     * 根据用户组ID 获取用户组权限
     */
    public function get_group_access($group_id){
        return Templi::model('member_group_access')->select(array('group_id'=>$group_id));
    }
    /**
     * 添加用户
     * @param array $info  用户数据
     */
    public function register_member(&$info){
        $userid = $this->insert($info,true);
        if($userid){
            $res_info = $this->M_member_info->insert(array('userid'=>$userid,'username'=>$info['username']));
            $res_data = $this->M_member_data->insert(array('userid'=>$userid));    
            if($res_info && $res_data){
                return true;
            }else{
                $this->delete_member($userid);
                return false;
            }
        }else{
            return false;
        }
        
    }
    /**
     * 根据用户id 删除用户
     * @param int $userid 用户id 
     */
    public function delete_member($userid){
        $this->M_member_info->delete(array('userid'=>$userid));
        $this->M_member_data->delete(array('userid'=>$userid));
        if($this->delete(array('userid'=>$userid)))
            return true;
        else
            return false;
    }
}
?>