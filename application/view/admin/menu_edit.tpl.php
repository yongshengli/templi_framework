<?php include $this->tpl('header')?>
<div class="Main">
    <div class="form">
    <form method="post" action="index.php?m=admin&c=menu&a=<?php echo $GLOBALS['action']?>"> 
        <div class="con_adu2">
            <div class="bj_ifo2">
                <div class="adv_pay">
                    <table border="0" cellspacing="0" cellpadding="0" class="form_table">
                        <tr>
                            <td class="pay_td1">上级菜单：</td>
                            <td class="pay_td2">
                                <select name="info[parent_id]" class="input_text">
									<option value="0">一级菜单</option>
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
                            <td class="pay_td1">名称：</td>
                            <td class="pay_td2">
                                <input name="info[id]" type="hidden" value="<?=$info['id']?>"/>
                            	<input name="info[title]" value="<?=$info['title']?>" class="input_text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">连接url：</td>
                            <td class="pay_td2">
                            	<input name="info[url]" value="<?=$info['url']?>" class="input_text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">链接ALT信息：</td>
                            <td class="pay_td2">
                            	<input name="info[alt]" value="<?=$info['alt']?>" class="input_text"/>
                            </td>
                        </tr>
                        <tr>
                            <td class="pay_td1">打开方式：</td>
                            <td class="pay_td2">
                                同一窗口打开<input type="radio" name="info[target]" value="0" <?=$info['target']==0?'checked="checked"':''?>/>
                                新窗口打开<input type="radio" name="info[target]" value="1" <?=$info['target']==1?'checked="checked"':''?>/>
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