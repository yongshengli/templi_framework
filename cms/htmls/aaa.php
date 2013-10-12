<?php

call_user_func(array(new b,'index'));



class a{
    public function __construct(){
        self::c();
        echo 'fffff';
        
    }
    public static function c(){
        self::d();
    } 
    public static function d(){
        echo 'dgggggggggggggggggggggggg';
    }
}


class b extends a{
    public function index(){
        
    }
}
?>