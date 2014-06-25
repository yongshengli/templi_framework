<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * 缓存类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-3-22
 */
class Cache_file{
    //缓存有效期
    private $timeout = null;
    //缓存数据方式 1 array 2 serialize
    private $cache_datatype = null;
    //private $cache_datatype ='serialize';
    public function __construct($dataType='array', $timeout=0){
        $this->timeout = $dataType;
        $this->cache_datatype = $timeout;
    }
    /**
     * 读取缓存文件
     * @param $filename 缓存文件名
     * @param mid
     */
    public function get($filename){
        if(!$filename){
            return false;
        }
        $file =Templi::get_config('app_path').'cache/datas/'.$filename.'_cache.php';
        if(!file_exists($file)){
            return false;
        }
        //echo $this->timeout;
        if($this->timeout>0){
            if(SYS_TIME-filemtime($file)>$this->timeout){
                return false;
            }
        }
        if($this->cache_datatype=='array'){
            $data = include($file);
        }else{
            $data = unserialize(file_get_contents($file));
        }
        return $data;
    }
    /**
     * 设置写入缓存文件
     * @param string $filename 文件名
     * @content array or string  缓存的内容
     * @return bool 成功返回 true 失败返回 false
     */
    public function set($filename, &$content){
        if(!$filename || !$content)return false;
        require_once(TEMPLI_PATH.'dir.func.php');
        dir_create(Templi::get_config('app_path').'cache/datas/');
        $file =Templi::get_config('app_path').'cache/datas/'.$filename.'_cache.php';
        if($this->cache_datatype=='array'){
            $file_size = file_put_contents($file, "<?php \nreturn ".var_export($content,true).";\n?>");
        }else{
            $file_size = file_put_contents($file, serialize($content));
        }
        return $file_size?true:false;
    }
    /**
     * 清除缓存文件
     * @param string $filename 文件名
     * @return bool 成功 true 失败 false
     */
    public function clean($filename=null){
        if($filename){
            return @unlink(Templi::get_config('app_path').'cache/datas/'.$filename.'_cache.php');
        }else{
            require_once(TEMPLI_PATH.'dir.func.php');
            return dir_delete(Templi::get_config('app_path').'cache/datas/');
        }
    }
       
}