<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * memcache缓存类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
class Cache_memcahe{
    private $memcache;
    public function __construct(){
        $this->memcache = new Memcache;
		$this->memcache->connect(templi::get_config('cache_host'), templi::get_config('cache_port'), templi::get_config('cache_timeout'));
    }
    /**
     * 获取缓存
     * @param string $name  memcache id
     * @param boole
     */
    public function get($name){
        $value = $this->memcache->get($name);
		return $value;
    }
    /**
     * 设置缓存
     * @param sting $name memecache id
     * @param string $value 缓存值
     * @param boole
     */
    public function set($name, $value, $ttl = 0, $ext1=null, $ext2=null){
        return $this->memcache->set($name, $value, false, $ttl);
    }
    /**
     * 清除缓存
     * @param string $name  memcache id
     * @rerurn boole
     */
    public function clean($name=null){
        if($name){
            return $this->memcache->delete($name);
        }else{
            return $this->memcache->flush();
        }
        
    }
    /**
     * 关闭连接
     */
    public function close(){
        $this->memcache->close();
    }
}
?>