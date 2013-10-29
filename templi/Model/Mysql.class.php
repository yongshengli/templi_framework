<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * mysql数据库操作 驱动类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-1-19
 */
require_once 'DB.class.php';
class Mysql extends DB{
    private $linkid = NULL;     //连接id
     /**
      * 连接数据库
      */
    public function connect(){
        if(isset($pconnect)){
            $this->linkid = @mysql_pconnect($this->dbhost, $this->dbuser, $this->dbpsw, 1);
        }else{
            $this->linkid = @mysql_connect($this->dbhost, $this->dbuser, $this->dbpsw, 1);
        }
        if(!$this->linkid && APP_DEBUG){
            throw new Abnormal($this->error_msg('Can not link to mysql'),5,true); 
        }
        if($this->dbname){
            mysql_select_db($this->dbname,$this->linkid) or $this->error_msg('Can not use db '.$dbname);
	    $this->execute(' set names '.$this->charset);
        }elseif(APP_DEBUG){
            throw new Abnormal($this->error_msg('It not gave dbname'),5,true);
        }
    }
    /**
     * 执行基本的 mysql查询
     */
    function query($sql){
        if(!$sql){
            return false;
        }else{
            if(!$this->linkid && APP_DEBUG){
                throw new Abnormal($this->error_msg('未连接数据库'), 5, true);
                return false;
            }
            $this->lastqueryid = mysql_query($sql,$this->linkid);
            if($this->lastqueryid === false && APP_DEBUG){
                throw new Abnormal($this->error_msg($this->error(),$sql),5, true);
                return false;
            }else{
                return $this->fetch_array($this->lastqueryid);
            }
        }
    }
    /**
     * 执行mysql 语句
     */
    public function execute($sql){
        if(!$sql){
            return false;
        }
        if(!$this->linkid && APP_DEBUG){
            throw new Abnormal($this->error_msg('未连接数据库'), 5, true);
            return false;
        }
        $this->lastqueryid = mysql_query($sql,$this->linkid);
        if($this->lastqueryid === false && APP_DEBUG){
            throw new Abnormal($this->error_msg($this->error(), $sql), 5, true);
            return false;
        }else{
            return $this->affected_rows();
        }
    }
    /**
     * 将mysql查询结果转化为数组
     */
    function fetch_array($handler){
        $list=array();
        while($row =mysql_fetch_assoc($handler)){
            $list[] =$row;
        }
        $this->free_reslut();
        return $list;
    }
    /**
     * 获取最后一次添加数据的主键号
     */
    function insert_id(){
        return mysql_insert_id($this->linkid);
    }
    /**
     * 释放查询资源
     */
    function free_reslut(){
        mysql_free_result($this->lastqueryid);
        $this->lastqueryid=NULL;
        return true;
    }
    /**
     * 获取mysql产生的文本错误信息
     */
    function error(){
        return mysql_error($this->linkid);
    }
    /**
     * 返回mysql 操作中的文本错误编码
     */
    function erron(){
        return mysql_errno($this->linkid);
    }
    /**
     * 返回最后一次操作数据库影响的条数
     */
    function affected_rows(){
        return mysql_affected_rows($this->linkid);
    }  
}
?>
