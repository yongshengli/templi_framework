<?php include $this->tpl('header')?>
<div class="Main">
    <div class="form">
    <form method="post" action="index.php?m=admin&c=category&a=<?=$GLOBALS['action']?>"> 
        <div class="con_adu2">
            <div class="bj_ifo2">
                <div class="adv_pay">
                    <table border="0" cellspacing="0" cellpadding="0" class="form_table">
                        <tr>
                            <td class="pay_td1">上级栏目：</td>
                            <td class="pay_td2">
                                <select name="info[parent_id]" class="input_text">
									<option value="0">一级栏目</option>
                                    <?=$option_html?>
								</select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">分类：</td>
                            <td class="pay_td2">
                                <select name="info[st_name]" class="input_text">
                                    <?=$st_option_html?>
								</select>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">中文名：</td>
                            <td class="pay_td2">
                                <input name="info[id]" type="hidden" value="<?=$info['id']?>"/>
                            	<input name="info[title]" value="<?=$info['title']?>" class="input_text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">英文名：</td>
                            <td class="pay_td2">
                            	<input name="info[category]" value="<?=$info['category']?>" class="input_text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">关键词：</td>
                            <td class="pay_td2">
                            	<textarea name="info[keywords]" cols="80" rows="5" wrap="virtual" maxlength="100"><?=$info['keywords']?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">描述：</td>
                            <td class="pay_td2">
                            	<textarea name="info[description]" cols="80" rows="5" wrap="virtual" maxlength="100"><?=$info['description']?></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">display：</td>
                            <td class="pay_td2">
                            	显示<input type="radio" name="info[display]" value="1" <?php if($info['display']==1):?> checked="checked"<?php endif?> />
                                不显示<input type="radio" name="info[display]" value="0" <?php if($info['display']==0):?> checked="checked"<?php endif?> />
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