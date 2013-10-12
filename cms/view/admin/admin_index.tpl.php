<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
          	<th><input name="" type="checkbox" value="" /></th>
            <th>id</th>
            <th>账号</th>
            <th>备注</th>
            <th>角色</th>
            <th>登录次数</th>
            <th>最后登录ip</th>
            <th>最后登录时间</th>
            <th>操作</th>
          </tr>
          <?php if(is_array($data['list'])): foreach($data['list'] as $r): ?>
          <tr>
            <td><input name="" type="checkbox" value="" /></td>
            <td><?=$r['adminid']?></td>
            <td><?=$r['adminname']?></td>
            <td><?=$r['remark']?></td>
            <td><?=$r['role_name']?></td>
            <td><?=$r['loginnum']?></td>
            <td><?=$r['loginip']?></td>
            <td><?=date('Y-m-d H:i:s',$r['last_logintime'])?></td>
            <td class="czA">
                <?php if($r['role_id']!=1):?>
                <a href="index.php?m=admin&c=admin&a=del&id=<?=$r['adminid']?>&menu_pid=<?=$_GET['menu_pid']?>" onclick="return confirm('你确定要删除吗？');">删除</a>|
                <?php endif?>
                <a href="index.php?m=admin&c=admin&a=edit&id=<?=$r['adminid']?>&menu_pid=<?=$_GET['menu_pid']?>">修改</a>
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
