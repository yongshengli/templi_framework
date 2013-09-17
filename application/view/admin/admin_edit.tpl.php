<?php include $this->tpl('header')?>
<div class="Main">
    <div class="form">
        <form method="post" action="index.php?m=admin&c=admin&a=<?=$GLOBALS['action']?>">
            <table cellpadding="0" cellspacing="0" border="0" class="form_table">
              <tr>
                <td>账号:</td>
                <td><input name="info[adminname]" type="text"  value="<?=$info['adminname']?>" class="input_text"/> 
                <input type="hidden" name="info[adminid]" value="<?=$info['adminid']?>"/></td>
              </tr>
              <tr>
                <td>密码:</td>
                <td><input name="info[password]" type="text"  value="<?=$info['description']?>" class="input_text"/></td>
              </tr>
              <tr>
                <td>Email:</td>
                <td><input name="info[email]" type="text"  value="<?=$info['email']?>" class="input_text"/></td>
              </tr>
              <tr>
                <td>备注:</td>
                <td><input name="info[remark]" type="text"  value="<?=$info['remark']?>" class="input_text"/></td>
              </tr>
              <tr>
                <td>角色:</td>
                <td>
                    <select size="1" name="info[role_id]">
                        <option value="0">请选择角色</option>
                        <?php foreach($role_list as $r):?>
                            <option value="<?=$r['role_id']?>" <?=$r['role_id']==$info['role_id']?'selected':''?>><?=$r['title']?></option>
                        <?php endforeach;?>
                    </select>
                </td>
              </tr>
            </table>
            <div class="handle"><input type="submit" name="dosubmit" value="提交" class="input_submit"/></div>
        </form>
    </div>
    
</div>
<?php include $this->tpl('footer')?>
