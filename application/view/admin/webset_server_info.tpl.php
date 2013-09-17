<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
            <td>当前时间</td>
            <td><?php echo $server['time']?></td>
          </tr>
          <tr>
            <td>上传文件</td>
            <td><?=$server['upfile']?></td>
          </tr>
          <tr>
            <td>全局变量 register_globals</td>
            <td><?=$server['register_globals']?></td>
          </tr>
          <tr>
            <td>安全模式 safe_mode</td>
            <td><?=$server['safe_mode']?></td>
          </tr>
          <tr>
            <td>系统</td>
            <td><?=$server['software']?></td>
          </tr>
          <tr>
            <td>PHP版本:</td><td><?=PHP_VERSION?></td>
            </tr>
    	  <tr>
            <td>Zend引擎版本:</td><td><?=zend_version()?></td>
          </tr>
          <tr>
            <td>内存占用:</td><td><?=$server['memory']?></td>
          </tr>
          <tr>
            <td>内存限制:</td><td><?=$server['memory_limit']?></td>
          </tr>
          <tr>
            <td>程序超时限制:</td><td><?=@get_cfg_var("max_execution_time")."秒"?></td>
          </tr>
    </table>
</div>
<?php include $this->tpl('footer')?>