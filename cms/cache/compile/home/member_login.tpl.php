<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
    <?php include Templi::include_html("head");?>
    <link href="<?php echo THEMES_URL;?>common/css/register.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo JS_URL;?>formvalidator/themes/126/style/style.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo JS_URL;?>formValidator/formValidator-4.1.3.js"></script>
    <script src="<?php echo JS_URL;?>formValidator/formValidatorRegex.js" type="text/javascript" charset="UTF-8"></script>
</head>
<body>
<div class="wrap">
<!--<style>.header_wrap{top:29px;}body{padding-top:75px;}</style>
<div id="notice"><?php echo $site_info_notice;?></div>-->
    <header class="header_wrap">
    	<div id="J_header" class="header cc">
    		<div class="logo">
    			<a href="<?php echo APP_URL;?>">
    				<img src="<?php echo THEMES_URL;?>common/images/logo.png" alt="<?php echo TEMPLI::get_config('app_name');?>"/>
    			</a>
    		</div>
    		<nav class="nav_wrap">
    			<div class="nav">
    				<ul>
    					<li class="current"><a href="">首页</a></li>
    				</ul>
    			</div>
    		</nav>
    		<div class="header_search" role="search">
    			<form action="{@url:search/s/run}" method="post">
    				<input type="text" id="s" aria-label="搜索关键词" accesskey="s" placeholder="搜索其实很简单" x-webkit-speech speech name="keyword"/>
    				<button type="submit" aria-label="搜索"><span>搜索</span></button>
    			</form>
    		</div>
    		<?php include Templi::include_html("header_login");?>
    	</div>
    </header>
	<div class="nav_weak" id="<?php echo $ck;?>">
		<ul class="cc">
			<li class="<?php echo $current;?>"><?php echo $_v['name']|html;?></li>
		</ul>
	</div>
    <div class="tac"><advertisement id='Site.NavBanner' sys='1'/></div>
    <div class="main_wrap">
		<div class="box_wrap register cc">
			<h2 class="reg_head"><?php echo $_errMsg;?></h2>
			<div class="reg_cont_wrap">
				<div class="reg_cont">
					<form name="loginForm" id="loginForm" method="post" action="<?php echo url('home/member/login');?>">
					<div class="reg_form">
						<!--#if ($url) {#-->
						<div class="tips"><span class="tips_icon">请登录后再继续浏览</span></div>
						<!--#}#-->
						<dl class="cc">
							<dt><label for="J_u_login_username">帐号：</label></dt>
							<dd><input id="J_u_login_username" name="info[username]" type="text" class="input length_4" aria-required="true" value=""/></dd>
							<dd id="J_u_login_usernameTip" aria-hidden="true" class="dd_r"></dd>
						</dl>
						<dl class="cc">
							<dt><label for="J_u_login_password">密码：</label></dt>
							<dd><input id="J_u_login_password"  name="info[password]" type="password" aria-required="true" class="input length_4" value=""/></dd>
							<dd class="dd_r">
								<span id="J_u_login_passwordTip" aria-hidden="true"></span>
							</dd>
						</dl>
						<div id="J_login_qa" style="display:none;"></div>
						<?php if($show_captcha):?>
						<dl class="cc dl_cd">
							<dt><label for="J_login_code">验证码：</label></dt>
							<dd>
								<input data-id="code" id="J_login_code" name="info[captcha]" type="text" class="input length_4 mb5">
								<div id="J_verify_code"><img src="index.php?m=index&c=verify" width="70" height="30" id="img_verify" onclick="this.src='index.php?m=index&c=verify&time='+Math.random()"/></span><a href="javascript:change_verify();">看不清，换一张</a></div>
							</dd>
							<dd class="dd_r"><span id="J_u_login_codeTip"></span></dd>
						</dl>
						<?php endif?>
						<dl class="cc pick">
							<dt>&nbsp;</dt>
							<dd><a rel="nofollow" href="{@url:u/findPwd/run}" class="fr mr10">找回密码</a><input name="rememberme" value="31536000" type="checkbox" class="checkbox" id="cktime"><label for="cktime">自动登录</label></dd>
						</dl>
						<dl class="cc">
							<dt>&nbsp;</dt>
							<dd><button class="btn btn_big btn_submit mr20" type="submit" id="dologin">登录</button>
							<input type="hidden" name="dosubmit" value="true"/>
							<input type="hidden" name="invite" value="<?php echo $invite;?>" />
							</dd>
						</dl>
					</div>
					</form>
				</div>
			</div>
			<div class="reg_side">
				<div class="reg_side_cont">
					<p class="mb10">还没有帐号？</p>
					<p class="mb20"><a rel="nofollow" href="<?php echo url('home/member/register');?>" class="btn btn_big">免费注册</a></p>
					<hook name="login_sidebar"/>
				</div>
			</div>
		</div>
	</div>
    <script type="text/javascript">
    $(document).ready(function(){
    	$.formValidator.initConfig({formID:"loginForm",theme:"templi",submitOnce:true});
        $("#J_u_login_username").formValidator({onShowText:"用户名",onFocus:"用户名3-15个字符"}).inputValidator({min:3,max:15,onError:"用户名长度为3-15个字符"});
        $("#J_u_login_password").formValidator({onFocus:"密码"}).inputValidator({min:1,onError:"密码不能为空"});
        <?php if($show_captcha):?>
            $("#J_login_code").formValidator({onFocus:"验证码"}).inputValidator({min:1,onError:"验证码不能为空"});
        <?php endif?>
    })
    </script>
<?php include Templi::include_html("footer");?>