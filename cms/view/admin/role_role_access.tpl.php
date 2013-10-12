<?php include $this->tpl('header')?>
<div class="Main">
    <form method="post" action="index.php?m=admin&c=role&a=role_access">
    <input type="hidden" name="info[role_id]" value="<?=$info['role_id']?>"/>
    <table cellpadding="0" cellspacing="0" border="0" class="grayBd" >
          <tr>
            <th>节点</th>
          </tr>
          <?=$tree_html?>
    </table>
    <div class="handle">
       <span><input class="input_submit" name="dosubmit" type="submit" value="提 交"/></span>
       <span><input class="input_submit" name="" type="reset" value="取 消"/></span>
    </div>
    </form>
</div>
<script type="text/javascript">
    function check_node(obj){
         var status = $(obj).prop('checked');
         if(status){
            parent(obj, status);
         }
         child(obj, status);
    }
    function parent(obj, status){
        var parent_id = $(obj).attr('pid');
        var o = $('#node-'+parent_id+' input[type="checkbox"]');
        if(o){
            o.each(function(){
            $(this).prop('checked', status);
            parent(this, status);
            })
        }
    }
    function child(obj, status){
        var node_id =$(obj).val();
        var o = $('.menu-node-'+node_id+' input[type="checkbox"]');
        if(o){
          o.each(function(){
            $(this).prop('checked', status);
            child(this, status);
          })  
        }
    }
</script>
<?php include $this->tpl('footer')?>