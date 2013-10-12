<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
          	<th>id</th>
            <th>标题</th>
            <th>字段名</th>
            <th>字段值</th>
            <th>操作</th>
          </tr>
          <?php foreach($data['list'] as $r):?>
          <tr>
            <td><?=$r['id']?></td>
            <td><?=$r['title']?></td>
            <td><?=$r['name']?></td>
            <td><?=$r['data']?></td>
            <td><a href="index.php?m=admin&c=webset&a=edit&menu_pid=<?=$_GET['menu_pid']?>&id=<?=$r['id']?>">修改</a> | <a href="index.php?m=admin&c=webset&a=del&menu_pid=<?=$_GET['menu_pid']?>&id=<?=$r['id']?>">删除</a></td>
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