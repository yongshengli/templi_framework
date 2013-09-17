<?php include $this->tpl('header')?>
<div class="Main">
    <div class="form">
        <form method="post" action="index.php?m=admin&c=mypanel&a=<?=$GLOBALS['action']?>">
            <table cellpadding="0" cellspacing="0" border="0" class="form_table">
              <tr>
                <td>用户名:</td>
                <td><?=$info['adminname']?> 
                <input type="hidden" name="info[adminid]" value="<?=$info['adminid']?>"/></td>
              </tr>
              <tr>
                <td>旧密码:</td>
                <td><input name="info[old]" type="text"  value="" class="input_text"/> 
              </tr>
              <tr>
                <td>新密码:</td>
                <td><input name="info[new]" type="text"  value="" class="input_text"/> 
              </tr>
              <tr>
                <td>重复密码:</td>
                <td><input name="info[new2]" type="text"  value="" class="input_text"/></td>
              </tr>
            </table>
            <div class="handle"><input type="submit" name="dosubmit" value="提交" class="input_submit"/></div>
        </form>
    </div>
    
</div>
<?php include $this->tpl('footer')?>
