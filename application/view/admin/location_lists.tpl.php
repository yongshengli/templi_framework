<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
          	<th>ID</th>
            <th>名称</th>
            <th>描述</th>
            <th>操作</th>
          </tr>
          <?php foreach($data['list'] as $r):?>
          <tr>
            <td><?=$r['id']?></td>
            <td><?=$r['name']?></td>
            <td><?=$r['description']?></td>
            <td>
                <a href="index.php?m=admin&c=location&a=lists&menu_pid=<?=$_GET['menu_pid']?>&keyid=<?=$r['keyid']?>&parent_id=<?=$r['id']?>">管理子菜单</a> | 
                <a href="index.php?m=admin&c=location&a=edit&menu_pid=<?=$_GET['menu_pid']?>&id=<?=$r['id']?>">修改</a> | 
                <a href="index.php?m=admin&c=webset&a=del&menu_pid=<?=$_GET['menu_pid']?>&id=<?=$r['id']?>">删除</a>
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