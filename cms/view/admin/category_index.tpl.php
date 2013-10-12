<?php include $this->tpl('header')?>
<div class="Main">
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
          	<th>id</th>
            <th>栏目名称</th>
            <th>栏目所属类别</th>
            <th>操作</th>
          </tr>
          <?=$tree_html?>
    </table>
</div>
<?php include $this->tpl('footer')?>