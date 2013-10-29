<?php 
defined('IN_TEMPLI') or die('非法引用');
class verifyController{
    protected function init(){
        
    }
    public function index(){
        $filed_arr =array(
            'length'=>4,
            'mode'=>5,
            'size'=>array(70, 30),
            'font'=>array(),
            'disturb'=>3,
            'border'=>true,
            'verifyName'=>'verify'
            );
        foreach($filed_arr as $key=>$val){
            if($key=='size'){
                $width = isset($_GET['width'])?intval($_GET['width']):$filed_arr['size'][0];
                $height= isset($_GET['height'])?intval($_GET['height']):$filed_arr['size'][1];
                $size =array($width, $height);
            }elseif(isset($_GET[$key])){
                $$key =strval(trim($_GET[$key]));
            }else{
                $$key =$val;
            }
        }
        Templi::include_common_file('Image_verify.class.php');
        die(Image_verify::buildImageVerify($length, $mode, $size, $font, $disturb, $border,$type='png', $verifyName));
    }
}
?>