<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * 异常处理类
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date 2013-3-19
 */
class Abnormal extends Exception{
    
    //额外的 调试信息
    private $extra;
    public function __construct($message, $code, $extra=false){
        parent::__construct($message, $code);
        $this->extra =$extra;
    }
    public function __toString(){
        $error =array();
        $trace = $this->getTrace();
        if($this->extra)
            array_shift($trace);
        //print_r($trace);
        $traceInfo ='';
        $time =date('y-m-d H:i:s');
        foreach($trace as $val){
            $traceInfo .= '['.$time.'] '.$val['file'].' ('.$val['line'].') ';
            $traceInfo .= isset($val['class'])?$val['class']:'';
            $traceInfo .= isset($val['type'])?$val['type']:'';
            $traceInfo .= isset($val['function'])?$val['function'].'(':'';
            $traceInfo .= is_array($val['args'])?implode(', ', $val['args']):'';
            $traceInfo .=")\n";
        }
        $error['file']  = $this->getFile();
        $error['line']  = $this->getLine();
        $error['message']= $this->message;
        $error['code']  =$this->code;
        $error['trace'] =$traceInfo;
        return $error;
    }
}
?>