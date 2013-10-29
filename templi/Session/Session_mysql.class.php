<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 *  session mysql 数据库存储类
 * 
 */
class Session_mysql {
	var $lifetime = 1800;
	var $model;
	var $table;
    /**
     * 构造函数
     * 
     */
    public function __construct() {
        $this->model = Templi::model('sessions');
        $this->lifetime = Templi::get_config('session_lifetime');
    	session_set_save_handler(array(&$this,'open'), array(&$this,'close'), array(&$this,'read'), array(&$this,'write'), array(&$this,'destroy'), array(&$this,'gc'));
    	session_start();
    }
    /**
     * session_set_save_handler  open方法
     * @param $save_path
     * @param $session_name
     * @return true
     */
    public function open($save_path, $session_name) {
		
        return true;
    }
    /**
     * session_set_save_handler  close方法
     * @return bool
     */
    public function close() {
        return $this->gc($this->lifetime);
    } 
    /**
     * 读取session_id
     * session_set_save_handler  read方法
     * @return string 读取session_id
     */
    public function read($id) {
        $r = $this->model->where(array('session_id'=>$id))->find();
        return $r ? $r['user_data'] : '';
    } 
    /**
     * 写入session_id 的值
     * 
     * @param $id session
     * @param $data 值
     * @return mixed query 执行结果
     */
    public function write($id, $data) {
        $ip = getip();
        $sessiondata = array(
                        'session_id'=>$id,
                        'ip_address'=>$ip,
                        'last_activity'=>SYS_TIME,
                        'user_data'=>$data,
                    );
        return $this->model->insert($sessiondata, 1,1);
    }
    /** 
     * 删除指定的session_id
     * 
     * @param $id session
     * @return bool
     */
    public function destroy($id) {
        return $this->model->delete(array('session_id'=>$id));
    }
    /**
     * 删除过期的 session
     * 
     * @param $maxlifetime 存活期时间
     * @return bool
     */
   public function gc($maxlifetime) {
        $expiretime = SYS_TIME - $maxlifetime;
        return $this->model->delete("`lastvisit`<$expiretime");
    }
}
?>