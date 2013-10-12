<?php include $this->tpl('header')?>
<div class="Main">
    <div class="form">
    <form method="post" action="index.php?m=admin&c=st&a=<?=$GLOBALS['action']?>"> 
        <div class="con_adu2">
            <div class="bj_ifo2">
                <div class="adv_pay">
                    <table border="0" cellspacing="0" cellpadding="0" class="form_table">
                        <tr>
                            <td class="pay_td1">所属父类：</td>
                            <td class="pay_td2">
                                <select name="info[parent_name]" class="input_text">
									<option value="_top">一级分类</option>
                                    <?=$option_html?>
								</select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">中文名：</td>
                            <td class="pay_td2">
                            	<input name="info[title]" value="<?=$info['title']?>" class="input_text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">英文名：</td>
                            <td class="pay_td2">
                            	<input name="info[name]" value="<?=$info['name']?>" class="input_text" <?php echo $GLOBALS['action']=='edit'?'disabled="disabled"':''?>/>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">描述：</td>
                            <td class="pay_td2">
                            	<textarea name="info[description]" cols="80" rows="5" wrap="virtual" maxlength="100"><?=$info['description']?></textarea>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="sub_but2">
           <span><input class="input_submit" name="dosubmit" type="submit" value="提 交"/></span>
           <span><input class="input_submit" name="" type="reset" value="取 消"/></span>
        </div>
    </form>
    </div>
</div>
<div class="clear"></div>
<?php include $this->tpl('footer')?>