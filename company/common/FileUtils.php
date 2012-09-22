<?php
/**
 *
 * 文件操作函数
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version         $ID: fileUtils.php 2011-8-5 Administrator tengzhiwangluoruanjian$

 */

/**
 * 判断文件是否存在
 */
function fileExit($filename) {
	return file_exists_case($filename);
}

/**
 * 从文件里获取数据缓存
 */
function getCache($cacheModel, $condition = NULL, $orderString=NULL, $cacheName=NULL) {
	//文件路径不存在的时候，采用默认的缓存方式
	$cacheNameKey = $cacheModel;
	if(!empty($cacheName)) {
		$cacheNameKey = $cacheName;
	}
	if(S($cacheNameKey)) {
		$list = S($cacheNameKey);
	}else{
		$list = writeCache($cacheModel, $condition, $orderString, $cacheName);
	}
	return $list;
}

/**
 *
 * 缓存数据库数据到文件里
 * @param $cacheModel
 */
function writeCache($cacheModel, $condition=NULL, $orderString=NULL, $cacheName=NULL) {
	$Form    =    M($cacheModel);
	if (!empty($condition)) {
		$Form->where($condition);
	}
	if (!empty($orderString)) {
		$Form->order($orderString);
	} else {
		$Form->order("id"); //默认按照id
	}
	$list    =    $Form->select();

	if(empty($cacheName)) {
		S($cacheModel, $list);
	} else {
		S($cacheName, $list);
	}
	return $list;
}

/**
 *
 * 获取页面内容,从指定文件路径里
 * @param unknown_type $content
 */
function getContent($varFileName, $varFilePath) {
	$fileName = empty($varFileName) ?exit('error function getContent: file is LOST') : $varFileName ;
	$filePath = empty($varFilePath) ? APP_CONFIG : $varFilePath;
	if ($filePath == '.') {
		$filePath = APP_CONFIG;
	}
	$file = $filePath.'/'.$fileName;
	if(!file_exists($file)) die("file:$file is LOST");
	return require ($file);
}


/**
 * 清除项目缓存
 * Enter description here ...
 */
function clearCore() {
	delFile('./Front/Runtime/Cache');
	delFile('./Front/Runtime/Data');
	delFile('./Front/Runtime/Logs');
	delFile('./Front/Runtime/Temp');
	@unlink('./Front/Runtime/~app.php');
	@unlink('./Front/Runtime/~runtime.php');
	delFile('./Admin/Runtime/Cache');
	delFile('./Admin/Runtime/Data');
	delFile('./Admin/Runtime/Logs');
	delFile('./Admin/Runtime/Temp');
	@unlink('./Admin/Runtime/~app.php');
	@unlink('./Admin/Runtime/~runtime.php');
}

function delFile($directory)
{
	if (is_dir($directory) !== false)
	{
		$handle = opendir($directory);
		while (($file = readdir($handle)) !== false)
		{
			if ($file != "."&&$file != "..")
			{
				is_dir("$directory/$file")?
				delFile("$directory/$file"):
				unlink("$directory/$file");
			}
		}
		if (readdir($handle) === false)
		{
			closedir($handle);
			rmdir($directory);
		}
	} else if (is_file($directory) !== false) {
		unlink("$directory");
	}
}

/**
 * 更新缓存信息
 * Enter description here ...
 */
function configCache($configData) {
	if (!isset($configData)) {
		$Config= M("Config");
		$configData=$Config->getById('1');
	}
	//防止页面更新不全
	$OldConfig = getContent('cms.config.php');
	$configData = array_merge($OldConfig, $configData);
	
	$configHeader = "<?php\n/** \n*  cms配置文件\n*\n* @package      	companycms\n* @author     zxz (QQ:396774497)\n* @copyright  Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)\n* @license    http://www.tengzhiinfo.com/license.txt\n* @version    \$ID: db.config.php 2011-7-28 Administrator$\n*/\n\n\nreturn array(\r\n";
	$configFooter .= ');';
	foreach($configData as $key => $value) {
		//过滤无关POST key
		if (in_array($key, array('submit', 'id'))) continue ;
		if(strtolower($value) == "true" || strtolower($value) == "false" || is_numeric($value)){
			$configBody .= "    '".$key."' => ".dadds($value).",\r\n";
		}else{
			$configBody .= "    '".$key."' => '".dadds($value)."',\r\n";
		}
	}
	$configData = $configHeader . $configBody . $configFooter;
	putContent($configData, 'cms.config.php', APP_CONFIG);
}

/**
 *
 * 数字原型显示，文字添加''
 * @param unknown_type $value
 */
function dadds($value) {
	return formatQuery($value);
}

/**
 *
 * 写入配置信息
 * @param $configFile
 * @param $config
 */
