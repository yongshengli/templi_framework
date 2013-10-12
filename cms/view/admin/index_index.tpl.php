<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$title?></title>
<link href="<?php echo THEMES_URL?>admin/css/common.css" type="text/css" rel="stylesheet" />
<link href="<?php echo THEMES_URL?>admin/css/glxt.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo JS_URL?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo JS_URL?>jquery-ui/js/jquery-ui-1.10.2.custom.min.js"></script>
</head>
<body style="overflow: hidden;">
<div class="topGg">
        <div class="top-line">
    	   <div class="logo">
                <img src="<?=THEMES_URL?>admin/images/logo.png"/>
           </div>
           <div class="user_info fl">
                <span><a>你好!<?=$info['adminname']?> [<?=$info['role']['title']?>]</a></span>
                <span>|</span>
                <span><a href="index.php?m=admin&c=index&a=logout">退出</a></span>
                <span>|</span><span><a href="<?php echo APP_URL?>/">前台首页</a></span>
                <span>|</span>
                <span><a href="index.php?m=admin&c=webset&a=clean_cache">清除网站缓存</a></span>
           </div>
           <div class="user_info fr">
                <span><a href="http://www.tongxuebb.com">官方网站</a></span>
                <span>|</span>
                <span><a href="">帮助?</a></span>
           </div>
        </div>
        <div class="rPbtn">
            <?php foreach($menulist as $r):?>
                <a href="javascript:getmenu(<?=$r['node_id']?>);" id="topM_<?=$r['node_id']?>" class="topM <?php if($info['default_pid']==$r['node_id']): echo 'current';endif;?>"><?php echo $r['title']?></a>
            <?php endforeach?>
        </div>
</div>
<div class="gMain" style="height: 640px;">
	<div class="leftMain" style="height: 100%;">
        <iframe src="index.php?m=admin&c=index&a=menu&pid=<?=$info['default_pid']?>" id="menu" width="100%" name="left" height="100%" allowtransparency="true" frameborder="false" style="border: medium none;"></iframe>
    </div>
    <div class="rightMain" style="width: 900px;height: 600px;">
        <iframe src="index.php?m=admin&c=mypanel&a=index" id="main" name="right" width="100%" height="100%" allowtransparency="true" frameborder="false" style="border: medium none; margin-bottom: 30px;"></iframe>
    </div>
</div>
<script>
    $(function($){
        resize();
        $(window).resize(function(){
            resize()
        });
        function resize(){
             var height = $(window).height()-110;
             var width = $(window).width()-170;
             $('#main').css({'height':height+'px','width':width+'px'});
       }
    })
    function getmenu(pid){
        $('#menu').attr('src','index.php?m=admin&c=index&a=menu&pid='+pid);
        $('.topM').removeClass('current');
        $('#topM_'+pid).addClass('current');
    }
</script>
</body>
</html>
