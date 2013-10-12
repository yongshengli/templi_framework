<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>templi后台管理系统</title>
<link href="<?=THEMES_URL?>admin/css/common.css" type="text/css" rel="stylesheet" />
<link href="<?=THEMES_URL?>admin/css/glxt.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?=JS_URL?>jquery.min.js"></script>
</head>
<body>
<div class="leftBar">
    <dl class="lMenu">
        <?php foreach($menulist as $r):?>
    	<dt id="menuM_<?=$r['node_id']?>" class="menuM <?php if($GLOBALS['controller']==($r['controller'].'Controller')): echo 'current'; endif;?>">
            <a href="javascript:go(<?=$r['node_id']?>,'m=<?=$r['module']?>&c=<?=$r['controller']?>&a=<?=$r['action']?>');"><?=$r['title']?></a>
        </dt>
        <?php endforeach;?>
    </dl>
</div>
<script>
function go(pid,url){
    window.top.$('#main').attr('src','index.php?'+url+'&menu_pid='+pid);
    $('.menuM').removeClass('current');
    $('#menuM_'+pid).addClass('current');
}
</script>
</body>
</html>