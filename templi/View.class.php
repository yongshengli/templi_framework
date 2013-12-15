<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * php 模板引擎
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-1-19
 */
class View{
    //视图文件路径
    public $template = '';
    //编译文件保存路径
    public $compile  = '';
    //模板变量
    private $viewVar = array();
    function __construct(){
        Templi::include_common_file('dir.func.php');
        $this->template= Templi::get_config('app_path').'view/';
        $this->compile = Templi::get_config('app_path').'cache/compile/';
    }
    /**
     * 设置模板路径
     */
    public function set_view_dir($template){
        $this->template = rtrim($template,'/\\').'/';
    }
    /**
     * 设置编译缓存路径
     */
    public function set_compile_dir($compile){
        $this->compile = rtrim($compile,'/\\').'/';
    }
    /**
     * 分配变量 变量批量分配
     * @param array $data 变量名
     */
    public function setOutput($data){
        if (!is_array($data)) return;
        $this->viewVar = array_merge($this->viewVar,$data); 
    }

    /**
     * 分配变量 单个变量分配
     * @param string $name 变量名
     * @param \mid|string $value 变量值
     */
    public function assign($name, $value=''){
        $this->viewVar[$name] = $value;
    }

    /**
     * 页面显示
     * @param string $template_file_name 模板文件名(不包括扩展名)
     * @internal param string $template_file_extend 视图文件扩展名
     */
    public function display($template_file_name=null){
        $template = $this->compile($template_file_name);
        extract($this->viewVar, EXTR_OVERWRITE);
        require_once $template;
    }
    /**
     * 载入 模板缓存文件
     */
    public function loadView($template_file_name=null){
        return $this->compile($template_file_name);
    }
    /**
     * 模板编译 并缓存
     */
    private function compile($template_file_name=null){
         //视图文件名
        $file_info =pathinfo($template_file_name);
        $template_file =$file_info['extension']?$template_file_name:$template_file_name.'.html';
        //视图文件路径
        $template_file_path =$this->template;
        $compile_file_name = $file_info['extension']?($file_info['dirname'].'/'.$file_info['filename']):$template_file_name;
        //编译文件
        $compile_file =$this->compile.$compile_file_name.'.tpl.php';

        if(!file_exists($template_file_path.$template_file)){
            if(APP_DEBUG)
                throw new Abnormal($template_file_path.$template_file.'模板文件不存在', 0, true);
            else
                show_404();
        }
        if(!file_exists($compile_file) || filemtime($compile_file)<filemtime($template_file_path.$template_file)){
            $content   = file_get_contents($template_file_path.$template_file,filesize($template_file_path.$template_file));
            $content = $this->replace_tag($content);
            dir_create(dirname($compile_file));
            file_put_contents($compile_file,$content);
            return $compile_file;
        }else{
            return $compile_file;
        }
    }
    /**
     * 标签替换
     */
    private function replace_tag($str){
        if(!$str) return;
        $find_tag  = array(
            0=>'/\{loop\s+?(\$\S+?)\s+?(\$\S+?)\}/i',     // foreach 循环
            1=>'/\{loop\s+?(\$\S+?)\s+?(\$\S+?)\s+?(\$\S+?)\}/i',   //foreach 循环
            2=>'/\{\/loop\}/i',   //foreach 循环结束标签
            3=>'/\{if\s+?(.+?)\}/i',  //if 标签
            4=>'/\{else\}/i',  //else 标签
            5=>'/\{elseif\s+?(.+?)\}/i', //elseif标签
            6=>'/\{\/if\}/i', //if 结束标签
            7=>'/\{php\s+?(.+?)\}/i',   //php源代码标签
            8=>'/\{include\s+?(.+?)\}/i', //include
            9=>'/\{\s*?([a-zA-Z_\x7f-\xff][a-zA-Z_0-9:\x7f-\xff]*?\([^{}]*?\))\s*?\}/i',//函数
            10=>'/\{\s*?(\$[a-zA-Z_\x7f-\xff][a-zA-Z_0-9\x7f-\xff]*?([^{}]+?))\s*?\}/i',//数组变量
            11=>'/\{\s*?(\$[a-zA-Z_\x7f-\xff][a-zA-Z_0-9\x7f-\xff]*?)\s*?\}/i',//变量
            12=>'/\{\s*?([A-Z_]+?)\s*?\}/', //常量
            13=>'/\{template\s+?file=[\"\']?([\w\/]+)[\"\']?\s+?module=[\"\']?(\w+)[\"\']?\}/',//载入模板文件
            14=>'/\{template\s+?file=[\"\']?([\w\/]+)[\"\']?\}/',//载入模板文件
        );
        $replace_tag = array(
            0=>'<?php $n=1; if(is_array(\\1))foreach(\\1 as \\2):?>', //foreach 循环
            1=>'<?php $n=1; if(is_array(\\1))foreach(\\1 as \\2=>\\3):?>', //foreach循环
            2=>'<?php $n++;endforeach;unset($n);?>',//foreach 循环结束标签
            3=>'<?php if(\\1):?>',//if 
            4=>'<?php else:?>',// else
            5=>'<?php elseif(\\1):?>',//elseif
            6=>'<?php endif?>',//if 结束标签
            7=>'<?php \\1;?>',  //php 源代码标签
            8=>'<?php include \\1;?>',//include
            9=>'<?php echo \\1;?>',//函数显示
            10=>'<?php echo \\1;?>',//数组变量
            11=>'<?php echo \\1;?>',//变量
            12=>'<?php echo \\1;?>', //常量
            13=>'<?php include Templi::include_html("\\1","\\2");?>',
            14=>'<?php include Templi::include_html("\\1");?>',
        );
        
        $str = preg_replace($find_tag,$replace_tag,$str);
        return $str;
    }
}