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
    private $affected_rows = NULL; //影响行数
    
    /**
     * 连接数据库
     */
    public function connect(){
        if(is_null($this->pdo)){
            try {
                $this->pdo = new PDO("mysql:host={$this->dbhost};dbname={$this->dbname}", $this->dbuser, $this->dbpsw);
                $this->execute('set names '.$this->charset);
            }catch(PDOException $e){
                //print_r($e);
                $message = $e->getMessage();
                $encod   = mb_detect_encoding($message,array('ASCII','GB2312','GBK', 'utf-8', 'iso-8859-1', 'windows-1251'));
                if($encod!='UTF-8'){
                    $message = iconv($encod, 'UTF-8', $message);
                }
                if(APP_DEBUG){
                    throw new Abnormal($message, $e->getCode(), true);
                }
            }
        }
    }
     public function query($sql){
        if(!$sql) return false;
        
        $this->lastqueryid = $this->pdo->query($sql);
        if($this->lastqueryid===FALSE && APP_DEBUG){
           $err = $this->pdo->errorInfo();
           throw new Abnormal($this->error_msg($err[2], $sql),5);
        }
        return $this->lastqueryid->fetchAll(PDO::FETCH_ASSOC);
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
        
        $result = $this->pdo->exec($sql);
        if ($result === false) {
            $err = $this->pdo->errorInfo();
            throw new Abnormal($this->error_msg($err[2],$sql),5);
        }
        return $result;
     }
     /**
      * 释放资源
      * @return boolean
      */
     public function free_reslut(){
         $this->lastqueryid=NULL;
         return true;
     }
     /**
      * 返回影响行数
      * @return type
      */
     public function affected_rows(){
         return $this->affected_rows;
     }
}
?>
