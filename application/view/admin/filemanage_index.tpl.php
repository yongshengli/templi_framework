<?php include $this->tpl('header')?>
<div class="Main">
    <div style="width: 600px;">
        <?php if(is_array($list)): foreach($list as $val):?>
            <div style="width: 100px; height: 100px; float: left;margin: 10px;">
                <a href="index.php?m=admin&c=filemanage&a=index&path=<?=$val['path']?>">
                    <?php if($val['filetype']=='file'):?>
                        <img src="<?=$val['path']?>" />
                    <?php else:?>
                        <img src="<?=IMG_URL?>admin/dir.jpg" />
                    <?php endif;?>
                </a>
                <span><?=$val['name']?></span>
            </div>
        <?php  endforeach;endif;?>
    </div>
</div>
<?php include $this->tpl('footer')?>