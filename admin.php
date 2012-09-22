<?php
/**
 * 
 * {function}
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version         $ID: admin.php 2011-7-28 Administrator tengzhiwangluoruanjian$

 */
 
header("Content-type: text/html; charset=UTF-8");
/* Set internal character encoding to UTF-8 */
mb_internal_encoding("UTF-8");
define('UPLOAD_PATH', './Uploads');
define('NO_CACHE_RUNTIME', true);//debug
define('STRIP_RUNTIME_SPACE',false);
 // 定义ThinkPHP框架路径
define('THINK_PATH', './ThinkPHP/');
//定义模板路径
define('FRONT_PATH', './Front');
//定义项目名称和路径
define('APP_NAME', 'Admin');
define('APP_PATH', './Admin/');
//定义配置文件路径
define('APP_CONFIG', './config');
//开启Debug模式
define('APP_DEBUG',true);
//加载自定义类库
require("./company/company.php");
// 加载框架入口文件
require(THINK_PATH."ThinkPHP.php");

