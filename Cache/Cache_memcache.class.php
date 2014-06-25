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
    public function __construct(){
		$this->connect(templi::get_config('cache_host'), templi::get_config('cache_port'), templi::get_config('cache_timeout'));
    }
    /**
     * 获取缓存
     * @param string $name  memcache id
     * @param boole
     */
    public function get($name, $flag=0){
        $value = parent::get($name, $flag);
		return $value;
    }
    /**
     * 设置缓存
     *
     * @param sting $name memecache id
     * @param mixed $value 缓存值
     * @param int expire 有效期
     * @param bool $flage 是否压缩
     * @param boole
     */
    public function set($name, $value, $expire = 0, $flag=false){
        return parent::set($name, $value, $flag, $expire);
    }
    /**
     * 清除缓存
     * @param string $name  memcache id
     * @rerurn boole
     */
    public function clean($name=null){
        if($name){
            return $this->delete($name);
        }else{
            return $this->flush();
        }
    }
}