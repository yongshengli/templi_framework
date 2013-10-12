<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>templi后台管理系统</title>
    <link href="<?php echo THEMES_URL?>admin/css/common.css" type="text/css" rel="stylesheet" />
    <link href="<?php echo THEMES_URL?>admin/css/glxt.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="<?php echo JS_URL?>jquery.min.js"></script>
    <?php if($GLOBALS['action']=='add' or $GLOBALS['action']=='edit'):?>
        <script type="text/javascript" charset="utf-8" src="<?php echo JS_URL?>ueditor/editor_config.js"></script>
        <script type="text/javascript" charset="utf-8" src="<?php echo JS_URL?>ueditor/editor_all.js"></script>
    <?php endif?>
</head>
<body>
    <?php if(!$disable_submenu && $_GET['menu_pid']):?>
    <div class="Main">
        <div class="listA">
            <?php $sub_menulist=$this->getmenu($_GET['menu_pid']);if(is_array($sub_menulist)):foreach($sub_menulist as $r):?>
        	<?php if($r['action']==$GLOBALS['action']):?>
                <a href="index.php?m=<?=$r['module']?>&c=<?=$r['controller']?>&a=<?=$r['action']?>&menu_pid=<?=$r['parent_id']?>" class="alist2"><?=$r['title']?></a>
            <?php else:?>
                <a href="index.php?m=<?=$r['module']?>&c=<?=$r['controller']?>&a=<?=$r['action']?>&menu_pid=<?=$r['parent_id']?>" class="alist1"><?=$r['title']?></a>
            <?php endif?>
            <?php endforeach;endif;?>
        </div>
    </div>
    <?php endif?>