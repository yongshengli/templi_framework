<?php
defined('IN_TEMPLI') or die('非法引用');
/**
 * TempLi 共共函数库 常用函数  
 * @author 七觞酒
 * @email 739800600@qq.com
 * @date  2013-1-20
 */


/**
 * 返回经addslashes处理过的字符串或数组
 * @param string or array  $data
 * @rerurn string or array
 */
function new_addslashes($data){
    if(!is_array($data)){
        return addslashes($data);
    }else{
        foreach($data as $k=>$v){
           $data[$k] =new_addslashes($v); 
        }
        return $data;
    }
}
/**
 * 返回经stripslashes处理过的字符串或数组
 * @param string or array  $data
 * @rerurn string or array
 */
function new_stripslashes($data){
    if(!is_array($data)){
        return stripslashes($data);
    }else{
        foreach($data as $k=>$v){
            $data[$k]= new_stripslashes($v);
        }
        return $data;
    }
}
/**
 * 返回经htmlspecialchars处理过的字符串或数组
 * @param array or string $data 需要处理的字符串或数组
 * @return mixed
 */
function new_htmlspecialchars($data) {
	if(!is_array($data)) return htmlspecialchars($data);
	foreach($data as $k => $v) $string[$k] = new_html_special_chars($v);
	return $data;
}
/**
 * 返回经htmlspecialchars_decode处理过的字符串或数组
 * @param array or string $data 需要处理的字符串或数组
 * @return mixed
 */
function new_htmlspecialchars_decode($data) {
	if(!is_array($data)) return htmlspecialchars_decode($data);
	foreach($data as $k => $v) $string[$k] = new_html_special_chars_decode($v);
	return $data;
}
/**
 * 安全过滤函数
 *
 * @param $string
 * @return string
 */
function safe_replace($string) {
    $string =str_replace(
        array(0=>'%20',1=>'%27',2=>'%2527',3=>'*',4=>'\'',5=>'"',6=>';',7=>'<',8=>'>',9=>'{',10=>'}',11=>'\\'),
        array(0=>'',1=>'',2=>'',3=>'',4=>'',5=>'',6=>'',7=>'&lt;',8=>'&gt;',9=>'',10=>'',11=>''),
        $string
    );
	return $string;
}
/**
 * 错误输出
 */
function halt($error){
    include 'tpl/halt.html';
    die;
}
/**
 * 404输出
 */
function show_404($url=''){
    if(!$url){
        $url = Templi::get_config('404_url');
    }
    if($url){
        redirect($url);
    }elseif(function_exists('send_http_status')){
        send_http_status(404);
    }
    die;
}
/**
* url 重定向
*/
function redirect($url, $time=0, $msg='') {
	//多行URL地址支持
    $url = str_replace(array("\n", "\r"), '', $url);
    if (empty($msg))
        $msg = "系统将在{$time}秒之后自动跳转到{$url}！";
    if(!headers_sent()){
		if($time){
			header('"refresh:{$time};url={$url}"');
			echo $msg;
		}else{
			header('Location: ' . $url);
		}
    }else{
		$str ="<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
		if ($time != 0)
            $str .= $msg;
        exit($str);
	}
}
/**
 * url js 跳转
 */
function url_skip($url){
    die('<script type="text/javascript">window.location.href="'.$url.'"</script>');
}
/**
 * 获取当前页面完整URL地址
 */
function get_url() {
	$sys_protocal = isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://';
	$php_self = $_SERVER['PHP_SELF'] ? safe_replace($_SERVER['PHP_SELF']) : safe_replace($_SERVER['SCRIPT_NAME']);
	$path_info = isset($_SERVER['PATH_INFO']) ? safe_replace($_SERVER['PATH_INFO']) : '';
	$relate_url = isset($_SERVER['REQUEST_URI']) ? safe_replace($_SERVER['REQUEST_URI']) : $php_self.(isset($_SERVER['QUERY_STRING']) ? '?'.safe_replace($_SERVER['QUERY_STRING']) : $path_info);
	return $sys_protocal.(isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '').$relate_url;
}
/**
 * 生成url 如果不穿 参数 则 返回当前页面url
 * @param string $main_params =$module/$controller/$action;
 * @param $param array   get 参数 array('id'=>3)
 * @example url('home/member/index',array('id'=>28)));
 */
function url($main_params = NULL, $params = array(), $url_model = NULL){
    $main_params = explode('/',$main_params);
    if(is_null($main_params) && is_null($params)){
        //没有参数返回当前页url
        return get_url();
    }
    $url_model = $url_model?$url_model:Templi::get_config('url_model');
    //$url_model = 2;
    $params =   array_map('trim',$params);
    $str   =   rtrim(APP_URL,'/').'/';
    //http://www.TempLi.com/m/c-a-id-5.html
    if($url_model ==1){
        $str .=$main_params[0].'/'.$main_params[1].'-'.$main_params[2];
        foreach($params as $key=>$val){
           $str .= '-'.$key.'-'.$val;
        }
        $str .='.html';
    }elseif($url_model ==2){
        $str .=$main_params[0].'/'.$main_params[1].'-'.$main_params[2];
        foreach($params as $key=>$val){
           $str .= '-'.$val;
        }
        $str .='.html';
    }else{
        $str .='index.php?m='.$main_params[0].'&c='.$main_params[1].'&a='.$main_params[2];   
        $str .= $params?'&'.http_build_query($params):'';
    }
    return $str;
}
/**
 * 快捷方法 获取网站设置
 * @param string $field 配置项 字段名
 */
