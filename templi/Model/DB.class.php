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
    protected $database='';       //数据库名
    protected $last_sql='';     //最后一次执行的sql
    /**
     * 构造函数 连接数据可
     */
    public function __construct($dbhost, $dbuser, $dbpsw, $dbname, $charset='utf8', $pconnect=0){
        $this->connect($dbhost, $dbuser, $dbpsw, $dbname, $charset='utf8', $pconnect=0);
    }
    /**
     * 数据库连接
     */
    abstract public function connect($dbhost, $dbuser, $dbpsw, $dbname, $charset='utf8', $pconnect=0);
     /**
     * 多条查询
     * @param string or array $where 条件语句 可以为数组 
     * @param string $table 表名
     * @param string $field 字段
     * @param string $oreder 排序
     * @param string $limit 条数限制
     */
    abstract public function select($where='', $table, $field='*', $order='', $limit='20');
    
    /**
     * 查询数据条数
     * @param string $table 表名
     * @param array or string 查询条件 可以是 数组 
     */
    abstract public function count($table, $where='');
    
    /**
     * 修改数据
     * @param array or string $data要修改的数据 字符串为 sql 语句 数组key 为字段名 value 为字段值
     * @param string  $table 表名
     * @param array or string $where 条件语句 可为数组
     */
    abstract function update($data, $table, $where='');
    /**
      * 插入数据
      * @param array $data 要添加的数据 key 为字段名 value 为字段值
      * @param string $table 表名
      * @param bool $return_insert_id 是否返回主键 号
      * @param bool $replace 是否 为替换插入
      */
    abstract function insert($data, $table, $return_insert_id=false, $replace=false);
    /**
      * 删除数据 
      * @param string $table 表名
      * @param string or array $where 条件
      * @return bool
      */
    abstract function delete($table,$where='');
    /**
     * 执行基本的 mysql查询
     */
    abstract function query($sql);
    /**
     * 执行mysql 语句
     */
    abstract public function execute($sql);
    /**
     * 获取最后一次添加数据的主键号
     */
    abstract public function insert_id();
    /**
     * 返回最后一次操作数据库影响的条数
     */
    abstract public function affected_rows();
    /**
	 * 对字段两边加反引号，以保证数据库安全
	 * @param $value 数组值
	 */
	public function add_special_char(&$value) {
		if('*' == $value || false !== strpos($value, '(') || false !== strpos($value, '.') || false !== strpos ( $value, '`')) {
			//不处理包含* 或者 使用了sql方法。
		} else {
			$value = '`'.trim($value).'`';
		}
		return $value;
	}
    /**
	 * 对字段值两边加引号，以保证数据库安全
	 * @param $value 数组值
	 * @param $key 数组key
	 * @param $quotation 
	 */
	public function escape_string(&$value, $key='', $quotation = 1) {
		if ($quotation) {
			$q = '\'';
		} else {
			$q = '';
		}
		$value = $q.addslashes($value).$q;
		return $value;
	}
    public function last_sql(){
        return $this->last_sql;
    }
    /**
	 * 将数组转换为SQL语句
	 * @param array $where 要生成的数组
	 * @param string $font 连接串。
	 */
	final public function sqls($where, $font = ' AND ') {
		if (is_array($where)) {
			$sql = '';
			foreach ($where as $key=>$val) {
				$sql .= $sql ? " $font (`$key` = '$val' )" : " `$key` = '$val'";
			}
			return $sql;
		} else {
			return $where;
		}
	}
    /**
     * 输出错误提示信息
     */
    private function error_msg($message='',$sql=''){
        return $message.'<br/>SQL:'.$sql;
    }
}