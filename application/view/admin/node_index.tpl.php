<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
          	<th>id</th>
            <th>节点</th>
            <th>操作</th>
          </tr>
          <?=$tree_html?>
    </table>
</div>
<?php include $this->tpl('footer')?>