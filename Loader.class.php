<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 14-6-29
 * Time: 下午9:31
 */

trait Loader
{
    /**
     * @var array 已载入的文件
     */
    private static $_files =[];
    /**
     * 引入文件
     * 默认加载路径是 系统类库
     */
    public static function load($file)
    {
        if(!isset(self::$_files[$file])){
            if(!file_exists($file)){
                return false;
            }
            self::$_files[$file] = require($file);
        }
        return self::$_files[$file];
    }
    /**
     * 多位置引入文件
     * 找到文件 后返回
     */
    public static function array_include($file_arr=array())
    {
        foreach($file_arr as $file) {
            $result = self::load($file);
            if($result){
                return $result;
            }
        }
        return false;
    }
}