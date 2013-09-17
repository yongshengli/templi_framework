<?php
include('./includes/Template.class.php');
$tempLi=new Template(); 


$title='我的模板引擎';
$content='内容管理';
$array =array('a'=>'aaaaaaaaaaaaaaaaaaaaaa','b'=>'bbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbbb');
$arrayb=array(array('a'=>'gggggggggggggggggggggggggg','b'=>'hhhhhhhhhhhhhhhhhhhhhhhhhh'),array('a'=>'a111111111','b'=>'bbbbb11111111111111111'));
include $tempLi->display('html.html');

include('./includes/Db.class.php');
include('./includes/Page.class.php');
$db = new db_mysql('127.0.0.1', 'root', '', 'tongxuebb');
$list1 =$db->query('select * from tx_user');
//print_r($list);
$listuser =$db->find('','tx_user','uname','uid desc');
$num =$db->count('tx_user',1);

$page  = intval($_GET['page']);
$mypage=new pages();
$pages = $mypage->page($total=$num,$page,$listNum=20,$pageNum=8,$urlrule='',$maxpage=0);

echo $pages;


$string ='01234567890abcdefghijklmnopqrstuvwxyz';
echo $string[0];
?>