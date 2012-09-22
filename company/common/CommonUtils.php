<?php
/**
 * 
 * 基本操作函数
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version         $ID: commonUtils.php 2011-8-10 Administrator tengzhiwangluoruanjian$

 */
 
/**
 * 
 * 判断是否为英文或英文数字
 * @param $englishStr
 */
 function isEnglist($englishStr) {
 	return preg_match("/^[a-z|A-Z|0-9]+$/u", $englishStr); 
 }