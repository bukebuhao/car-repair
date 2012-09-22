<?php
// 示例的全局数据库配置文件
return array(
    //'URL_MODEL'=>3, // 如果你的环境不支持PATHINFO 请设置为3
    'URL_MODEL' => 1,
    'URL_PATHINFO_MODEL' => 3,
    'URL_PATHINFO_DEPR' => '/',
    'DB_TYPE'=>'mysql',
    'DB_HOST'=>'localhost',
    'DB_NAME'=>'test',
    'DB_USER'=>'root',
    'DB_PWD'=>'root',
    'DB_PORT'=>'3306',
    'DB_PREFIX'=>'think_',
);
?>