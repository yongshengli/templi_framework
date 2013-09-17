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
     * 多条查询
     * @param string or array $where 条件语句 可以为数组 
     * @param string $table 表名
     * @param string $field 字段
     * @param string $oreder 排序
     * @param string $limit 条数限制
     */
    function select($where='', $table, $field='*', $order='', $limit='20'){
        $field = explode(',', $field);
        array_walk($field, array($this, 'add_special_char'));
        $field = implode(',', $field);
        $sql ='select '.$field.' from `'.$this->database.'`.`'.$table.'`';
        $sql .=$where?' where '.$this->sqls($where):'';
        $sql .=$order?' order by '.$order:'';
        $sql .=$limit?' limit '.$limit:'';
        return $this->query($sql);
    }
    /**
     * 查询数据条数
     * @param string $table 表名
     * @param array or string 查询条件 可以是 数组 
     */
    function count($table, $where=''){
        if(!$table)return false;
        $sql = 'select count(*) as `num` from `'.$this->database.'`.`'.$table.'`';
        $sql .=$where?' where '.$this->sqls($where):'';
        $sql .=' limit 1';
        $result =$this->query($sql);
        return $result[0]['num'];
    }
    /**
     * 修改数据
     * @param array or string $data要修改的数据 字符串为 sql 语句 数组key 为字段名 value 为字段值
     * @param string  $table 表名
     * @param array or string $where 条件语句 可为数组
     */
     function update($data, $table, $where=''){
        if(!$where)
             return false;
        if(is_array($data) && count($data)>0){
           foreach($data as $k=>$v){
                switch(substr($v,0,2)){
                    case '+=':
                        $v= substr($v,2);
                        if(is_numeric($v)){
                            $fields[] =$this->add_special_char($k).'='.$this->add_special_char($k).'+'.$this->escape_string($v,'',false);
                            
                        }else{
                            continue;
                        }
                        break;
                    case '-=':
                        $v= substr($v,2);
                        if(is_numeric($v)){
                            $fields[] =$this->add_special_char($k).'='.$this->add_special_char($k).'-'.$this->escape_string($v,'',false);
                            
                        }else{
                            continue;
                        }
                        break;
                    default:
                        $fields[]=$this->add_special_char($k).'='.$this->escape_string($v);
                        break;
                }
           }
           $field = implode(',',$fields);
        }elseif(is_string($data) && $data!=''){
            $field =$data;
        }
        $where =' where '.$this->sqls($where);
        $sql ='update `'.$this->database.'`.`'.$table.'` set '.$field.$where;
        return $this->execute($sql);
     }
     /**
      * 插入数据
      * @param array $data 要添加的数据 key 为字段名 value 为字段值
      * @param string $table 表名
      * @param bool $return_insert_id 是否返回主键 号
      * @param bool $replace 是否 为替换插入
      */
     function insert($data, $table, $return_insert_id=false, $replace=false){
        if(!is_array($data) || !$table || count($data)==0){
            return false;
        } 
        $fields=array_keys($data);
        $values=array_values($data);
        array_walk($fields,array($this, 'add_special_char'));
        array_walk($values,array($this, 'escape_string'));
        $fields = implode(',',$fields);
        $values = implode(',',$values);
        $sql =$replace?'replace into ':'insert into ';
        $sql .= '`'.$this->database.'`.`'.$table.'`('.$fields.') values ('.$values.')';
        $return =$this->execute($sql);
        return $return_insert_id?$this->insert_id():$return;
     }
     /**
      * 删除数据 
      * @param string $table 表名
      * @param string or array $where 条件
      * @return bool
      */
     function delete($table,$where=''){
        if(!$where) return false;
        $sql='delete from `'.$this->database.'`.`'.$table.'` where '.$this->sqls($where);
        return $this->execute($sql);
     }
     /**
      * 连接数据库
      */
    private function connect($dbhost, $dbuser, $dbpsw, $dbname, $charset='utf8', $pconnect=0){
        if($pconnect){
            $this->linkid = @mysql_pconnect($dbhost, $dbuser, $dbpsw, 1);
        }else{
            $this->linkid = @mysql_connect($dbhost, $dbuser, $dbpsw, 1);
        }
        if(!$this->linkid && APP_DEBUG){
            throw new Abnormal($this->error_msg('Can not link to mysql'),5,true); 
        }
        if($dbname){
            $this->database = $dbname;
            mysql_select_db($dbname,$this->linkid) or $this->error_msg('Can not use db '.$dbname);
	        $this->execute(' set names '.$charset);
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
            return $this->fetch_array($this->execute($sql)); 
        }
    }
    /**
     * 执行mysql 语句
     */
    private function execute($sql){
        if(!$this->linkid && APP_DEBUG){
            throw new Abnormal($this->error_msg('未连接数据库'), 5, true);
        }
        $this->last_sql = $sql;
        $this->lastqueryid= mysql_query($sql,$this->linkid);
        if($this->lastqueryid===false && APP_DEBUG){
            throw new Abnormal($this->error_msg($this->error(),$sql),5,true);
            return false;
        }else{
            return $this->lastqueryid;
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
        $this->lastqueryid=NULL;
        return mysql_free_result($this->lastqueryid);
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
