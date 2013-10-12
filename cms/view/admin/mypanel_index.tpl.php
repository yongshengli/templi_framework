<?php include $this->tpl('header')?>
<div class="Main">
    <div style="width: 50%;float: left;">
        <div class="box-hd">
            <h3 class="box-hd-title">我的个人信息</h3>
        </div>
        <ul class="box-bd-list">
            <li>你好! <?=$info['adminname']?></li>
            <li>所属角色：<?=$info['role']['title']?></li>
            <li>登录次数: <?=$info['login_num']?></li>
            <li>上次登录时间：<?=date('Y-m-d H:i:s',$info['last_logintime'])?></li>
            <li>上次登录ip: <?=$info['last_loginip']?></li>
        </ul>
        <div class="hc10"></div>
        <div class="box-hd">
            <h3 class="box-hd-title">快捷方式</h3>
        </div>
        <ul class="box-bd-list">
            <li>CopyRight 2006-2013 同学帮帮网络 templi cms 管理系统</li>
        </ul>
        
    </div>
    <div style="width: 48%; float:right;">
        <div class="box-hd">
            <h3 class="box-hd-title">安全提示</h3>
        </div>
        <ul class="box-bd-list">
            <li><font color="red">※ 强烈建议您将script目录设置为644（linux/unix）或只读（NT）</font></li>
            <li><font color="red">※ 强烈建议您网站上线后，建议关闭 DEBUG （前台SQL错误提示）</font></li>
        </ul>
        <div class="hc10"></div>
        <div class="box-hd">
            <h3 class="box-hd-title">版权信息</h3>
        </div>
        <ul class="box-bd-list">
            <li><font color="red">CopyRight 2006-2013 同学帮帮网络 templi cms 管理系统</font></li>
            <li><a href="#">开发者： 七觞酒</a></li>
            <li><a href="#">qq：739800600</a></li>
            <li><a href="#">email：739800600@qq.com</a></li>
            <li><a href="#">电话：13240702278</a></li>
        </ul>
        
    </div>
</div>
<?php include $this->tpl('footer')?>