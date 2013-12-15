<?php if(Session::get('userid')):?>
	<a class="header_login_btn" id="J_head_forum_post" role="button" aria-label="快速发帖" aria-haspopup="J_head_forum_pop" href="#" title="快速发帖" tabindex="-1"><span class="inside"><span class="header_post" >发帖</span></span></a>
	<div id="J_head_forum_pop" tabindex="0" class="pop_select_forum" style="display:none;" aria-label="快速发帖菜单,按ESC键关闭菜单">
		<a id="J_head_forum_close" href="#" class="pop_close" role="button">关闭窗口</a>
		<div class="core_arrow_top" style="left:310px;"><em></em><span></span></div>
		<div class="hd">发帖到其他版块</div>
		<div id="J_head_forum_ct" class="ct cc" data-fid="<?php echo $_tmpfid;?>" data-cid="<?php echo $_tmpcid;?>">
			<div class="pop_loading"></div>
		</div>
		<div class="ft">
			<div class="associate">
				<label class="fr"><input type="checkbox" id="J_head_forum_join" data-url="{@url:bbs/forum/join}">添加到我的版块</label>
				发帖到：<span id="J_post_to_forum"></span>
			</div>
			<div class="tac">
				<button type="button" class="btn btn_submit disabled" disabled="disabled" id="J_head_forum_sub" data-url="{@url:bbs/post/run/}">确定</button>
			</div>
		</div>
	</div>
	<div class="header_login_later">
        <ul class="megamenu">
            <li>
        		<a href="<?php echo url('home/index/index');?>" class="username header_menu_hd" title="<?php echo Session::get('username');?>"><?php echo Session::get('username');?><em class="core_arrow"></em></a>
        		
                <div style="width: 130px;" class="header_menu my_menu cc">
                  <ul class="ct cc">
						<li><a href="http://localhost/phpwind/index.php?m=space"><em class="icon_space"></em>我的空间</a></li>
						<li><a href="http://localhost/phpwind/index.php?m=my&amp;c=fresh"><em class="icon_fresh"></em>我的关注</a></li>
						<li><a href="http://localhost/phpwind/index.php?m=bbs&amp;c=forum&amp;a=my"><em class="icon_forum"></em>我的版块</a></li>
						<li><a href="http://localhost/phpwind/index.php?m=my&amp;c=article"><em class="icon_article"></em>我的帖子</a></li>
						<li><a href="http://localhost/phpwind/index.php?m=vote&amp;c=my"><em class="icon_vote"></em>我的投票</a></li>
						<li><a href="http://localhost/phpwind/index.php?m=task"><em class="icon_task"></em>我的任务</a></li>
						<li><a href="http://localhost/phpwind/index.php?m=medal"><em class="icon_medal"></em>我的勋章</a></li>
						<li><a rel="nofollow" href="http://www.phpwind.cc/index.php?m=manage&amp;c=content"><em class="icon_system"></em>前台管理</a></li>
						<li><a rel="nofollow" target="_blank" href="http://www.phpwind.cc/admin.php"><em class="icon_admin"></em>系统后台</a></li>
					</ul>
                    <ul class="ft cc">
    					<li><a href="<?php echo url('home/member_info/profile');?>"><em class="icon_profile"></em>个人设置</a></li>
                        <li><a href="<?php echo url('home/member/logout');?>"><em class="icon_quit"></em>退出</a></li>
    				</ul>
                </div>
            </li>
            <li>
        		<a href="{@url:message/message/run}"><span class="inside"><span class="<?php echo $messageClass;?>">消息<em class="core_num J_hm_num"><?php echo $messageCount;?></em></span></span></a>
            	<!--消息下拉菜单-->
            	<div id="J_head_msg_pop" class="header_menu_wrap my_message_menu">
            		<div style="width: 300px;">
                      <ul id="list-content">
                        <li>Point 1 is the first point
                          <ul>
                            <li>Point 1.1 goes here</li>
                            <li>Point 1.2 goes here</li>
                            <li>Point 1.3 can go here also</li>
                          </ul>
                        </li>
                        <li>Point 2 is the second point
                          <ul>
                            <li>Point 2.1 is a sub point</li>
                            <li>Point 2.2 is a sub point</li>
                          </ul>
                        </li>
                        <li>Point 3 is the third point
                          <ul>
                            <li>Point 3.1 is a sub point</li>
                            <li>Point 3.2 is a sub point</li>
                          </ul>
                        </li>
                        <li>Point 4 is the lone fourth point without any children</li>
                      </ul>
                    </div>
            	</div>
            </li>
        </ul>
	</div>
	<!--# if ($loginUser->info['message_tone'] > 0 && $messageCount > 0) { #-->
	<audio autoplay="autoplay">
		<source src="{@theme:images}/message/msg.wav" type="audio/wav" />
		<source src="{@theme:images}/message/msg.mp3" type="audio/mp3" />
		<div style='overflow:hidden;width:0;float:left'><embed src='{@theme:images}/message/msg.wav' width='0' height='0' AutoStart='true' type='application/x-mplayer2'></embed></div>
	</audio>
	<!--# } #-->
<?php else:?>
<div class="header_login">
	<hook name="header_info_3"/><a rel="nofollow" href="<?php echo url('home/member/login');?>">登录</a><a rel="nofollow" href="<?php echo url('home/member/register');?>">注册</a>
</div>
<?php endif?>
