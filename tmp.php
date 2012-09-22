<?php

function U($url,$params=array(),$redirect=false,$suffix=true) {
	if(0===strpos($url,'/'))
	$url   =  substr($url,1);
	if(!strpos($url,'://'))
	$url   =  APP_NAME.'://'.$url;
	if(stripos($url,'@?')) {
		$url   =  str_replace('@?','@think?',$url);
	}elseif(stripos($url,'@')) {
		$url   =  $url.MODULE_NAME;
	}
	$array   =  parse_url($url);
	$app      =  isset($array['scheme'])?$array['scheme']  :APP_NAME;
	$route    =  isset($array['user'])?$array['user']:'';
	if(defined('GROUP_NAME') &&strcasecmp(GROUP_NAME,C('DEFAULT_GROUP')))
	$group=  GROUP_NAME;
	if(isset($array['path'])) {
		$action  =  substr($array['path'],1);
		if(!isset($array['host'])) {
			$module = MODULE_NAME;
		}else{
			if(strpos($array['host'],'-')) {
				list($group,$module) = explode('-',$array['host']);
			}else{
				$module = $array['host'];
			}
		}
	}else{
		$module = MODULE_NAME;
		$action   =  $array['host'];
	}
	if(isset($array['query'])) {
		parse_str($array['query'],$query);
		$params = array_merge($query,$params);
	}
	if(C('URL_DISPATCH_ON') &&C('URL_MODEL')>0) {
		$depr = C('URL_PATHINFO_MODEL')==2?C('URL_PATHINFO_DEPR'):'/';
		$str    =   $depr;
		foreach ((array)$params as $var=>$val)
		$str .= $var.$depr.$val.$depr;
		$str = substr($str,0,-1);
		$group   = isset($group)?$group.$depr:'';
		if(!empty($route)) {
			$url    =   str_replace(APP_NAME,$app,__APP__).'/'.$group.$route.$str;
		}else{
			$url    =   str_replace(APP_NAME,$app,__APP__).'/'.$group.$module.$depr.$action.$str;
		}
		if($suffix &&C('URL_HTML_SUFFIX'))
		$url .= C('URL_HTML_SUFFIX');
	}else{
		$params =   http_build_query($params);
		if(isset($group)) {
			$url    =   str_replace(APP_NAME,$app,__APP__).'?'.C('VAR_GROUP').'='.$group.'&'.C('VAR_MODULE').'='.$module.'&'.C('VAR_ACTION').'='.$action.'&'.$params;
		}else{
			$url    =   str_replace(APP_NAME,$app,__APP__).'?'.C('VAR_MODULE').'='.$module.'&'.C('VAR_ACTION').'='.$action.'&'.$params;
		}
	}
	if($redirect)
	redirect($url);
	else
	return $url;
}
function parse_name($name,$type=0) {
	if($type) {
		return ucfirst(preg_replace("/_([a-zA-Z])/e","strtoupper('\\1')",$name));
	}else{
		$name = preg_replace("/[A-Z]/","_\\0",$name);
		return strtolower(trim($name,"_"));
	}
}
function halt($error) {
	if(IS_CLI)   exit ($error);
	$e = array();
	if(C('APP_DEBUG')){
		if(!is_array($error)) {
			$trace = debug_backtrace();
			$e['message'] = $error;
			$e['file'] = $trace[0]['file'];
			$e['class'] = $trace[0]['class'];
			$e['function'] = $trace[0]['function'];
			$e['line'] = $trace[0]['line'];
			$traceInfo='';
			$time = date("y-m-d H:i:m");
			foreach($trace as $t)
			{
				$traceInfo .= '['.$time.'] '.$t['file'].' ('.$t['line'].') ';
				$traceInfo .= $t['class'].$t['type'].$t['function'].'(';
				$traceInfo .= implode(', ',$t['args']);
				$traceInfo .=")<br/>";
			}
			$e['trace']  = $traceInfo;
		}else {
			$e = $error;
		}
		include C('TMPL_EXCEPTION_FILE');
	}
	else
	{
		$error_page =   C('ERROR_PAGE');
		if(!empty($error_page)){
			redirect($error_page);
		}else {
			if(C('SHOW_ERROR_MSG'))
			$e['message'] =  is_array($error)?$error['message']:$error;
			else
			$e['message'] = C('ERROR_MESSAGE');
			include C('TMPL_EXCEPTION_FILE');
		}
	}
	exit;
}
function redirect($url,$time=0,$msg='')
{
	$url = str_replace(array("\n","\r"),'',$url);
	if(empty($msg))
	$msg    =   "系统将在{$time}秒之后自动跳转到{$url}！";
	if (!headers_sent()) {
		if(0===$time) {
			header("Location: ".$url);
		}else {
			header("refresh:{$time};url={$url}");
			echo($msg);
		}
		exit();
	}else {
		$str    = "<meta http-equiv='Refresh' content='{$time};URL={$url}'>";
		if($time!=0)
		$str   .=   $msg;
		exit($str);
	}
}
function throw_exception($msg,$type='ThinkException',$code=0)
{
	if(IS_CLI)   exit($msg);
	if(class_exists($type,false))
	throw new $type($msg,$code,true);
	else
	halt($msg);
}
function debug_start($label='')
{
	$GLOBALS[$label]['_beginTime'] = microtime(TRUE);
	if ( MEMORY_LIMIT_ON )  $GLOBALS[$label]['_beginMem'] = memory_get_usage();
}
function debug_end($label='')
{
	$GLOBALS[$label]['_endTime'] = microtime(TRUE);
	echo '<div style="text-align:center;width:100%">Process '.$label.': Times '.number_format($GLOBALS[$label]['_endTime']-$GLOBALS[$label]['_beginTime'],6).'s ';
	if ( MEMORY_LIMIT_ON )  {
		$GLOBALS[$label]['_endMem'] = memory_get_usage();
		echo ' Memories '.number_format(($GLOBALS[$label]['_endMem']-$GLOBALS[$label]['_beginMem'])/1024).' k';
	}
	echo '</div>';
}
function dump($var,$echo=true,$label=null,$strict=true)
{
	$label = ($label===null) ?'': rtrim($label) .' ';
	if(!$strict) {
		if (ini_get('html_errors')) {
			$output = print_r($var,true);
			$output = "<pre>".$label.htmlspecialchars($output,ENT_QUOTES)."</pre>";
		}else {
			$output = $label ." : ".print_r($var,true);
		}
	}else {
		ob_start();
		var_dump($var);
		$output = ob_get_clean();
		if(!extension_loaded('xdebug')) {
			$output = preg_replace("/\]\=\>\n(\s+)/m","] => ",$output);
			$output = '<pre>'.$label.htmlspecialchars($output,ENT_QUOTES).'</pre>';
		}
	}
	if ($echo) {
		echo($output);
		return null;
	}else
	return $output;
}
function get_instance_of($name,$method='',$args=array())
{
	static $_instance = array();
	$identify   =   empty($args)?$name.$method:$name.$method.to_guid_string($args);
	if (!isset($_instance[$identify])) {
		if(class_exists($name)){
			$o = new $name();
			if(method_exists($o,$method)){
				if(!empty($args)) {
					$_instance[$identify] = call_user_func_array(array(&$o,$method),$args);
				}else {
					$_instance[$identify] = $o->$method();
				}
			}
			else
			$_instance[$identify] = $o;
		}
		else
		halt(L('_CLASS_NOT_EXIST_').':'.$name);
	}
	return $_instance[$identify];
}
function __autoload($name)
{
	if(alias_import($name)) return ;
	if(substr($name,-5)=="Model") {
		require_cache(LIB_PATH.'Model/'.$name.'.class.php');
	}elseif(substr($name,-6)=="Action"){
		require_cache(LIB_PATH.'Action/'.$name.'.class.php');
	}else {
		if(C('APP_AUTOLOAD_PATH')) {
			$paths  =   explode(',',C('APP_AUTOLOAD_PATH'));
			foreach ($paths as $path){
				if(import($path.$name)) {
					return ;
				}
			}
		}
	}
	return ;
}
function require_cache($filename)
{
	static $_importFiles = array();
	$filename   =  realpath($filename);
	if (!isset($_importFiles[$filename])) {
		if(file_exists_case($filename)){
			require $filename;
			$_importFiles[$filename] = true;
		}
		else
		{
			$_importFiles[$filename] = false;
		}
	}
	return $_importFiles[$filename];
}
function file_exists_case($filename) {
	if(is_file($filename)) {
		if(IS_WIN &&C('APP_FILE_CASE')) {
			if(basename(realpath($filename)) != basename($filename))
			return false;
		}
		return true;
	}
	return false;
}
function import($class,$baseUrl = '',$ext='.class.php')
{
	static $_file = array();
	static $_class = array();
	$class    =   str_replace(array('.','#'),array('/','.'),$class);
	if(''=== $baseUrl &&false === strpos($class,'/')) {
		return alias_import($class);
	}
	if(isset($_file[$class.$baseUrl]))
	return true;
	else
	$_file[$class.$baseUrl] = true;
	$class_strut = explode("/",$class);
	if(empty($baseUrl)) {
		if('@'==$class_strut[0] ||APP_NAME == $class_strut[0] ) {
			$baseUrl   =  dirname(LIB_PATH);
			$class =  str_replace(array(APP_NAME.'/','@/'),LIB_DIR.'/',$class);
		}elseif(in_array(strtolower($class_strut[0]),array('think','org','com'))) {
			$baseUrl =  THINK_PATH.'/Lib/';
		}else {
			$class    =   substr_replace($class,'',0,strlen($class_strut[0])+1);
			$baseUrl =  APP_PATH.'/../'.$class_strut[0].'/'.LIB_DIR.'/';
		}
	}
	if(substr($baseUrl,-1) != "/")    $baseUrl .= "/";
	$classfile = $baseUrl .$class .$ext;
	if($ext == '.class.php'&&is_file($classfile)) {
		$class = basename($classfile,$ext);
		if(isset($_class[$class]))
		throw_exception(L('_CLASS_CONFLICT_').':'.$_class[$class].' '.$classfile);
		$_class[$class] = $classfile;
	}
	return require_cache($classfile);
}
function load($name,$baseUrl='',$ext='.php') {
	$name    =   str_replace(array('.','#'),array('/','.'),$name);
	if(empty($baseUrl)) {
		if(0 === strpos($name,'@/')) {
			$baseUrl   =  APP_PATH.'/Common/';
			$name =  substr($name,2);
		}else{
			$baseUrl =  THINK_PATH.'/Common/';
		}
	}
	if(substr($baseUrl,-1) != "/")    $baseUrl .= "/";
	include $baseUrl .$name .$ext;
}
function vendor($class,$baseUrl = '',$ext='.php')
{
	if(empty($baseUrl))  $baseUrl    =   VENDOR_PATH;
	return import($class,$baseUrl,$ext);
}
function alias_import($alias,$classfile='') {
	static $_alias   =  array();
	if(''!== $classfile) {
		$_alias[$alias]  = $classfile;
		return ;
	}
	if(is_string($alias)) {
		if(isset($_alias[$alias]))
		return require_cache($_alias[$alias]);
	}elseif(is_array($alias)){
		foreach ($alias as $key=>$val)
		$_alias[$key]  =  $val;
		return ;
	}
	return false;
}
function D($name='',$app='')
{
	static $_model = array();
	if(empty($name)) return new Model;
	if(empty($app))   $app =  C('DEFAULT_APP');
	if(isset($_model[$app.$name]))
	return $_model[$app.$name];
	$OriClassName = $name;
	if(strpos($name,C('APP_GROUP_DEPR'))) {
		$array   =  explode(C('APP_GROUP_DEPR'),$name);
		$name = array_pop($array);
		$className =  $name.'Model';
		import($app.'.Model.'.implode('.',$array).'.'.$className);
	}else{
		$className =  $name.'Model';
		import($app.'.Model.'.$className);
	}
	if(class_exists($className)) {
		$model = new $className();
		$_model[$app.$OriClassName] =  $model;
		return $model;
	}else {
		throw_exception($className.L('_MODEL_NOT_EXIST_'));
	}
}
function M($name='',$class='Model') {
	static $_model = array();
	if(!isset($_model[$name.'_'.$class]))
	$_model[$name.'_'.$class]   = new $class($name);
	return $_model[$name.'_'.$class];
}
function A($name,$app='@')
{
	static $_action = array();
	if(isset($_action[$app.$name]))
	return $_action[$app.$name];
	$OriClassName = $name;
	if(strpos($name,C('APP_GROUP_DEPR'))) {
		$array   =  explode(C('APP_GROUP_DEPR'),$name);
		$name = array_pop($array);
		$className =  $name.'Action';
		import($app.'.Action.'.implode('.',$array).'.'.$className);
	}else{
		$className =  $name.'Action';
		import($app.'.Action.'.$className);
	}
	if(class_exists($className)) {
		$action = new $className();
		$_action[$app.$OriClassName] = $action;
		return $action;
	}else {
		return false;
	}
}
function R($module,$action,$app='@') {
	$class = A($module,$app);
	if($class)
	return call_user_func(array(&$class,$action));
	else
	return false;
}
function L($name=null,$value=null) {
	static $_lang = array();
	if(empty($name)) return $_lang;
	if (is_string($name) )
	{
		$name = strtoupper($name);
		if (is_null($value))
		return isset($_lang[$name]) ?$_lang[$name] : $name;
		$_lang[$name] = $value;
		return;
	}
	if (is_array($name))
	$_lang = array_merge($_lang,array_change_key_case($name,CASE_UPPER));
	return;
}
function C($name=null,$value=null)
{
	static $_config = array();
	if(empty($name)) return $_config;
	if (is_string($name))
	{
		$name = strtolower($name);
		if (!strpos($name,'.')) {
			if (is_null($value))
			return isset($_config[$name])?$_config[$name] : null;
			$_config[$name] = $value;
			return;
		}
		$name = explode('.',$name);
		if (is_null($value))
		return isset($_config[$name[0]][$name[1]]) ?$_config[$name[0]][$name[1]] : null;
		$_config[$name[0]][$name[1]] = $value;
		return;
	}
	if(is_array($name))
	return $_config = array_merge($_config,array_change_key_case($name));
	return null;
}
function tag($name,$params=array()) {
	$tags   =  C('_tags_.'.$name);
	if($tags) {
		foreach ($tags   as $key=>$call){
			if(is_callable($call))
			$result = call_user_func_array($call,$params);
		}
		return $result;
	}
	return false;
}
function B($name) {
	$class = $name.'Behavior';
	require_cache(LIB_PATH.'Behavior/'.$class.'.class.php');
	$behavior   =  new $class();
	$behavior->run();
}
function W($name,$data=array(),$return=false) {
	$class = $name.'Widget';
	require_cache(LIB_PATH.'Widget/'.$class.'.class.php');
	if(!class_exists($class))
	throw_exception(L('_CLASS_NOT_EXIST_').':'.$class);
	$widget  =  Think::instance($class);
	$content = $widget->render($data);
	if($return)
	return $content;
	else
	echo $content;
}
function S($name,$value='',$expire='',$type='') {
	static $_cache = array();
	alias_import('Cache');
	$cache  = Cache::getInstance($type);
	if(''!== $value) {
		if(is_null($value)) {
			$result =   $cache->rm($name);
			if($result)   unset($_cache[$type.'_'.$name]);
			return $result;
		}else{
			$cache->set($name,$value,$expire);
			$_cache[$type.'_'.$name]     =   $value;
		}
		return ;
	}
	if(isset($_cache[$type.'_'.$name]))
	return $_cache[$type.'_'.$name];
	$value      =  $cache->get($name);
	$_cache[$type.'_'.$name]     =   $value;
	return $value;
}
function F($name,$value='',$path=DATA_PATH) {
	static $_cache = array();
	$filename   =   $path.$name.'.php';
	if(''!== $value) {
		if(is_null($value)) {
			return unlink($filename);
		}else{
			$dir   =  dirname($filename);
			if(!is_dir($dir))  mkdir($dir);
			return file_put_contents($filename,"<?php\nreturn ".var_export($value,true).";\n?>");
		}
	}
	if(isset($_cache[$name])) return $_cache[$name];
	if(is_file($filename)) {
		$value   =  include $filename;
		$_cache[$name]   =   $value;
	}else{
		$value  =   false;
	}
	return $value;
}
function to_guid_string($mix)
{
	if(is_object($mix) &&function_exists('spl_object_hash')) {
		return spl_object_hash($mix);
	}elseif(is_resource($mix)){
		$mix = get_resource_type($mix).strval($mix);
	}else{
		$mix = serialize($mix);
	}
	return md5($mix);
}
function compile($filename,$runtime=false) {
	$content = file_get_contents($filename);
	if(true === $runtime)
	$content = preg_replace('/\/\/\[RUNTIME\](.*?)\/\/\[\/RUNTIME\]/s','',$content);
	$content = substr(trim($content),5);
	if('?>'== substr($content,-2))
	$content = substr($content,0,-2);
	return $content;
}
function strip_whitespace($content) {
	$stripStr = '';
	$tokens =   token_get_all ($content);
	$last_space = false;
	for ($i = 0,$j = count ($tokens);$i <$j;$i++)
	{
		if (is_string ($tokens[$i]))
		{
			$last_space = false;
			$stripStr .= $tokens[$i];
		}
		else
		{
			switch ($tokens[$i][0])
			{
				case T_COMMENT:
				case T_DOC_COMMENT:
					break;
				case T_WHITESPACE:
					if (!$last_space)
					{
						$stripStr .= ' ';
						$last_space = true;
					}
					break;
				default:
					$last_space = false;
					$stripStr .= $tokens[$i][1];
			}
		}
	}
	return $stripStr;
}
function array_define($array) {
	$content = '';
	foreach($array as $key=>$val) {
		$key =  strtoupper($key);
		if(in_array($key,array('THINK_PATH','APP_NAME','APP_PATH','RUNTIME_PATH','RUNTIME_ALLINONE','THINK_MODE')))
		$content .= 'if(!defined(\''.$key.'\')) ';
		if(is_int($val) ||is_float($val)) {
			$content .= "define('".$key."',".$val.");";
		}elseif(is_bool($val)) {
			$val = ($val)?'true':'false';
			$content .= "define('".$key."',".$val.");";
		}elseif(is_string($val)) {
			$content .= "define('".$key."','".addslashes($val)."');";
		}
	}
	return $content;
}
function mk_dir($dir,$mode = 0755)
{
	if (is_dir($dir) ||@mkdir($dir,$mode)) return true;
	if (!mk_dir(dirname($dir),$mode)) return false;
	return @mkdir($dir,$mode);
}
function auto_charset($fContents,$from,$to){
	$from   =  strtoupper($from)=='UTF8'?'utf-8':$from;
	$to       =  strtoupper($to)=='UTF8'?'utf-8':$to;
	if( strtoupper($from) === strtoupper($to) ||empty($fContents) ||(is_scalar($fContents) &&!is_string($fContents)) ){
		return $fContents;
	}
	if(is_string($fContents) ) {
		if(function_exists('mb_convert_encoding')){
			return mb_convert_encoding ($fContents,$to,$from);
		}elseif(function_exists('iconv')){
			return iconv($from,$to,$fContents);
		}else{
			return $fContents;
		}
	}
	elseif(is_array($fContents)){
		foreach ( $fContents as $key =>$val ) {
			$_key =     auto_charset($key,$from,$to);
			$fContents[$_key] = auto_charset($val,$from,$to);
			if($key != $_key )
			unset($fContents[$key]);
		}
		return $fContents;
	}
	else{
		return $fContents;
	}
}
function xml_encode($data,$encoding='utf-8',$root="think") {
	$xml = '<?xml version="1.0" encoding="'.$encoding.'"?>';
	$xml.= '<'.$root.'>';
	$xml.= data_to_xml($data);
	$xml.= '</'.$root.'>';
	return $xml;
}
function data_to_xml($data) {
	if(is_object($data)) {
		$data = get_object_vars($data);
	}
	$xml = '';
	foreach($data as $key=>$val) {
		is_numeric($key) &&$key="item id=\"$key\"";
		$xml.="<$key>";
		$xml.=(is_array($val)||is_object($val))?data_to_xml($val):$val;
		list($key,)=explode(' ',$key);
		$xml.="</$key>";
	}
	return $xml;
}
function cookie($name,$value='',$option=null)
{
	$config = array(
'prefix'=>C('COOKIE_PREFIX'),
'expire'=>C('COOKIE_EXPIRE'),
'path'=>C('COOKIE_PATH'),
'domain'=>C('COOKIE_DOMAIN'),
	);
	if (!empty($option)) {
		if (is_numeric($option))
		$option = array('expire'=>$option);
		elseif( is_string($option) )
		parse_str($option,$option);
		array_merge($config,array_change_key_case($option));
	}
	if (is_null($name)) {
		if (empty($_COOKIE)) return;
		$prefix = empty($value)?$config['prefix'] : $value;
		if (!empty($prefix))
		{
			foreach($_COOKIE as $key=>$val) {
				if (0 === stripos($key,$prefix)){
					setcookie($_COOKIE[$key],'',time()-3600,$config['path'],$config['domain']);
					unset($_COOKIE[$key]);
				}
			}
		}
		return;
	}
	$name = $config['prefix'].$name;
	if (''===$value){
		return isset($_COOKIE[$name]) ?unserialize($_COOKIE[$name]) : null;
	}else {
		if (is_null($value)) {
			setcookie($name,'',time()-3600,$config['path'],$config['domain']);
			unset($_COOKIE[$name]);
		}else {
			$expire = !empty($config['expire'])?time()+intval($config['expire']):0;
			setcookie($name,serialize($value),$expire,$config['path'],$config['domain']);
			$_COOKIE[$name] = serialize($value);
		}
	}
}
function upload($model='',$path = 1,$fileSize = 0,$thumbStatus = 1,$thumbSize = 0,$allowExts = 0,$attachFields = 'attach_file')
{
	if(attachTrue($_FILES[$attachFields]['name'])){
		$globalConfig = getContent('cms.config.php','.');
		$globalAttachSize = intval($globalConfig['global_attach_size']);
		$globalAttachSuffix = $globalConfig['global_attach_suffix'];
		$dot = '/';
		$setFolder = empty($model) ?'file/': $model .$dot ;
		$setUserPath = empty($path) ?'': makeFolderName($path) ;
		$finalPath = UPLOAD_PATH.$dot.$setFolder.$setUserPath;
		if(!is_dir($finalPath)){
			@mk_dir($finalPath);
		}
		import("ORG.Net.UploadFile");
		$upload = new UploadFile();
		$upload->maxSize = empty($fileSize) ?$globalAttachSize : intval($fileSize) ;
		$upload->allowExts = empty($allowExts) ?explode(',',$globalAttachSuffix) : explode(',',$allowExts) ;
		$upload->savePath = $finalPath;
		$upload->saveRule = 'uniqid';
		switch ($model){
			case 'News':
				$globalThumbStatus = intval($globalConfig['news_thumb_status']);;
				$globalThumbSize = trim($globalConfig['news_thumb_size']);
				break;
			case 'Product':
				$globalThumbStatus = intval($globalConfig['product_thumb_status']);;
				$globalThumbSize = trim($globalConfig['product_thumb_size']);
				break;
			case 'Download':
				$globalThumbStatus = intval($globalConfig['download_thumb_status']);;
				$globalThumbSize = trim($globalConfig['download_thumb_size']);
				break;
			default:
				$globalThumbStatus = intval($globalConfig['global_thumb_status']);;
				$globalThumbSize = trim($globalConfig['global_thumb_size']);
				break;
		}
		$globalThumbSizeExplode = explode(',',$globalThumbSize);
		$userThumbSizeExplode = explode(',',$thumbSize);
		if(!empty($globalThumbStatus) &&!empty($thumbStatus)){
			$upload->thumb = true;
		}else{
			$upload->thumb = false;
		}
		if(!empty($thumbStatus) &&!empty($thumbSize)){
			$upload->thumbMaxWidth = $userThumbSizeExplode[0] ;
			$upload->thumbMaxHeight = $userThumbSizeExplode[1] ;
		}else{
			$upload->thumbMaxWidth = $globalThumbSizeExplode[0] ;
			$upload->thumbMaxHeight = $globalThumbSizeExplode[1] ;
		}
		$upload->thumbPrefix = '';
		$upload->thumbSuffix = '_s';
		if(!$upload->upload())
		{
			echo ($upload->getErrorMsg());
		}else
		{
			return $upload->getUploadFileInfo();
		}
	}
}
function isEnglist($param)
{
	if (!eregi("^[A-Z0-9]{1,26}$",$param)) {
		return false;
	}else {
		return true;
	}
}
function safe_b64encode($string)
{
	$data = base64_encode($string);
	$data = str_replace(array('+','/','='),array('-','_',''),$data);
	return $data;
}
function safe_b64decode($string)
{
	$data = str_replace(array('-','_'),array('+','/'),$string);
	$mod4 = strlen($data) %4;
	if ($mod4)
	{
		$data .= substr('====',$mod4);
	}
	return base64_decode($data);
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
function splitThumb($attach)
{
	$splitAttach = explode('.',$attach);
	$thumb =  $splitAttach[0].'_s.'.$splitAttach[1];
	return $thumb;
}
function formatAttachPath($path,$find = './Uploads/',$replace ='')
{
	if(!empty($path)){
		return str_replace($find,$replace,$path);
	}
}
function string2checked($sring,$param,$split = ',')
{
	$splitParam = explode($split,$sring);
	if (in_array($param,$splitParam)) $result = ' checked=checked';
	return $result;
}
function array2string($data = array(),$split = ',')
{
	if (is_array($data)) {
		return implode($split,$data);
	}else{
		return $data;
	}
}
function selected($string,$param =1,$type = 'select')
{
	$returnString = '';
	if ($string == $param)
	{
		$returnString = $type == 'select'?'selected="selected"': 'checked="checked"';
	}
	return $returnString;
}
function a2bc($a,$param =1,$b = '',$c = '')
{
	$returnString = $a == $param ?$b : $c;
	return $returnString;
}
function disable($param,$typeParam =1,$stringParam = array(' disabled="disabled"',''))
{
	return $param == $typeParam ?$stringParam[0] : '';
}
function getMethod()
{
	return  strtolower($_SERVER['REQUEST_METHOD']);
}
function getDir($dirname)
{
	$files = array();
	if(is_dir($dirname))
	{
		$fileHander = opendir($dirname);
		while (($file = readdir($fileHander)) !== false)
		{
			$filepath = $dirname .'/'.$file;
			if (strcmp($file,'.') == 0 ||strcmp($file,'..') == 0 ||is_file($filepath))
			{
				continue;
			}
			$files[] = auto_charset($file,'GBK','UTF8');;
		}
		closedir($fileHander);
	}
	else
	{
		$files = false;
	}
	return $files;
}
function getFile($dirname)
{
	$files = array();
	if(is_dir($dirname))
	{
		$fileHander = opendir($dirname);
		while (($file = readdir($fileHander)) !== false)
		{
			$filepath = $dirname .'/'.$file;
			if (strcmp($file,'.') == 0 ||strcmp($file,'..') == 0 ||is_dir($filepath) )
			{
				continue;
			}
			$files[] = auto_charset($file,'GBK','UTF8');;
		}
		closedir($fileHander);
	}
	else
	{
		$files = false;
	}
	return $files;
}
function formatQuery($string)
{
	return $string;
}
function makeFolderName($type =0,$prefix=1)
{
	$setPrefix = empty($prefix) ?'': '/';
	switch ($type){
		case 1: $result = date('Ym').$setPrefix ;break ;
		case 2: $result = date('Y-m').$setPrefix ;break ;
		case 3: $result = date('Ymd').$setPrefix ;break ;
		case 4: $result = date('Y-m-d').$setPrefix ;break ;
		case 5: $result = date('Y').$setPrefix ;break ;
		default: $result = date('Ym').$setPrefix ;break ;
	}
	return $result;
}
function attachTrue($fields,$trueNum = 0)
{
	if(is_array($fields)){
		foreach ($fields as $value) {
			if(!empty($value)){
				$trueNum = $trueNum+1;
			}
		}
	}else {
		if(empty($fields)){
			$trueNum = 0;
		}else {
			$trueNum = 1;
		}
	}
	return $trueNum;
}
function statusIcon($data = 1,$status = 1,$folder = 0,$icon = 'hidden.png',$alt = '显示',$condition = 'eq'){
	$strStart = '<img src="';
	$strMiddle = $folder.'/Public/Admin/'.$icon;
	$strEnd = '" alt="'.$alt.'" align="absmiddle" />';
	if ($condition == 'eq'){
		if($data == $status){
			return $strStart.$strMiddle.$strEnd;
		}
	}elseif($condition == 'neq'){
		if($data != (int)$status){
			return $strStart .$strMiddle .$strEnd;
		}
	}
}
function attachStatus($data = 1,$status = 1,$folder = 0,$icon = 'hidden.png',$alt = '显示'){
	$string = '<img src="'.$folder.'/Public/Admin/'.$icon.'" alt="'.$alt.'" align="absmiddle" />';
	switch ($status){
		case '1':
			$returnString = !empty($data) ?$string : '';
			break;
		case '0':
			$returnString = empty($data) ?$string : '';
			break;
		default:
			$returnString = $data == $status ?$string : '';
			break;
	}
	return $returnString;
}
function str2time($string,$time = 0){
	if(!empty($string)){
		return strtotime($string);
	}
}
function createStyle($data,$style = array(),$styleArray = array())
{
	$dataStyle = '';
	if($data){
		if(strtolower($data['style_color']) != '#ffffff'&&!empty($data['style_color'])){
			$style['color'] = $data['style_color'];
			$styleArray[] = 'color:'.$data['style_color'];
		}
		if(!empty($data['style_bold'])){
			$style['bold'] = $data['style_bold'];
			$styleArray[] = 'font-weight:bold';
		}
		if(!empty($data['style_underline'])){
			$style['underline'] = $data['style_underline'];
			$styleArray[] = 'TEXT-DECORATION: underline';
		}
		$dataStyle['title_style'] = empty($styleArray) ?'': implode(';',$styleArray);
		$dataStyle['title_style_serialize'] = empty($style) ?'': serialize($style);
	}
	return $dataStyle;
}
function string2Checkbox($string = '',$emptyString = '未定义'){
	if(empty($string)){
		$resultString = $emptyString;
	}else{
		$stringSplit = explode(',',$string);
		foreach ($stringSplit as $row){
			$resultString .= '<input name="run_system[]" type="checkbox" id="run_system[]" value="'.$row.'"/>'.$row;
		}
	}
	return $resultString;
}
function string2checkboxSelect($string = '',$param = '',$emptyString = '未定义')
{
	if(empty($string)){
		$resultString = $emptyString;
	}else{
		$stringSplit = explode(',',$string);
		foreach ($stringSplit as $row){
			if(in_array($row,explode(',',$param))){
				$resultString.='<input name="run_system[]" type="checkbox" id="run_system[]" value="'.$row.'" checked="checked"/>'.$row;
			}else{
				$resultString.='<input name="run_system[]" type="checkbox" id="run_system[]" value="'.$row.'"/>'.$row;
			}
		}
	}
	return $resultString;
}
function setOrder($orderFields = 0,$selectField = 'id',$orderType = 'DESC',$join = NULL){
	$orderValue = empty($join) ?'id': 'a.id';
	foreach ((array)$orderFields as $value){
		if(is_array($value)){
			if($value[0] == $selectField){
				$orderValue = $value[1];
			}
		}else{
			if($value == $selectField){
				$orderValue = $value;
			}
		}
	}
	$orderByValue = empty($orderValue) ?'id': $orderValue ;
	$orderByType = empty($orderType) ?'DESC': $orderType ;
	return $orderByValue.' '.$orderByType;
}
function setTime($time = 0,$time1 = 0){
	$createTime = empty($time) ?0 : strtotime($time) ;
	$createTime1 = strtotime($time1) ;
	if(!empty($time1)){
		return $createTime.','.$createTime1;
	}
}
function setViewCount($count = 0,$count1 = 0)
{
	$viewCount = empty($count) ?0 : $count ;
	$viewCount1 = $count1 ;
	if(!empty($count1)){
		return $viewCount.','.$viewCount1;
	}
}
function styleSelected($titelStyle = 0,$type = 'color',$returnString = 'checked="checked"')
{
	$result = '';
	if(!empty($titelStyle)){
		$unserialize = unserialize($titelStyle);
		switch ($type) {
			case 'color':
				$result = empty($unserialize['color']) ?'#ffffff': $unserialize['color'];
				break;
			case 'bold':
				$result = empty($unserialize['bold']) ?'': $returnString ;
				break;
			case 'underline':
				$result = empty($unserialize['underline']) ?'': $returnString ;
				break;
			default:
				break;
		}
	}
	return $result;
}
function formatTags($data)
{
	if(!empty($data)){
		$tagCount = 0;
		$tag = explode(',',$data);
		foreach ($tag as $value){
			if(!empty($value)){
				$tags[] = $value;
				$tagCount ++;
				if($tagCount >4) {
					unset($tag);
					break;
				}
			}
		}
		return implode(',',$tags);
	}else {
		return '';
	}
}
function tagsGet($tags,$module = '')
{
	if(!empty($tags)){
		$str = '';
		$format = explode(',',$tags);
		foreach ((array)$format as $row){
			$str .= '<a href="'.U("Tags/getList",array('module'=>$module,'name'=>urlencode($row))).'" target="_blank">'.$row.'</a> ';
		}
		echo $str;
	}
}
function fileExit($file)
{
	return file_exists($file) ?true : false ;
}
function explodeRole($permission,$inData = '',$field = 'role_permission')
{
	if(!empty($permission)){
		$str = '';
		$pmArray = explode('|',$permission);
		foreach ((array)$pmArray as $row){
			$subRow = explode('=',$row);
			if(in_array($subRow[1],explode(',',$inData))){
				$str .= '<span style="float:left; width:20%;"><input name="'.$field.'[]" type="checkbox" id="'.$field.'[]" value="'.trim($subRow[1]).'" class="checkbox" checked="checked"/>'.trim($subRow[0]).'</span>';
			}else{
				$str .= '<span style="float:left; width:20%;"><input name="'.$field.'[]" type="checkbox" id="'.$field.'[]" value="'.trim($subRow[1]).'" class="checkbox"/>'.trim($subRow[0]).'</span>';
			}
		}
		return $str;
	}
}
function splitsql($sql) {
	$sql = str_replace("\r","\n",$sql);
	$returnSql = array();
	$num = 0;
	$queryArray = explode(";\n",trim($sql));
	unset($sql);
	foreach($queryArray as $query) {
		$queries = explode("\n",trim($query));
		foreach($queries as $query) {
			$returnSql[$num] .= $query[0] == "#"||$query[0].$query[1] == '--'?NULL : $query;
		}
		$num ++;
	}
	return($returnSql);
}
if(!function_exists('file_put_contents')) {
	function file_put_contents($filename,$data) {
		if($fp = @fopen($filename,'w') === false)
		{
			exit($filename.'if not writeable');
		}else {
			$bytes = fwrite($fp,$contents);
			fclose($fp);
		}
	}
}
function writeCache($name = NULL,$data = NULL,$order = '',$where = '',$path = './CmsData/')
{
	if(empty($data)){
		$dao = M($name);
		$getData = $dao->where($where)->order($order)->findAll();
		$fileName = strtolower($name);
		$writeData = "<?php\n/** \n* cache.{$fileName}.php\n*\n* @package    shuguangCMS\n* @author     shuguang QQ:5565907 <web@sgcms.cn>\n* @copyright  Copyright (c) 2008-2010  (http://www.sgcms.cn)\n* @license    http://www.sgcms.cn/license.txt\n   \n*/\n\nif (!defined('SHUGUANGCMS')) exit();\n\nreturn ";
		$writeData .= var_export($getData,true);
		$writeData .= ';';
	}else{
		$writeData = $data;
	}
	$writeFile = 'cache.'.$fileName.'.php';
	@file_put_contents($path .$writeFile,$writeData);
	return $writeData;
}
function configCache($id = 1,$data = NULL,$file = NULL,$path = NULL)
{
	$writePath = empty($path) ?'./': $path ;
	$writeFile = empty($file) ?'fcms.config.php': $file ;
	$writeDataHeader = "<?php\n/*** \n* cms.config.php\n*\n* @package    shuguangCMS\n* @author     shuguang QQ:5565907 <web@sgcms.cn>\n* @copyright  Copyright (c) 2008-2010  (http://www.sgcms.cn)\n* @license    http://www.sgcms.cn/license.txt\n*/\n\nif (!defined('SHUGUANGCMS')) exit();\n\nreturn array(\r\n";
	$writeDataFooter =  ');';
	if(empty($data)){
		$configDao = D('Config');
		$getConfig = $configDao->where("id=1")->find();
		foreach((array)$getConfig as $key =>$value)
		{
			if(strtolower($value) == "true"||strtolower($value) == "false"||is_numeric($value)){
				$data .= "    '".$key."' => ".dadds($value).",\r\n";
			}else{
				$data .= "    '".$key."' => '".dadds($value)."',\r\n";
			}
		}
		$writeData = $writeDataHeader .$data .$writeDataFooter;
	}else {
		$writeData = $writeDataHeader .$data .$writeDataFooter;
	}
	@file_put_contents($writePath .$writeFile,$writeData);
	return $getConfig;
}
function clearCore()
{
	delFile('./FrontApp/Runtime/Cache');
	delFile('./FrontApp/Runtime/Data');
	delFile('./FrontApp/Runtime/Logs');
	delFile('./FrontApp/Runtime/Temp');
	@unlink('./FrontApp/Runtime/~app.php');
	@unlink('./FrontApp/Runtime/~runtime.php');
	delFile('./AdminApp/Runtime/Cache');
	delFile('./AdminApp/Runtime/Data');
	delFile('./AdminApp/Runtime/Logs');
	delFile('./AdminApp/Runtime/Temp');
	@unlink('./AdminApp/Runtime/~app.php');
	@unlink('./AdminApp/Runtime/~runtime.php');
}
function delDir($directory,$subdir=true)
{
	if (is_dir($directory) != false)
	{
		$handle = opendir($directory);
		while (($file = readdir($handle)) !== false)
		{
			if ($file != "."&&$file != "..")
			{
				is_dir("$directory/$file")?
				delDir("$directory/$file"):
				unlink("$directory/$file");
			}
		}
		if (readdir($handle) == false)
		{
			closedir($handle);
			rmdir($directory);
		}
	}
}
function delFile($directory)
{
	if (is_dir($directory) != false)
	{
		$handle = opendir($directory);
		while (($file = readdir($handle)) !== false)
		{
			if ($file != "."&&$file != ".."&&is_file("$directory/$file"))
			{
				unlink("$directory/$file");
			}
		}
		closedir($handle);
	}
}
function getCache($name = '',$root = './CmsData/',$returnData = '')
{
	$formatName = strtolower($name);
	$getFile = $root .'cache.'.$formatName .'.php';
	if(fileExit($getFile)){
		$returnData = @require($getFile);
	}else{
		switch ($formatName)
		{
			case 'adminrole': $returnData = writeCache('AdminRole') ;break;
			case 'config': $returnData = configCache(1);break;
			case 'category': $returnData = writeCache('Category',0,'display_order DESC,id DESC') ;break;
			case 'link': $returnData = writeCache('Link',0,'display_order DESC,id DESC');break;
			case 'menu': $returnData = writeCache('Menu',0,'display_order DESC,id DESC');break;
			case 'module': $returnData = writeCache('Module');break;
		}
	}
	return $returnData;
}
function getContent($file = NULL,$path = NULL){
	$gFile = empty($file) ?exit('error function getFile: file is LOST') : $file ;
	$getPath = empty($path) ?CMS_DATA : $path ;
	$getFile = $getPath .'/'.$gFile;
	if(!file_exists($getFile)) die("file:$getFile is LOST");
	return @require($getFile);
}
function putContent($data,$file = NULL,$path = NULL)
{
	$pFile = empty($file) ?exit('error function getFile: file is LOST') : $file ;
	$pPath = empty($path) ?CMS_DATA : $path ;
	if ($path != '.'){
		if(!is_dir($pPath)){
			@mk_dir($pPath);
		}
	}
	$putFile = $pPath.'/'.$pFile;
	@file_put_contents($putFile,$data);
}
function xCopy($source,$dest,$child = 0){
	if(!is_dir($source)){
		echo("Error:the $source is not a direction!");
		exit();
	}
	if(!is_dir($dest)){
		@mk_dir($dest,0777);
	}
	$fileHander = opendir($source);
	while (($file = readdir($fileHander)) !== false)
	{
		$filepath = $source .'/'.$file;
		if (strcmp($file,'.') == 0 ||strcmp($file,'..') == 0 )
		{
			continue;
		}
		if(is_dir($filepath)){
			if($child) xCopy($source."/".$file,$dest."/".$file,$child);
		}else{
			copy($source."/".$file,$dest."/".$file);
		}
	}
}
function copyDir($source,$dest,$child = 0){
	if(!is_dir($source)){
		echo("Error:the $source is not a direction!");
		exit();
	}
	if(!is_dir($dest)){
		@mk_dir($dest,0777);
	}
	$fileHander = opendir($source);
	while (($file = readdir($fileHander)) !== false)
	{
		$filepath = $source .'/'.$file;
		if (strcmp($file,'.') == 0 ||strcmp($file,'..') == 0 ) continue;
		if(is_dir($filepath)){
			if($child) xCopy($source."/".$file,$dest."/".$file,$child);
		}
	}
}
function getCategory($array,$parentid = 0,$level = 0,$add = 2,$repeat = '　') {
	$str_repeat = '';
	if($level) {
		for($j=0;$j<$level;$j++) {
			$str_repeat .= $repeat;
		}
	}
	$newarray = array();
	$temparray = array();
	foreach((array)$array as $v) {
		if($v['parent_id'] == $parentid) {
			$newarray[] = array(
'id'=>$v['id'],
'module'=>$v['module'],
'title'=>$v['title'],
'parent_id'=>$v['parent_id'],
'level'=>$level,
'display_order'=>$v['display_order'],
'description'=>$v['description'],
'status'=>$v['status'],
'create_time'=>$v['create_time'],
'update_time'=>$v['create_time'],
'status'=>$v['status'],
'protected'=>$v['protected'],
'str_repeat'=>$str_repeat
			);
			$temparray = getCategory($array,$v['id'],($level +$add));
			if($temparray) {
				$newarray = array_merge($newarray,$temparray);
			}
		}
	}
	return $newarray;
}
function bgStyle($data,$param = 1,$color = '#00F'){
	if($data == $param){
		return $color;
	}
}
function buildSelect($data,$parentId = 0,$selected = 0,$str = '')
{
	$formatArray = getCategory($data,$parentId);
	foreach ((array)$formatArray as $row){
		if($row['id'] == $selected){
			$str .= '<option value="'.$row['id'] .'" selected="selected">'.$row['str_repeat'] .$row['title'] .'</option>';
		}else{
			$str .= '<option value="'.$row['id'] .'">'.$row['str_repeat'] .$row['title'] .'</option>';
		}
	}
	return $str;
}
function moduleTitle($name = '',$file = NULL,$path = NULL){
	$getData = getCache('Module');
	foreach ((array)$getData as $key=>$value){
		if($value['module_name'] == $name){
			echo $value['module_title'];
		}
	}
}
function dadds($str)
{
	$content = (!get_magic_quotes_gpc ()) ?addslashes($str) : $str;
	return trim($content);
}
function categoryModule($data)
{
	foreach ((array)$data as $row){
		if(in_array($row['module_name'],array('News','Product','Download','Job','Link','Ad'))){
			$datas[] = $row;
		}
	}
	return $datas;
}
function selectCategory($slid){
	$category = getCache('Category');
	foreach ((array)$category as $c){
		if($c['id'] == $slid){
			echo $c['title'];
		}
	}
}
function explodeUrl($url,$img = '')
{
	$str = empty($url) ?'': explode("\n",$url);
	foreach ((array)$str as $key=>$row){
		$key = $key+1;
		$result .= "<a href='$row' target='_blank'><img src='$img' align='absmiddle'/>下载地址 $key</a><br />";
	}
	echo $result;
}