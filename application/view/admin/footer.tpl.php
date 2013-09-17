<?php if(ACTION=='add' or ACTION=='edit'):?>
    <script type="text/javascript" charset="utf-8" src="<?php echo JS_URL?>formvalidator/formValidator.min.js"></script>
    <script type="text/javascript" charset="utf-8" src="<?php echo JS_URL?>formvalidator/formValidatorRegex.js"></script>
<?php endif?>
<!--当前内存使用<?php if(function_exists('memory_get_usage')) echo sizecount(memory_get_usage());?>-->
</body>
</html>