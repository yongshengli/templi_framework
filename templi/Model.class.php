<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * 模型类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-3-20
 */
class Model{
    //表前缀
    public $prefix = ''; 
    //表名       
    public $table_name ='';
    //数据库连接对象
    protected $db; 
    //数据库驱动   
    protected $dbdrive;
    //缓存类
    public $cache=null;
    
    private $_where = '';
    private $_field = '*';
    private $_order = '';
    private $_limit = '';
    private $_set   = '';
    private $_page  =array('current_page'=>1,'pageNum'=>8,'urlrule'=>'','maxpage'=>0);
    /**
     * 构造函数
     * @param string $table 表名
     * @param int $dbSign 数据库唯一标识
     * @param array $db 数据库配置
     */
    function __construct($table='', $dbSign=0, $db=array()){
        $this->prefix = templi::get_config('prefix');
        $this->db($dbSign,$db);
        if($table){
            $this->table_name =$this->prefix.$table;
        }
    }
    /**
     * 设置数据库连接 切换数据库
     * @param init $sign 数据库标识
     * @param array $config 数据库配置信息  
     */
    public function db($sign= 0,$config = array()){
	static $db = array();
        Templi::include_common_file('Cache.class.php');
        $this->cache = Cache::factory();
	if(!$db[$sign]){
            if(!$congfig){
                $config =Templi::get_config($config);
                $this->dbdrive = $config['dbdrive']?$config['dbdrive']:templi::get_config('dbdrive');
            }
            require_once('Model/'.ucfirst($this->dbdrive).'.class.php');
            $db[$sign] = new $this->dbdrive($config['dbhost'], $config['dbuser'], $config['dbpsw'], $config['dbname'],$config['charset'],$config['pconnect']);
        }
	$this->db = &$db[$sign];
        return $this;
    }
    /**
     * 设置操作数据表
     * 此方法不建议使用 （使用 此方法 会改变 之前模型 的操作表）
     * @param string $table
     */
    public function table($table){
        $this->table_name = $table;
        return $this;
    }
    /**
     * 学则字段字段
     * @param string $field
     */
    public function field($field){
        $this->_field =$field;
        return $this;
    }
    /**
     * where 条件
     * @param array or string $where 
     */
    public function where($where){
        $this->_where=$where;
        return $this;
    }
    /**
     * 给 update insert 赋值
     * @param string  array $data
     */
    public function set($data){
        $this->_set=$data;
        return $this;
    }
    /**
     * oder by
     * @param string $order 
     * 
     * example id desc
     */
    public function order($order){
        $this->_order =$order;
        return $this;
    }
    /**
     * limit 
     * @param string $limit 
     * example 0,20
     */
    public function limit($listNum,$offset=NULL){
        if($offset!=NULL){
            $this->_limit= " $offset,$listNum";
        }else{
            $this->_limit =$limit;
        }
        return $this;
    }
    /**
     * 分页设置
     * @param array $page
     * current_page 当前页
     * pageNum 每页显示的 页码数
     * urlrule 分页 url 规则
     * maxpage 最大页数
     */
    public function page($page){
        $this->_page = array_merge($this->_page,$page);
        return $this;
    }
    /**
     * 多条查询并分页
     * @param string or array $where 条件语句 可以为数组 
     * @param string $field 字段
     * @param string $oreder 排序
     * @param string $limit 条数限制
     * @param int $current_page 当前页
     * @param int $listNum 每页显示条数 
     * @param int $pageNum 每页显示的 页码数
     * @param string $urlrule url 规则
     * @param $maxpage 最多显示页数
     * @param array $arr[list] 数据列表 $arr['page_html'] 页码html
     */
    public function getlist($where=NULL, $field=NULL, $order=NULL, $current_page=NULL, $listNum=NULL, $pageNum=NULL, $urlrule=NULL, $maxpage=NULL){
        $arr = array();
        $total = $this->count($where);
        Templi::include_common_file('Page.class.php');
        $page_config =array(
                'total'=>$total,
                'pageNum'=>$pageNum?$pageNum:$this->_page['pageNum'],
                'listNum'=>$listNum?$listNum:($this->_limit?$this->_limit:20),
                'current_page'=>$current_page?$current_page:$this->_page['current_page'],
                'urlrule'=>$urlrule?$urlrule:$this->_page['urlrule'],
                'maxpage'=>$maxpage?$maxpage:$this->_page['maxpage']
            );
        $page =new Page($page_config);
        $arr['page_html'] = $page->page_html();
        $arr['list'] = $this->select($where, $field, $order, "{$page->offset},$listNum");
        $arr['total'] = $total;
        return $arr;
    }
    /**
     * 多条查询
     * @param string or array $where 条件语句 可以为数组 
     * @param string $field 字段
     * @param string $oreder 排序
     * @param string $limit 条数限制
     */
    public function select($where=NULL, $field=NULL, $order=NULL, $limit=NULL){
        
        $where && $this->_where = $where;
        $field && $this->_field = $field;
        $order && $this->_order = $order;
        $limit && $this->_limit = $limit;
        
        return $this->db->select($this->_where, $this->table_name, $this->_field, $this->_order, $this->_limit);
    }
    /**
     * 单条查询
     * @param string or array  $where语句 可以是数组
     * @param string  $fields 要查找的字段
     * @param string  $oeder  排序
     */
    public function find($where=NULL, $field=NULL, $order=NULL){
        $list = $this->select($where, $field, $order,$limit=1);
        return $list[0];
    }
    /**
     * 查询数据条数
     * @param array or string 查询条件 可以是 数组 
     */
    public function count($where=NULL){
        $where && $this->_where = $where;
        
        return $this->db->count($this->table_name,$this->_where);
    }
    /**
     * 修改数据
     * @param array or string $data要修改的数据 字符串为 sql 语句 数组key 为字段名 value 为字段值
     * @param array or string $where 条件语句 可为数组
     */
    public function update($data=NULL, $where=NULL){
        $data && $this->_set = $data;
        $where && $this->_where = $where;
        
        return $this->db->update($this->_set, $this->table_name, $this->_where);
    }
    /**
      * 插入数据
      * @param array $data 要添加的数据 key 为字段名 value 为字段值
      * @param bool $return_insert_id 是否返回主键 号
      * @param bool $replace 是否 为替换插入
      */
    public function insert($data=NULL, $return_insert_id=false, $replace=false){
        $data && $this->_set = $data;
        return $this->db->insert($this->_set, $this->table_name, $return_insert_id, $replace);
    }
    /**
      * 删除数据 
      * @param string or array $where 条件
      * @return bool
      */
    public function delete($where=NULL){
        $where && $this->_where = $where;
        return $this->db->delete($this->table_name,$this->_where);
    }
    /**
     * 执行基本的 mysql查询
     */
    public function query($sql){
        return $this->db->query($sql);
    }
    /**
     * 获取最后一次执行的sql 语句
     */
    public function last_sql(){
        return $this->db->last_sql();
    }
}

?>