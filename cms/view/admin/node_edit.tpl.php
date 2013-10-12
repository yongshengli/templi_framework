<?php include $this->tpl('header')?>
<div class="Main">
    <div class="form">
    <form method="post" id="myform" name="myform" action="index.php?m=admin&c=node&a=<?php echo $GLOBALS['action']?>"> 
        <div class="con_adu2">
            <div class="adu_tit">添加/修改菜单</div>
            <div class="bj_ifo2">
                <div class="adv_pay">
                    <ul id="errorlist"></ul>
                    <table border="0" cellspacing="0" cellpadding="0" class="form_table">
                        <tr>
                            <td class="pay_td1">所属父类：</td>
                            <td class="pay_td2">
                                <select name="info[parent_id]" class="input_text">
									<option value="0">一级模块</option>
                                    <?=$option_html?>
								</select>
                            </td>
                            <td></td>
                        </tr>
                        <tr>
                            <td class="pay_td1">中文名：</td>
                            <td class="pay_td2">
                                <input name="node_id" type="hidden" value="<?=$info['node_id']?>"/>
                            	<input id="title" name="info[title]" value="<?=$info['title']?>" class="input_text"/>
                            </td>
                            <td><span id="titleTip"></span></td>
                        </tr>
                        <tr>
                            <td class="pay_td1">模块：</td>
                            <td class="pay_td2">
                            	<input id="module" name="info[module]" class="input_text" value="<?=$info['module']?>"/>
                            </td>
                            <td><span id="moduleTip"></span></td>
                        </tr>
                        <tr>
                            <td class="pay_td1">controller：</td>
                            <td class="pay_td2">
                            	<input id="controller" name="info[controller]" class="input_text" value="<?=$info['controller']?>"/>
                            </td>
                            <td><span id="controllerTip"></span></td>
                        </tr>
                        <tr>
                            <td class="pay_td1">action：</td>
                            <td class="pay_td2">
                            	<input id="action" name="info[action]" class="input_text" value="<?=$info['action']?>"/>
                            </td>
                            <td><span id="actionTip"></span></td>
                        </tr>
                        <tr>
                            <td class="pay_td1">display：</td>
                            <td class="pay_td2">
                            	显示<input type="radio" name="info[display]" value="1" <?php if($info['display']==1):?> checked="checked"<?php endif?> />
                                不显示<input type="radio" name="info[display]" value="0" <?php if($info['display']==0):?> checked="checked"<?php endif?> />
                            </td>
                            <td></td>
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
<script type="text/javascript">
$(function($){
        $.formValidator.initConfig({formID:"myform",theme:"ArrowSolidBox",
		onError:function(msg,obj,errorlist){
			//$("#errorlist").empty();
			//$.map(errorlist,function(msg){
				//$("#errorlist").append("<li>" + msg + "</li>")
			//});
			//alert(msg);
		},
		ajaxPrompt : '有数据正在异步验证，请稍等...'
	});
    $("#title").formValidator({onShow:"中文名必填",onFocus:"中文名必填"}).inputValidator({min:1,max:30,onError:"中文名长度在1-30字符之间"});
    $("#module").formValidator({onShow:"模块必填",onFocus:"模块必填"}).inputValidator({min:1,max:20,onError:"模块长度1-20个字符之间"});
    $("#controller").formValidator({onShow:"控制器必填",onFocus:"控制器必填"}).inputValidator({min:1,max:20,onError:"控制器长度1-20个字符之间"});
    $("#action").formValidator({onShow:"方法必填",onFocus:"方法必填"}).inputValidator({min:1,max:20,onError:"方法名长度1-20个字符之间"});
})

</script>
<?php include $this->tpl('footer')?>