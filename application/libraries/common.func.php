<?php 
/**
 * 获取用户头像
 */
function getAvatar($userid, $size){
    $size_arr = array('big','middle','small','original');
    if(!in_array($size,$size_arr))
        $size ='middle';
	if(file_exists(UPLOAD_PATH.'avatar/'.$userid.'/'.$size.'.jpg')){
		return UPLOAD_URL.'avatar/'.$userid.'/'.$size.'.jpg';
	}else{
		return IMG_URL."/images/user_pic_{$size}.gif";
	}
}
?>