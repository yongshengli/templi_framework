<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
          	<th>id</th>
            <th>标题</th>
            <th>更新时间</th>
            <th>操作</th>
          </tr>
          <?php foreach($data['list'] as $r):?>
          <tr>
            <td><?=$r['id']?></td>
            <td><a href="/index.php?m=news&c=index&a=detail&id=<?=$r['id']?>"><?=$r['title']?></a></td>
            <td><?=date('Y-m-d H:i:s',$r['ctime'])?></td>
            <td><a href="index.php?m=news&c=manage&a=edit&menu_pid=<?=$_GET['menu_pid']?>">修改</a> | <a href="">删除</a></td>
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