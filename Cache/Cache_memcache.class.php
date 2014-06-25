<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * memcache缓存类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
class Cache_memcahe extends Memcache
{
    /**
     * @var int 默认数据有效期
     */
    private $_expire = null;
    /**
     * @var bool 数据是否压缩
     */
    private $_flag= null;

    public function __construct($expire=0, $flag=0)
    {
        $this->_flag = $flag;
        $this->_expire = $expire;
    }
    /**
     * 获取缓存
     * @param string $name  memcache id
     * @param boole
     * @return mixed
     */
    public function get($name, $flag=null){
        if (is_null($flag)) {
            $flag = $this->_flag;
        }
        $value = parent::get($name, $flag);
		return $value;
    }
    /**
     * 设置缓存
     *
     * @param string $name memecache id
     * @param mixed $value 缓存值
     * @param int $expire 有效期
     * @param bool $flag 是否压缩
     * @param boole
     * @return bool
     */
    public function set($name, $value, $flag=null, $expire=null){
        if (is_null($expire)) {
            $expire = $this->_expire;
        }
        if (is_null($flag)) {
            $flag = $this->_flag;
        }
        return parent::set($name, $value, $flag, $expire);
    }
    /**
     * 清除缓存
     *
     * @param string $name  memcache id
     * @return bool
     */
    public function clean($name=null){
        if($name){
            return $this->delete($name);
        }else{
            $this->flush();
            return true;
        }
    }
}