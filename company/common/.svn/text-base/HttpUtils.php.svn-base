<?php
/**
 *
 * http操作函数
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version         $ID: function.php 2011-7-29 Administrator tengzhiwangluoruanjian$

 */

/**
 *
 * 获取请求方法
 */
function getMethod() {
	return strtolower($_SERVER['REQUEST_METHOD']);
}

/**
 *
 * 字符解密
 * @param $encodestring
 */
function safe_b64decode($encodestring) {
	$data = str_replace(array('-','_'),array('+','/'),$string);
	$mod4 = strlen($data) %4;
	if ($mod4)
	{
		$data .= substr('====',$mod4);
	}
	return base64_decode($data);
}

/**
 * 字符加密
 * @param unknown_type $encodestring
 */
function safe_b64encode($encodestring) {
	$data = base64_encode($string);
	$data = str_replace(array('+','/','='),array('-','_',''),$data);
	return $data;
}


