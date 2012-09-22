<?php
/**
 * 
 * SQL操作函数
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version         $ID: SqlUtils.php 2011-8-5 Administrator tengzhiwangluoruanjian$

 */
 

/**
 *
 * SQL文过滤
 * @param $sql
 */

function formatQuery($input_arr) {
	if(get_magic_quotes_gpc()){
		return $input_arr;
	}
	if(is_array($input_arr)){
		$tmp = array();
		foreach ($input_arr as $key1 => $val){
			$tmp[$key1] = formatQuery($val);
		}
		return $tmp;
	}else{
		return addslashes($input_arr);
	}
}

/**
 *
 * 按照指定的$orderKey排序
 * @param $orderMap
 * @param $orderKey
 * @param $orderType
 */
function setOrder($orderMap, $orderKey, $orderType) {
	foreach ($orderMap as $orderItem) {
		if ($orderKey == $orderItem[0]) {
			return $orderItem[1].' '.$orderType;
		}
	}
	return "";
	//return $orderMap[$orderKey].' '.$orderType;
}

function dHtml($string)
{
	if(is_array($string))
	{
		foreach($string as $key =>$val)
		{
			$string[$key] = dhtml($val);
		}
	}else
	{
		$string = str_replace(array('"','\'','<','>',"\t","\r",'{','}'),array('&quot;','&#39;','&lt;','&gt;','&nbsp;&nbsp;','','&#123;','&#125;'),$string);
	}
	return $string;
}
function cvHttp($http)
{
	if ($http == '')
	{
		return '';
	}else
	{
		$link = substr($http,0,7) == "http://"?$http : 'http://'.$http;
		return $link;
	}
}
function htmlCv($string)
{
	$pattern = array('/(javascript|jscript|js|vbscript|vbs|about):/i','/on(mouse|exit|error|click|dblclick|key|load|unload|change|move|submit|reset|cut|copy|select|start|stop)/i','/<script([^>]*)>/i','/<iframe([^>]*)>/i','/<frame([^>]*)>/i','/<link([^>]*)>/i','/@import/i');
	$replace = array('','','&lt;script${1}&gt;','&lt;iframe${1}&gt;','&lt;frame${1}&gt;','&lt;link${1}&gt;','');
	$string = preg_replace($pattern,$replace,$string);
	$string = str_replace(array('</script>','</iframe>','&#'),array('&lt;/script&gt;','&lt;/iframe&gt;','&amp;#'),$string);
	return stripslashes($string);
}

function formatTags($tags) {
	return $tags;
}

/**
 *
 * 返回时间区间
 * @param $time1
 * @param $time2
 */
function setTime($time1, $time2) {
	if($time1!='' && $time2!='') {
		return strtotime($time1).','.strtotime($time2);
	}
	return null;
}

/**
 *
 * 返回数目区间
 * @param $n1
 * @param $n2
 */
function setViewCount($n1, $n2) {
	if($n1!='' && $n2!='') {
		return $n1.','.$n2;
	}
	return null;
}

/**
 * 根据分号转换成多条sql文
 * Enter description here ...
 * @param $runsql
 */
function splitsql($runsql) {
	return explode(';', $runsql);
}