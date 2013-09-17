<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * 验证码
 * @author 七殇酒
 * @email 739800600@qq.com
 * @lastmodify   2013-4-25
 *
 */
class Image_verify{
    /**
     * 生成图像验证码
     * @static
     * @access public
     * @param string $length  位数
     * @param string $mode  类型 0 字母 1 数字 2大写字母 3小写字母 4汉字 其它混合
     * @param string $type 图像格式
     * @param string $size= array(width,height)  图片大小宽度
     * @param array $font =array(font,fontsize)  字体
     * @param int $disturb 是否干扰 1 点干扰 2 线干扰 3 复合干扰 0 无干扰 4 间断直线
     * @param bool $border 是否要边框
     * @param string $verifyName session key
     */
    static function buildImageVerify($length=4, $mode=3, $size=array(70, 30), $font=array(), $disturb=3, $border=true,$type='png', $verifyName='verify') {
        Templi::include_common_file('String.class.php');
        Templi::include_common_file('Image.class.php');
        Templi::include_common_file('Session.class.php');
        Session::factory();
        $randval = String::randString($length, $mode);
        Session::set($verifyName, strtolower($randval));
        return Image::buildString($randval, $size, $font, $filename='',$rgb=array(), $type, $disturb, $border);
        
    }
}
?>