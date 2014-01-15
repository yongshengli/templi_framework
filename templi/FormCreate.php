<?php
//defined('IN_TEMPLI') or die('非法引用');
/**
 * form 验证 处理类
 * @package  TEMPLI
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-1-19
 **/
class FormCreate{
    private $config = array(
            
        );
    private $form_html='';
    function __construct(){
        $this->data=array(
                
                'username'=>array(
                    'name'=>'username',
                    'id'=>'sssss',
                    'type'=>'text'
                ),
                'password'=>array(
                    'name'=>'password',
                    'type'=>'password'
                ),
                /*'sex'=>array(  
                    array('name'=>'sex','id'=>'sesssss','type'=>'radio','value'=>array(0,1,2)),
                    array(),
                )*/  
            );
        echo $this->html();
    }
    public function text($attr){
        $this->input($attr);
    }
    public function password($attr){
        $this->input($attr);
    }
    public function radio($attr){
        $my_attr = array();
        if(is_array($attr['value'])){
            foreach($attr['value'] as $key=>$val){
                $my_attr[]=$attr;
                
            }
        }
    }
    /**
     * input 输入框
     * @param array $attr 属性配置
     */
    public function input($attr=array()){
        $form_html ='';
        $label= isset($attr['label'])?$attr['label']:$attr['name'];
        $form_html .= '<label>'.$label.':</label> ';
        $form_html .= '<input ';
        foreach($attr as $key=>$val){
            if($key=='label') continue;
            if($key=='value'){
                $form_html .= ' "'.$key.'"="{$'.$val.'}"';
            }else{
                $form_html .= ' "'.$key.'"="'.$val.'"';
            }
        }
        $form_html .= ' />';
        return $form_html;
    }
    /**
     * 文本域
     */
    public function textarea($attr=array()){
        $form_html ='';
        $label= isset($attr['label'])?$attr['label']:$attr['name'];
        $form_html .= '<label>'.$label.':</label> ';
        $form_html .= '<textarea ';
        foreach($attr as $key=>$val){
            if($key=='value' || $key=='label') continue;
            $form_html .= ' "'.$key.'"="'.$val.'"';
        }
        $form_html .= '{$'.$attr['value'].'}';
        $form_html .= ' </textarea>';
        return $form_html;
    }
    /**
     * 按钮
     */
    public function button(){
        $form_html ='';
        $label= isset($attr['label'])?$attr['label']:$attr['name'];
        $form_html .= '<button ';
        foreach($attr as $key=>$val){
            if($key=='label') continue;
            $form_html .= ' "'.$key.'"="'.$val.'"';
        }
        $form_html .= $label;
        $form_html .= ' </button>';
    }
    public function html(){
        foreach($this->data as $val){
            $this->$val['type']($val);
        }
        return $this->form_html;
    }
}

new FormCreate();