<?php include $this->tpl('header')?>
<div class="Main">
    <div class="form">
        <form method="post" action="index.php?m=news&c=manage&a=<?=ACTION?>">
            <table cellpadding="0" cellspacing="0" border="0" class="form_table">
              <tr>
                <td>标题:</td>
                <td><input name="info[title]" type="text"  value="<?=$info['title']?>" class="input_text"/> 
                <input type="hidden" name="info[id]" value="<?=$info['id']?>"/></td>
              </tr>
              <tr>
                <td>标题:</td>
                <td>
                    <select size="1" name="info[category_id]">
                        <?=$category_html?>
                    </select>
                </td>
              </tr>
              <tr>
                <td>内容:</td>
                <td>
                    <script  id="editor" type="text/plain" name="info[content]"></script>
                </td>
              </tr>
            </table>
            <div class="handle"><input type="submit" name="dosubmit" value="提交" class="input_submit"/></div>
        </form>
    </div>
    
</div>
<script>
    var ue = UE.getEditor('editor');
        ue.addListener('ready',function(){
            this.focus();
        });
</script>
<?php include $this->tpl('footer')?>