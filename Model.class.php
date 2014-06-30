<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * 模型类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-3-20
 */
class Model
{
    /** @var string 当前表明 */
    public $table_name ='';

    /** @var  DB 当前数据库连接对象*/
    protected $db;

    /** @var  Cache 缓存对象*/
    private $cache = null;

    /** @var array 所有数据库 */
    private static $_dbs = [];

    private $_where = '';
    private $_field = '';
    private $_order = '';
    private $_limit = '';       
    private $_set   = array();  //insert update操作数据
    private $_page  = array();  //分页配置数组
    private $_page_html = '';    //分页 html 代码
    //sql 语句
    private $_last_sql;

    /**
     * 构造函数
     * @param string $table 表名
     * @param int|string $dbSign 数据库唯一标识
     * @param array $config 数据库配置
     */
    function __construct($table='', $dbSign='master', $config = array())
    {
        $this->db($dbSign, $config);
        require_once('Cache.class.php');
        $this->cache = Cache::factory();
        $table && $this->table_name = $this->db->prefix.$table;
        $this->rest_all_var();
    }

    /**
     * 获取当前模型 的配置信息
     * 访问数据的的配置信息
     * 数据库表前缀 表名等
     * @param $name
     * @return mixed
     */
    public function __get($name)
    {
        return isset($this->db->$name) ? $this->db->$name : NULL;
    }

    /**
     * 设置数据库连接 切换数据库
     * @param int|string $sign 数据库标识
     * @param array $config 数据库配置信息
     * @return $this
     */
    public function db($sign = 'master', $config = array())
    {
	    if (!isset(self::$_dbs[$sign])) {
            if(!$config){
                $config = Templi::get_config("db.{$sign}");
            }
            $className = ucfirst($config['dbdrive']);
            $dbdriveFile = 'Model/' . $className . '.class.php';
            require_once($dbdriveFile);
            self::$_dbs[$sign] = new $className($config);
        }
        if (isset(self::$_dbs[$sign])) {
            $this->db = self::$_dbs[$sign];
        } else {
            $this->db = self::$_dbs['master'];
        }
        return $this;
    }

    /**
     * 取数全部数据记录
     */
    public function fetch()
    {
        if (empty($this->_page)) {
            return $this->query($this->_select());
        } else {
            require_once('Page.class.php');
            $page = new Page($this->_page);
            if ($this->_page['listNum']) {
                $this->limit($this->_page['listNum'], $page->offset);
            }
            $arr['list'] = $this->query($this->_select());
            $arr['page_html'] = $page->page_html();
            $arr['total'] =  $this->_page['total'];
            return $arr;
        }
    }

    /**
     * 取一条数据记录
     *
     * @return array
     */
    public function fetchOne()
    {
        $result = $this->fetch();
        return isset($result[0]) ? $result[0] : [];
    }
    /**
     * 获取/设置缓存数据
     */
    public function fetchCache($cacheId=null) {
        if (is_null($cacheId)) {
            $cacheId = md5($this->_last_sql);
        }
        $result = $this->get($cacheId);
        if (is_null($cacheId) || empty($result)) {
            $result = &$this->fetch();
            $this->cache->set($cacheId, $result);
        }
        return $result;
    }

    /**
     * 获取一条 缓存结果
     *
     * @param string $cacheId
     * @return array
     */
    public function fetchCacheOne($cacheId=null)
    {
        $result = $this->fetchCache($cacheId);
        return isset($result[0]) ? $result[0] : [];
    }
    /**
     * 设置操作数据表
     * 此方法不建议使用 （使用 此方法 会改变 之前模型 的操作表）
     * @param string $table
     * @return $this
     */
    public function table($table)
    {
        $this->table_name = $this->db->prefix.$table;
        return $this;
    }
    /**
     * 学则字段字段
     * @param string $field
     *
     * @return $this
     */
    public function field($field)
    {
        //不选字段默认为 * 防止用户输入*
        if (trim($field) == '*') {
            return $this;
        }
        if (is_array($field)) {
            $fields = implode(',', array_map(array($this, 'add_special_char'), $field));
        } else {
            $fields = explode(',', $field);
            array_walk($fields, array($this, 'add_special_char'));
            $fields = implode(',', $fields); 
        }
        $this->_field && $this->_field .= ', ';
        $this->_field .= $fields;
        return $this;
    }

    /**
     * where 条件
     * @param array|string $where
     *
     * @param string $compare
     * @return $this
     */
    public function where($where, $compare = '=')
    {
        $this->sqls($where, 'AND', $compare);
        return $this;
    }

    /**
     * where 条件
     * @param array|string $where
     * @param string $compare
     * @return $this
     */
    public function where_or($where, $compare = '=')
    {
        $this->sqls($where, 'OR', $compare = '=');
        return $this;
    }

