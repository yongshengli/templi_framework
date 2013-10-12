<?php include $this->tpl('header')?>
<div class="Main">
    <div class="form">
        <form method="post" action="index.php?m=admin&c=role&a=<?=$GLOBALS['action']?>">
            <table cellpadding="0" cellspacing="0" border="0" class="form_table">
              <tr>
                <td>角色名:</td>
                <td><input name="info[title]" type="text"  value="<?=$info['title']?>" class="input_text"/> 
                <input type="hidden" name="info[role_id]" value="<?=$info['role_id']?>"/></td>
              </tr>
              <tr>
                <td>角色描述:</td>
                <td><input name="info[description]" type="text"  value="<?=$info['description']?>" class="input_text"/></td>
              </tr>
            </table>
            <div class="handle"><input type="submit" name="dosubmit" value="提交" class="input_submit"/></div>
        </form>
    </div>
    
</div>
<?php include $this->tpl('footer')?>
