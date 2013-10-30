<?php
defined('IN_TEMPLI') or die('非法引用');
class indexController extends Controller{
    protected function init(){
        //初始化方法
    }
    public function index(){  
        Templi::model('menu')->db('slave')->where(array('st_name'=>'main_nav'));
        Templi::model('menu')->where_or(array('st_name'=>'my_setnav'), '=');
       // $data['nav']= Templi::model('menu')->select();
        //echo Templi::model('menu')->last_sql(),'<br>';
        Templi::model('menu')->where('id in ("1", "3", "4", "6")');
        $a = Templi::model('menu')->select();
        
        echo Templi::model('menu')->last_sql();
        //print_r($a);
        //print_r($data['nav']);
        //die;
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