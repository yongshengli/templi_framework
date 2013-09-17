<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" style="height: 100%;">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Templi管理后台</title>
<link href="<?php echo THEMES_URL?>admin/css/common.css" type="text/css" rel="stylesheet" />
<link href="<?php echo THEMES_URL?>admin/css/glxt.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo JS_URL?>jquery.min.js"></script>
</head>
<body class="wrap">
	<div class="layM">
    	<div class="login">
        	<div class="lText">Templi CMS管理后台</div>
            <div class="dwBx">
                <form name="loginform" id="loginform" action="<?=url('admin/index/login')?>" method="post">
                   <ul class="lUl">
                   	  <li class="dwText">用户登录</li>
                      <li><label>用户名：</label><input name="username" type="text" /></li>
                      <li><label>密&nbsp;&nbsp;码：</label><input name="password" type="password" /></li>
                      <li>
                          <span class="fl"><label>验证码：</label><input name="verify" type="text" class="sInput" /></span>
                          <span class="yzmP"><img src="index.php?m=index&c=verify" width="70" height="30" id="img_verify" onclick="this.src='index.php?m=index&c=verify&time='+Math.random()"/><a href="javascript:change_verify();">看不清，换一张</a></span>
                      </li>
                   </ul>
                   <input type="submit" name="dosubmit" value="登录" class="submit" />
                </form>
            </div>
        </div>
        <div class="CopyRight">
            CopyRight 2006-2013 同学帮帮网络 templi cms 管理系统
        </div>
    </div>
<script type="text/javascript">
 function change_verify(){
    //alert('ssss');
    var url ='index.php?m=index&c=verify&time='+Math.random();
    //alert(url);
    $('#img_verify').attr('src',url);
 }
</script>
<?php include $this->tpl('footer')?>