    /**
     * 给 update insert 赋值
     * @param array $data
     * @throws Abnormal
     * @return $this
     */
    public function set(array $data)
    {
        $this->_set = array_merge($this->_set, $data);
        return $this;
    }

    /**
     * oder by
     * @param string $order
     *
     * example id desc
     * @return $this
     */
    public function order($order)
    {
        if (is_array($order)) {
            foreach ($order as $key =>$val){
                $this->_order && $this->_order .= ', ';
                $this->_order .= $this->add_special_char($key).' '.$val;
            }
        } else {
            $this->_order && $this->_order .= ', ';
            $this->_order .= $order;
        }
        return $this;
    }

    /**
     * limit
     * $limit example 0,20* example 0,20
     *
     * @param $listNum
     * @param null $offset
     * @return $this
     */
    public function limit($listNum, $offset = NULL)
    {
        if($offset != NULL){
            $this->_limit = " $offset,$listNum";
        }else{
            $this->_limit = $listNum;
        }
        return $this;
    }

    /**
     * 分页设置
     * @param array $page
     * $page['total'] 总数
     * $page['listNum'] 每页显示条数
     * $page['current_page'] 当前页
     * $page['pageNum'] 每页显示的 页码数
     * $page['urlrule'] 分页 url 规则
     * $page['maxpage'] 最大页数
     * @return $this
     */
    public function page($page)
    {
        if (is_array($page)) {
            foreach ($this->_page as $key =>$val) {
                if ($page[$key] !== NULL) {
                    $this->_page[$key] = $page[$key];
                }
            }
        } else {
            $this->_page['current_page'] = $page;
        }
        $this->_page['total'] = $this->count($this->_where);
        return $this;
    }

    /**
     * 多条查询并分页
     * @param null $where 条件语句 可以为数组
     * @param string $field 字段
     * @param string $order 排序
     * @param int $current_page 当前页
     * @param int $listNum 每页显示条数
     * @param int $pageNum 每页显示的 页码数
     * @param string $urlrule url 规则
     * @param int $maxpage 最多显示页数
     *
     * @return array
     */
    public function getlist(
        $where=NULL, $field=NULL, $order=NULL, $current_page=NULL,
        $listNum=NULL, $pageNum=NULL, $urlrule=NULL, $maxpage=NULL
    ) {
        $arr = array();
        $field && $this->field($field);
        $where && $this->where($where);
        $order && $this->order($order);
        
        
        //分页
        $current_page && $this->page(array(
            'current_page'  =>  $current_page,
            'listNum'       =>  $listNum,
            'pageNum'       =>  $pageNum,
            'urlrule'       =>  $urlrule,
            'maxpage'       =>  $maxpage
        ));

        return $this;
    }

    /**
     * 多条查询
     * @param array $where 条件语句 可以为数组
     * @param string $field 字段
     * @param string $order 排序
     * @param string $limit 条数限制
     *
     * @return $this
     */
    public function select($where=NULL, $field=NULL, $order=NULL, $limit=NULL)
    {
        
        $where && $this->where($where);
        $field && $this->field($field);
        $order && $this->order($order);
        $limit && $this->limit($limit);

        return $this;
    }

    /**
     * 生成 select sql 语句
     */
    private function _select()
    {
        $sql  = 'SELECT '.$this->_field.' FROM `'.$this->table_name.'`';
        $sql .= $this->_where?' WHERE '.$this->_where:'';
        $sql .= $this->_order?' ORDER BY '.$this->_order:'';
        $sql .= $this->_limit;
        return $sql;
    }
    /**
     * 单条查询
     * @param null $where 语句 可以是数组
     * @param null $field 要查找的字段
     * @param null $order 排序
     *
     * @return array()
     */
    public function find($where=NULL, $field=NULL, $order=NULL)
    {
        $list = $this->select($where, $field, $order, $limit=1);
        return isset($list[0])?$list[0]:array();
    }
    /**
     * 查询数据条数
     * @param array|string 查询条件 可以是 数组
     */
    public function count($where = NULL)
    {
        $where && $this->where($where);
        
        $sql = 'SELECT COUNT(*) AS `num` FROM '.$this->table_name;
        $sql .= $where?' where '.$this->_where:'';
        $sql .=' limit 1';
        $res = $this->query($sql);
        return isset($res[0]['num'])?$res[0]['num']:$res;
    }

