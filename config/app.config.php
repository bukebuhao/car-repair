<?php
/** 
*  数据库配置文件
*
* @package      	companycms
* @author     zxz (QQ:396774497)
* @copyright  Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
* @license    http://www.tengzhiinfo.com/license.txt
* @version    $ID: db.config.php 2011-7-28 Administrator$
*/


return array(
    'APP_DEBUG' => true,
    'APP_VESION' => 1.0,
    'DATA_CACHE_TYPE' => 'file',
    'DATA_CACHE_TIME' => 600,
    'DATA_CACHE_PATH' => './Data/cache/',
    'URL_ROUTER_ON' => false,
    'URL_DISPATCH_ON' => false,
    'URL_MODEL' => 1,
    'URL_PATHINFO_MODEL' => 3,
    'URL_PATHINFO_DEPR' => '/',
    'URL_HTML_SUFFIX' => '.html',
    'TMPL_CACHE_ON' => false,
    'TOKEN_NAME' => 'test_token',
    'TMPL_CACHE_TIME' => '',
    'DEFAULT_THEME' => 'default',
    'TOKEN_ON' => true,
     'TMPL_PARSE_STRING'  =>array(
       '__UPLOAD__' => '/4s/Uploads', // 增加新的上传路径替换规则
      )
);