function putContent($config, $varFileName, $varFilePath) {
	$fileName = empty($varFileName) ?exit('error function putContent: file is LOST') : $varFileName ;
	$filePath = empty($varFilePath) ? APP_CONFIG : $varFilePath;
	if ($filePath == '.') {
		$filePath = APP_CONFIG;
	}
	if($filePath != APP_CONFIG && !is_dir($filePath)){
		@mk_dir($filePath);
	}
	@file_put_contents($filePath."/".$fileName, $config);
}

/**
 *
 * 获取指定目录下的子目录
 * @param $filePath
 */
function getDir($filePath) {
	return getFile($filePath, 'is_dir');
}

/**
 * 目录下的文件名字
 * Enter description here ...
 * @param $filePath
 */
function getFile($filePath, $varfunction) {
	$paramFunction = empty($varfunction) ? "is_file" : $varfunction;
	if (is_dir($filePath)) {
		$d = dir($filePath);
		$x=array();
		$i = 0;
		while (false !== ($r = $d->read())) {
			if(strncmp($r, ".", 1) !=0 && strncmp($r, "..", 2) && $paramFunction($filePath.'/'.$r)) {
				$x[$i] = $r;
				$i++;
			}
		}
		return $x;
	}
	return array();
}

// 文件上传
function upload($actionName) {
	//验证上传文件是否有效
	if (empty($_FILES)) {
		return null;
	}
	import("ORG.Net.UploadFile");
	
	$globalAttachSize = C('global_attach_size');
	$globalAttachSuffix = C('global_attach_suffix');
	
	$upload = new UploadFile();
	//设置上传文件大小
	$upload->maxSize = $globalAttachSize;
	//设置上传文件类型
	$upload->allowExts = empty($allowExts) ?explode(',',$globalAttachSuffix) : explode(',',$allowExts) ;
	
	//设置附件上传目录
	$savePath = C("global_uploads_path").$actionName;
	if(!is_dir($savePath)) {
		if(!mkdir($savePath)){
			Log::write('上传目录'.$savePath.'不存在',Log::ERR);
			return null;
		}
	}
	$upload->savePath = $savePath.'/'.date("Ymd").'/';
	// 设置引用图片类库包路径
	$upload->imageClassPath = 'ORG.Util.Image';
	//设置需要生成缩略图的文件后缀
	$upload->thumbPrefix = '';
	$upload->thumbSuffix = '_s';
	//$upload->thumbPrefix = 'm_,s_';  //生产2张缩略图
	
	switch ($actionName){
		case 'News':
			$globalThumbStatus = C('news_thumb_status');
			$globalThumbSize = trim(C('news_thumb_size'));
			break;
		case 'Product':
			$globalThumbStatus = C('product_thumb_status');
			$globalThumbSize = trim(C('product_thumb_size'));
			break;
		case 'Download':
			$globalThumbStatus = C('download_thumb_status');
			$globalThumbSize = trim(C('download_thumb_size'));
			break;
		default:
			$globalThumbStatus = C('global_thumb_status');
			$globalThumbSize = trim(C('global_thumb_size'));
			break;
	}
	//设置需要生成缩略图，仅对图像文件有效
	if(!empty($globalThumbStatus)){
		$upload->thumb = true;
	}else{
		$upload->thumb = false;
	}
	
	//设置缩略图最大宽度 最大高度
	$globalThumbSizeExplode = explode(',',$globalThumbSize);
	$upload->thumbMaxWidth = $globalThumbSizeExplode[0] ;
	$upload->thumbMaxHeight = $globalThumbSizeExplode[1] ;
	//设置上传文件规则
	$upload->saveRule = uniqid;
	//删除原图
	//$upload->thumbRemoveOrigin = true;
	if (!$upload->upload()) {
		//捕获上传异常
		Log::write($upload->getErrorMsg(),Log::ERR);
		return null;
	} else {
		//取得成功上传的文件信息
		$uploadList = $upload->getUploadFileInfo();
		import("ORG.Util.Image");
		//给m_缩略图添加水印, Image::water('原文件名','水印图片地址')
		Image::water($uploadList[0]['savepath'].$uploadList[0]['savename'], '/Public/Images/logo.png');
		//$uploadList[0]['savename'] = 's_'.$uploadList[0]['savename'];
	}
	return $uploadList;
}

/**
 * 删除上传的文件
 * Enter description here ...
 * @param $fileName
 */
function deleteUploadFile($fileName) {
	@unlink(C("global_uploads_path").$fileName);
}


/**
 * 返回有效的图片路径
 * @param $savepath
 */
function formatAttachPath($savepath) {
	$uplaodPath =  C("global_uploads_path");
	return str_replace($uplaodPath, '', $savepath);
}

/**
 *
 * 返回小图片的名称
 * @param $savename
 */
function splitThumb($prefixsavename) {
	$splitAttach = explode('.',$attach);
	$thumb =  $splitAttach[0].'_s.'.$splitAttach[1];
	return $thumb;
}