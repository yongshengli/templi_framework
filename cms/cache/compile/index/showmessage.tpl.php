<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>提示消息</title>
<style>
    *{ padding:0; margin:0; font-size:12px}
    .message{margin:0 auto;margin-top:200px;border: 1px solid #01427a;background-color: #ebf2fa;width: 300px;font-size: 12px;}
    .message .title{font-size: 14px;height: 25px;line-height: 25px;border-bottom: #01427a 1px solid;padding: 2px 10px;background: #2a72af;color: #FFF;}
    .message .main{padding: 10px;}
    .msg{margin-bottom: 10px;}
    .link a{font-size: 12px; color: #574F4F;text-decoration: none;margin-right: 15px;}
</style>
</head>
<body>
<div class="message">
    <h3 class="title">提示消息</h3>
    <div class="main">
        <div class="msg"><?php echo $msg;?></div>
        <div class="link">
            <?php if($url_forward=='goback' || $url_forward==''):?>
                <a href="javascript:history.back();" >[返回上一页]</a>
            <?php elseif($url_forward):?>
                <a href="<?php echo $url_forward;?>">如果浏览器没有自动跳转,点击[这里]</a>
                <script type="text/javascript">setTimeout("redirect('<?php echo $url_forward;?>');",<?php echo $ms;?>);</script>
            <?php endif?>
            <a href="<?php echo APP_URL;?>">[首页]</a>
        </div>
    </div>
</div>
<script type="text/javascript">
function redirect(url){
   window.location.href= url;
}
</script>
</body>
</html>