    /**
     * 修改数据
     * @param mixed $data 要修改的数据 字符串为 sql 语句 数组key 为字段名 value 为字段值
     * @param mixed $where 条件语句 可为数组
     *
     * @return bool
     */
    public function update($data = NULL, $where=NULL)
    {
        $data && $this->set($data);
        $where && $this->where($where);
        
        if(!$this->_where) {
             return false;
        }
        foreach($this->_set as $k => $v){
            switch(substr($v, 0, 2)){
                case '+=':
                    $v= substr($v,2);
                    if(is_numeric($v)){
                        $fields[] =$this->add_special_char($k).'='.$this->add_special_char($k).'+'.$this->escape_string($v, false);
                    }else{
                        continue;
                    }
                    break;
                case '-=':
                    $v= substr($v,2);
                    if(is_numeric($v)){
                        $fields[] =$this->add_special_char($k).'='.$this->add_special_char($k).'-'.$this->escape_string($v, false);

                    }else{
                        continue;
                    }
                    break;
                default:
                    $fields[]=$this->add_special_char($k).'='.$this->escape_string($v );
                    break;
            }
        }
        $field = implode(',', $fields);
        
        $sql ='UPDATE `'.$this->table_name.'` SET '.$field.' WHERE '.$this->_where;
        return $this->query($sql);
    }

    /**
     * 插入数据
     * @param array $data 要添加的数据 key 为字段名 value 为字段值
     * @param bool $return_insert_id 是否返回主键 号
     * @param bool $replace 是否 为替换插入
     * @return bool
     */
    public function insert($data = NULL, $return_insert_id = false, $replace = false)
    {
        $data && $this->set($data);
        
        if(!is_array($this->_set) || count($this->_set) == 0){
            return false;
        } 
        $fields = array_keys($this->_set);
        $values = array_values($this->_set);
        $fields = implode(',',array_map(array($this, 'add_special_char'), $fields));
        $values = implode(',',array_map(array($this, 'escape_string'), $values));

        $sql  = $replace?'REPLACE INTO ':'INSERT INTO ';
        $sql .= $this->table_name. '('.$fields.') VALUES ('.$values.')';
        $result = $this->query($sql);
        return $return_insert_id?$this->db->insert_id():$result;
    }
    /**
      * 删除数据 
      * @param string|array $where 条件
      * @return bool
      */
    public function delete($where=NULL)
    {
        $where && $this->where($where);
        if(!$this->_where) 
            return false;
        $sql = 'DELETE FROM ' .$this->table_name. ' WHERE '.$this->_where;
        return $this->query($sql);
    }
    /**
     * 执行基本的 mysql查询
     */
    public function query($sql)
    {
        $this->_last_sql = trim($sql);
        $this->rest_all_var();
        if (strtoupper(substr($this->_last_sql, 0, 6)) == 'SELECT') {
            return $this->db->query($this->_last_sql);
        } else {
            return $this->db->execute($this->_last_sql);
        }
    }

    /**
     * 对字段两边加反引号，以保证数据库安全
     * @param string $value 数组值
     * @return string
     */
    public function add_special_char(&$value)
    {
        if('*' == $value || false !== strpos($value, '(') || false !== strpos($value, '.') || false !== strpos ( $value, '`')) {
            //不处理包含* 或者 使用了sql方法。
        } else {
            $value = '`'.trim($value).'`';
        }
        return $value;
    }

    /**
     * 对字段值两边加引号，以保证数据库安全
     * @param string $value 数组值
     * @param $quotation
     * @return string
     */
    public function escape_string(&$value, $quotation = 1)
    {
        if ($quotation) {
                $q = '\'';
        } else {
                $q = '';
        }
        $value = $q.addslashes($value).$q;
        return $value;
    }
    /**
     * 获取最后一次执行的sql 语句
     */
    public function last_sql(){
        return $this->_last_sql;
    }

    /**
     * 将数组转换为SQL语句
     * @param array $where 要生成的数组
     * @param string $font 连接串
     * @param string $compare 比较字符 (=,!=,in not in, like)
     * @throws Abnormal
     */
    final public function sqls($where, $font = ' AND ', $compare = '=')
    {
        if (is_array($where)) {
            $compare = strtoupper(trim($compare));
            $allowed = array('=', '>=', '<=', '>', '<', '<>', '!=', 'LIKE');
            if (!in_array($compare ,$allowed)) {
                throw new Abnormal('不支持的比较操作符'.$compare, 500);
            }
            foreach ($where as $key=>$val) {
                $this->_where && $this->_where .= ' '.$font ;
                $this->_where .= ' '.$this->add_special_char($key). $compare .$this->escape_string($val, true);
            }
        } else {
            $this->_where && $this->_where .= ' '.$font;
            $this->_where .= ' '.$where;
            
        }
    }
    /**
     * 重置 类属性
     */
    private function rest_all_var()
    {
        $this->_where = '';
        $this->_field = '*';
        $this->_order = '';
        $this->_limit = '';
        $this->_set   = array();
        $this->_page  = array(
            'total'=>0,
            'current_page'=>1, 
            'pageNum'=>8, 
            'listNum'=>20, 
            'urlrule'=>'',
            'maxpage'=>0
            );
        $this->_page_html = '';
    }
}