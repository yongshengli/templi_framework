<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * mysql pdo数据库操作类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-1-19
 */
require_once 'DB.class.php';
class Pdo_Mysql extends DB{
    private $pdo = NULL;        //pdo对象
    private $affected_rows = NULL;
    
    /**
     * 连接数据库
     */
    public function connect($dbhost, $dbuser, $dbpsw, $dbname, $charset='utf8', $pconnect=0){
        $this->database = $dbname;
        if(is_null($this->pdo)){
            try {
                $this->pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpsw);
                $this->execute('set names '.$charset);
            }catch(PDOException $e){
                $message = $e->getMessage();
                $encod   = mb_detect_encoding($message,array('ASCII','GB2312','GBK', 'utf-8', 'iso-8859-1', 'windows-1251'));
                if($encod!='UTF-8'){
                    $message = iconv($encod,'UTF-8',$message);
                }
                if(APP_DEBUG)
                    throw new Abnormal($message,$e->getCode(),true);
            }
        }
    }
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
    public function query($sql){
        if(!$sql)return false;
        //$this->pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
        return $this->execute($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * 获取最后一次添加数据的主键号
     */
    public function insert_id(){
        return $this->pdo->lastInsertId();
    }
     /**
     * 执行mysql 语句
     */
    public function execute($sql){
        //die($sql);
        $this->last_sql = $sql;
        $this->lastqueryid = $this->pdo->prepare($sql);
        if($this->lastqueryid===FALSE && APP_DEBUG){
           $err = $this->pdo->errorInfo();
           throw new Abnormal($this->error_msg($err[3],$sql),5,true);
        }
        $this->lastqueryid->execute();
        $this->affected_rows =$this->lastqueryid->rowCount();
        return $this->lastqueryid;
    }
    public function free_reslut(){
        $this->lastqueryid=NULL;
        return true;
    }
    public function affected_rows(){
        return $this->affected_rows;
    }
}
?>
