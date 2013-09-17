<?php
defined('IN_TEMPLI') or die('非法引用');
class indexController extends Controller{
    protected function init(){
        //初始化方法
    }
    public function index(){
        
        $data['nav']= Templi::model('menu')->where(array('st_name'=>'main_nav'))->select();
        
        $this->setOutput($data);
        $this->display();
    }
    public function editor(){
        $this->display();
    }
    /**
     * 抛出异常
     * if(1){
            throw new Abnormal($message='测试',$code=1,true);
       }
     */
}
?>