function get_webset($field=NUll){
    $M_webset = Templi::model('webset');
    return $M_webset->get_webset($field);
}
/**
* 转换字节数为其他单位
*
*
* @param	string	$filesize	字节大小
* @return	string	返回大小
*/
function sizecount($filesize) {
	if ($filesize >= 1073741824) {
		$filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
	} elseif ($filesize >= 1048576) {
		$filesize = round($filesize / 1048576 * 100) / 100 .' MB';
	} elseif($filesize >= 1024) {
		$filesize = round($filesize / 1024 * 100) / 100 . ' KB';
	} else {
		$filesize = $filesize.' Bytes';
	}
	return $filesize;
}
/**
 * 获取客户端 ip
 *
 * @return ip地址
 */
function getip() {
	if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown')) {
		$ip = getenv('HTTP_CLIENT_IP');
	} elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown')) {
		$ip = getenv('HTTP_X_FORWARDED_FOR');
	} elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown')) {
		$ip = getenv('REMOTE_ADDR');
	} elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown')) {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return preg_match ( '/[\d\.]{7,15}/', $ip, $matches ) ? $matches [0] : '';
}
/**
 * 检查字符串是否是UTF8编码
 * @param string $string 字符串
 * @return Boolean
 */
function isUtf8($str) {
    $c=0; $b=0;
    $bits=0;
    $len=strlen($str);
    for($i=0; $i<$len; $i++){
        $c=ord($str[$i]);
        if($c > 128){
            if(($c >= 254)) return false;
            elseif($c >= 252) $bits=6;
            elseif($c >= 248) $bits=5;
            elseif($c >= 240) $bits=4;
            elseif($c >= 224) $bits=3;
            elseif($c >= 192) $bits=2;
            else return false;
            if(($i+$bits) > $len) return false;
            while($bits > 1){
                $i++;
                $b=ord($str[$i]);
                if($b < 128 || $b > 191) return false;
                $bits--;
            }
        }
    }
    return true;
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true) {
    if(function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif(function_exists('iconv_substr')) {
        $slice = iconv_substr($str,$start,$length,$charset);
    }else{
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("",array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice.'...' : $slice;
}
/**
 * 字符截取 支持UTF8/GBK
 * @param $string
 * @param $length
 * @param $dot
 */
function str_cut($string, $length, $dot = '...') {
	$strlen = strlen($string);
	if($strlen <= $length) return $string;
	$string = str_replace(array(' ','&nbsp;', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), array('∵',' ', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), $string);
	$strcut = '';
	if(strtolower(CHARSET) == 'utf-8') {
		$length = intval($length-strlen($dot)-$length/3);
		$n = $tn = $noc = 0;
		while($n < strlen($string)) {
			$t = ord($string[$n]);
			if($t == 9 || $t == 10 || (32 <= $t && $t <= 126)) {
				$tn = 1; $n++; $noc++;
			} elseif(194 <= $t && $t <= 223) {
				$tn = 2; $n += 2; $noc += 2;
			} elseif(224 <= $t && $t <= 239) {
				$tn = 3; $n += 3; $noc += 2;
			} elseif(240 <= $t && $t <= 247) {
				$tn = 4; $n += 4; $noc += 2;
			} elseif(248 <= $t && $t <= 251) {
				$tn = 5; $n += 5; $noc += 2;
			} elseif($t == 252 || $t == 253) {
				$tn = 6; $n += 6; $noc += 2;
			} else {
				$n++;
			}
			if($noc >= $length) {
				break;
			}
		}
		if($noc > $length) {
			$n -= $tn;
		}
		$strcut = substr($string, 0, $n);
		$strcut = str_replace(array('∵', '&', '"', "'", '“', '”', '—', '<', '>', '·', '…'), array(' ', '&amp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;'), $strcut);
	} else {
		$dotlen = strlen($dot);
		$maxi = $length - $dotlen - 1;
		$current_str = '';
		$search_arr = array('&',' ', '"', "'", '“', '”', '—', '<', '>', '·', '…','∵');
		$replace_arr = array('&amp;','&nbsp;', '&quot;', '&#039;', '&ldquo;', '&rdquo;', '&mdash;', '&lt;', '&gt;', '&middot;', '&hellip;',' ');
		$search_flip = array_flip($search_arr);
		for ($i = 0; $i < $maxi; $i++) {
			$current_str = ord($string[$i]) > 127 ? $string[$i].$string[++$i] : $string[$i];
			if (in_array($current_str, $search_arr)) {
				$key = $search_flip[$current_str];
				$current_str = str_replace($search_arr[$key], $replace_arr[$key], $current_str);
			}
			$strcut .= $current_str;
		}
	}
	return $strcut.$dot;
}
/**
 * 对数据进行编码转换
 * @param array or string $data       数组
 * @param string $input     需要转换的编码
 * @param string $output    转换后的编码
 */
function array_iconv($data, $input = 'gbk', $output = 'utf-8') {
	if (!is_array($data)) {
		return iconv($input, $output, $data);
	} else {
		foreach ($data as $key=>$val) {
			if(is_array($val)) {
				$data[$key] = array_iconv($val, $input, $output);
			} else {
				$data[$key] = iconv($input, $output, $val);
			}
		}
		return $data;
	}
}
/**
 * 程序执行时间 时间戳
 *
 * @return	int 
 */
function get_cost_time() {
	$microtime = microtime ( TRUE );
	return $microtime - SYS_START_TIME;
}
/**
 * 程序执行时间
 *
 * @return	int	单位ms
 */
function execute_time() {
	$stime = explode ( ' ', SYS_START_TIME );
	$etime = explode ( ' ', microtime () );
	return number_format ( ($etime [1] + $etime [0] - $stime [1] - $stime [0]), 6 );
}
/**
 * 获取精确时间戳 microtime
 * @return int 
 */
function getmicrotime() {
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}
?>