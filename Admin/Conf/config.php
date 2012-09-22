<?php
$database = require (APP_CONFIG.'/db.config.php');
$cache = require (APP_CONFIG.'/app.config.php');
$cms = require (APP_CONFIG.'/cms.config.php');
$config = array(
    'DEFAULT_THEME' => 'default',
    'LOG_RECORD' => true,
    'LOG_RECORD_LEVEL' => array('EMERG','ALERT','CRIT','ERR', 'WARN', 'NOTICE', 'INFO','DEBUG'),
	'APP_DEBUG' => true
);

return array_merge($database, $cache, $cms, $config);
?>