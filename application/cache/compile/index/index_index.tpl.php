<?php $n=1; if(is_array($nav))foreach($nav as $r):?>
    <a href="<?php echo $r['url'];?>"><?php echo $r['title'];?></a>
<?php $n++;endforeach;unset($n);?>