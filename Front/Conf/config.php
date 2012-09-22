<?php
$database = require (APP_CONFIG.'/db.config.php');
$cache = require (APP_CONFIG.'/app.config.php');
$config = array(
      'APP_DEBUG' => true
);
return array_merge($database, $cache, $config);
?>