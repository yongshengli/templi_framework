<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
          	<th><input name="" type="checkbox" value="" /></th>
            <th>编号</th>
            <th>角色名</th>
            <th>描述</th>
            <th>添加日期</th>
            <th>操作</th>
          </tr>
          <?php if(is_array($data['list'])): foreach($data['list'] as $r): ?>
          <tr>
            <td><input name="" type="checkbox" value="" /></td>
            <td><?=$r['role_id']?></td>
            <td><?php echo $r['title']?></td>
            <td><?=$r['description']?></td>
            <td><?=date('Y-m-d H:i:s',$r['ctime'])?></td>
            <td class="czA">
                <?php if($r['role_id']==1):?>
                    不可修改
                <?php else:?>
                    <a href="index.php?m=admin&c=role&a=del&id=<?=$r['role_id']?>&menu_pid=<?=$_GET['menu_pid']?>" onclick="return confirm('你确定要删除吗？');">删除</a>|
                    <a href="index.php?m=admin&c=role&a=edit&id=<?=$r['role_id']?>&menu_pid=<?=$_GET['menu_pid']?>">修改</a>|
                    <a href="index.php?m=admin&c=role&a=role_access&id=<?=$r['role_id']?>&menu_pid=<?=$_GET['menu_pid']?>">设置权限</a>
                <?php endif?>
            </td>
          </tr>
          <?php endforeach;endif;?>
    </table>
    <div  class="pages">
        <div class="inR">
            <?=$data['page_html']?>
        </div>
    </div>
</div>
<?php include $this->tpl('footer')?>
