<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
          	<th>ID</th>
            <th>名称</th>
            <th>操作</th>
          </tr>
          <?php foreach($data['list'] as $r):?>
          <tr>
            <td><?=$r['location_id']?></td>
            <td><?=$r['name']?></td>
            <td>
                <a href="index.php?m=admin&c=location&a=index&menu_pid=<?=$_GET['menu_pid']?>&parent_id=<?=$r['location_id']?>">管理子菜单</a> | 
                <a href="index.php?m=admin&c=location&a=edit&menu_pid=<?=$_GET['menu_pid']?>&id=<?=$r['id']?>">修改</a>
            </td>
          </tr>
          <?php endforeach?>
    </table>
    <div  class="pages">
          <div class="inR">
                <?=$data['page_html']?>
          </div>
    </div>
</div>
<?php include $this->tpl('footer')?>