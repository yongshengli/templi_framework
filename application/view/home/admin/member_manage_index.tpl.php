<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
          	<th><input name="" type="checkbox" value="" /></th>
            <th>id</th>
            <th>账号</th>
            <th>邮箱</th>
            <th>所属组</th>
            <th>登录次数</th>
            <th>最后登录ip</th>
            <th>最后登录时间</th>
            <th>操作</th>
          </tr>
          <?php if(is_array($data['list'])): foreach($data['list'] as $r): ?>
          <tr>
            <td><input name="" type="checkbox" value="" /></td>
            <td><?=$r['userid']?></td>
            <td><?=$r['username']?></td>
            <td><?=$r['email']?></td>
            <td><?=$r['group_id']?></td>
            <td><?=$r['login_num']?></td>
            <td><?=$r['last_loginip']?></td>
            <td><?=date('Y-m-d H:i:s',$r['last_logintime'])?></td>
            <td class="czA">
                <?php if($r['role_id']!=1):?>
                <a href="index.php?m=home&c=member_manage&a=del&id=<?=$r['userid']?>&menu_pid=<?=$_GET['menu_pid']?>" onclick="return confirm('你确定要删除吗？');">删除</a>|
                <?php endif?>
                <a href="index.php?m=home&c=member_manage&a=edit&id=<?=$r['userid']?>&menu_pid=<?=$_GET['menu_pid']?>">修改</a>
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
</body>
</html>
