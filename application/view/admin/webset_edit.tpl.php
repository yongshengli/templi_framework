<?php include $this->tpl('header')?>
<div class="Main">
    <div class="form">
        <form method="post" action="index.php?m=admin&c=webset&a=<?=$GLOBALS['action']?>">
            <table cellpadding="0" cellspacing="0" border="0" class="form_table">
              <tr>
                <td>标题:</td>
                <td>
                    <input name="info[title]" type="text"  value="<?=$info['title']?>" class="input_text"/> 
                    <input type="hidden" name="info[id]" value="<?=$info['id']?>"/>
                </td>
              </tr>
              <tr>
                <td>字段名:</td>
                <td>
                    <input name="info[name]" type="text"  value="<?=$info['name']?>" class="input_text"/> 
                </td>
              </tr>
              <tr>
                <td>角色描述:</td>
                <td>
                    <textarea name="info[description]" rows="5" wrap="virtual" cols="50" ><?=$info['description']?></textarea>
                </td>
              </tr>
              <tr>
                <td>字段值:</td>
                <td>
                    <textarea name="info[data]" rows="5" wrap="virtual" cols="50" ><?=$info['data']?></textarea>
                </td>
              </tr>
            </table>
            <div class="handle"><input type="submit" name="dosubmit" value="提交" class="input_submit"/></div>
        </form>
    </div>
    
</div>
<?php include $this->tpl('footer')?>
