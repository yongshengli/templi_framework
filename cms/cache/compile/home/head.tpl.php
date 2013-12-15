<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $seo['title'];?></title>
<meta name="keywords" content="<?php echo $seo['keywords'];?>"/>
<meta name="description" content="<?php echo $seo['description'];?>"/>
<link href="<?php echo THEMES_URL;?>common/css/core.css" type="text/css" rel="stylesheet" />
<link href="<?php echo THEMES_URL;?>common/css/style.css" type="text/css" rel="stylesheet" />
<!--<link href="<?php echo JS_URL;?>megamenu/stylesheets/jquery.megamenu.css" type="text/css" rel="stylesheet" />-->
<script type="text/javascript" src="<?php echo JS_URL;?>jquery.min.js"></script>
<script type="text/javascript" src="<?php echo JS_URL;?>idtabs.js"></script>
<script type="text/javascript" src="<?php echo JS_URL;?>megamenu/javascripts/jquery.megamenu.js"></script>
<script src="<?php echo JS_URL;?>layer/layer.min.js"></script>
<script type="text/javascript">
  $(function($){
    var SelfLocation = window.location.href.split('?');
    switch (SelfLocation[1]) {
      case "justify_right":
        $(".megamenu").megamenu({ 'justify':'right' });
        break;
      case "justify_left":
      default:
        $(".megamenu").megamenu();
    }
  });
</script>