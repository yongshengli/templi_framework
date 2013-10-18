<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * mysql数据库操作类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-1-19
 */
abstract class DB{
    public $prefix = '';        //表前缀
    public $table_name ='';     //表名
    protected $dbname='';     //数据库名
    protected $last_sql='';     //最后一次执行的sql
    protected $dbhost = '';
    protected $dbuser = '';
    protected $dbpsw = '';
    protected $charset = 'utf8';
    protected $pconnect = 0;
    /**
     * 构造函数 连接数据可
     */
    public function __construct($config){
        if(is_array($config)){
            foreach ($config as $key=>$val){
                if(isset($this->$key))
                    $this->$key = $val;
            }
        }
        $this->connect();
    }
    /**
     * 数据库连接
     */
    abstract public function connect();
     
    /**
     * 执行基本的 mysql查询 
     * 并返回结果集
     */
    abstract function query($sql);
    /**
     * 执行mysql instert update 语句 
     * 并返回影响行数
     */
    abstract public function execute($sql);
    /**
     * 获取最后一次添加数据的主键号
     */
    abstract public function insert_id();
    
    /**
     * 输出错误提示信息
     */
    protected function error_msg($message='',$sql=''){
        return $message.'<br/>SQL:'.$sql;
    }
}