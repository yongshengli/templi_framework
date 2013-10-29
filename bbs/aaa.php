<?php
    class a{
        const ABC = 'abc';
        function output(){
            echo self::ABC;
        }
    }
    class aa extends a{
        const ABC ='bcd';
    }
    $a = new aa();
    $a->output();
?>