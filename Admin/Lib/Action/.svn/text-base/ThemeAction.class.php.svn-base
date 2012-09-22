<?php
/**
 *
 * Theme(模板)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: ThemeAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class ThemeAction extends CommonAction
{
	private  $fileDir;
	function _initialize()
	{
		parent::_initialize();
		parent::_checkPermission('Theme');
		$this->fileDir = FRONT_PATH . '/Tpl/';
	}

	/**
	 * 格式化模板开发者信息
	 *
	 * @param unknown_type $data
	 * @return unknown
	 */
	public function formatDev($data)
	{
		$data = htmlspecialchars($data);
		return preg_replace("/(\015\012)|(\015)|(\012)/", "<br />", $data);
	}

	/**
	 * 风格列表页
	 *
	 */
	public function index()
	{
		$config = getContent('app.config.php', '.');
		$theme = $config['DEFAULT_THEME'];
		$fileList = getDir($this->fileDir);
		foreach ((array)$fileList as $row)
		{
			$devFile = $this->fileDir.$row . '/dev.txt';
			$thumb = $this->fileDir.$row . '/thumb.jpg';
			$developer = file_exists($devFile) ? file_get_contents($devFile) : array() ;
			$thumbImg = file_exists($thumb) ? $thumb : '';
			$arr[] = array('title' => $row, 'thumb' => $thumbImg, 'dev' => $this->formatDev($developer));
		}
		if(false != $fileList){
			$data['fileList'] = $arr;
			$data['theme'] = $theme;
			$this->assign($data);
		}

		parent::_sysLog('index');
		$this->display();
	}

	/**
	 * 应用风格
	 *
	 */
	public function applyTheme()
	{
		$themeName = trim($_GET['themeName']);
		$this->checkFileName($themeName);
		$modifyDate = date('Y-m-d H:i:s');
		$config = getContent('app.config.php', '.');

		$configHeader = "<?php\n/** \n*  cms配置信息\n*\n* @package      	companycms\n* @author     zxz (QQ:396774497)\n* @copyright  Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)\n* @license    http://www.tengzhiinfo.com/license.txt\n* @version    \$ID: app.config.php 2011-7-28 Administrator$\n*/\n\n\nreturn array(\r\n";
		$configFooter .= ');';
		$config['DEFAULT_THEME'] = $themeName;
		foreach((array)$config as $key => $value)
		{
			if($value === true){
				$configBody .= "\t'".$key."' => true,\r\n";
			}else if($value === false){
				$configBody .= "\t'".$key."' => false,\r\n";
			} else if(is_numeric($value)){
				$configBody .= "\t'".$key."' => $value,\r\n";
			}else{
				$configBody .= "\t'".$key."' => '$value',\r\n";
			}
		}

		$configData = $configHeader . $configBody . $configFooter;
		putContent($configData, 'app.config.php', APP_CONFIG);
		//更换成功后模板编辑页设置为当前风格
		cookie('tempTheme', $themeName, 600);
		clearCore();
		parent::_message('success', '风格更换成功');

	}

	/**
	 * 模板管理
	 *
	 */
	public function template()
	{
		$getTheme = trim($_GET['theme']);
		$getCookie = cookie('tempTheme');
		if(!empty($getTheme)){
			$theme = $getTheme;
			cookie('tempTheme', $getTheme, 600);
		}elseif(empty($getTheme) && empty($getCookie)){
			$config = getContent('app.config.php', '.');
			$theme = $config['DEFAULT_THEME'];
		}else{
			$theme = cookie('tempTheme');
		}

		$templateDir = $this->fileDir . $theme .'/';
		//检测目录是否存在
		if(!is_dir($templateDir) || empty($theme)){
			cookie('tempTheme', null);
			parent::_message('error', '风格目录不存在');
		}
		//获取风格列表
		$themeList = getDir($this->fileDir);
		//获取风格文件列表
		$fileList = getDir($templateDir);
		foreach ((array)$fileList as $key => $file)
		{
			$files[] = array('fileName' => $file, 'subFileList' => getFile($templateDir . $file));
		}

		if(false != $fileList){
			$data['fileList'] = $files;

		}
		$data['folder'] = $templateDir;
		$data['themeList'] = $themeList;
		$data['theme'] = $theme;
		$this->assign($data);
		parent::_sysLog('index');
		$this->display();
	}

	/**
	 * 创建模板文件夹
	 *
	 */
	public function themeCreateFolder()
	{
		parent::_setMethod('post');
		$folderName = trim($_POST['folderName']);
		//检测名称是否合法
		self::checkFileName($folderName);
		$folderPath = safe_b64decode($_POST['folderPath']);
		$createFolder = $folderPath.$folderName;
		if(is_dir($createFolder)){
			parent::_message('error', "$createFolder 已经存在，此次操作未生效");
		}
		@mk_dir($createFolder);
		parent::_message('success', "文件夹 {$folderName} 创建成功", U('Theme/template'));
	}

	/**
	 * 创建新页面
	 *
	 */
	public function templateInsert()
	{
		$folder = trim($_GET['folder']);
		$deFolder = base64_decode($folder);
		empty($deFolder) && parent::_message('error', '文件夹未指定');
		$data['folder'] = $deFolder;
		$this->assign($data);
		$this->display();
	}

	/**
	 * 提交创建
	 *
	 */
	public function doTemplateInsert()
	{
		parent::_setMethod('post');
		$getCookie = cookie('tempTheme');
		$jumpUri = empty($getCookie) ? U('Theme/template') : U('Theme/template', array('theme' => $getCookie)) ;
		$fileName = trim($_POST['fileName']);
		//检测名称是否合法
		self::checkFileName($fileName);
		$content = trim($_POST['content']);
		$fileFolder = trim($_POST['fileFolder']);
		$saveFile = $fileFolder.$fileName.'.html';
		empty($content) && $content = 'empty file';
		if(file_exists($saveFile)) parent::_message('error', "{$fileName}.html 此文件已经存在", 0, 5);
		$fileHander = file_put_contents($saveFile, $content);
		if(false == $fileHander){
			parent::_message('error', '文件写入失败，请检查模板文件夹是否拥用写入权限');
		}else {
			parent::_sysLog('insert', "写入模板: $saveFile");
			parent::_message('success', "写入成功:{$fileName}.html", $jumpUri);
		}
	}

	/**
	 * 编辑
	 *
	 */
	public function templateModify()
	{
		$enFile = trim($_GET["file"]);
		$deFile = base64_decode($enFile);
		$content = file_get_contents($deFile);
		empty($content) && parent::_message('error', '模板文件读取错误');
		$data['file'] = $deFile;
		$data['content'] = htmlspecialchars($content);
		$data['fileSize'] = byte_format(filesize($deFile));
		$data['fileMtime'] = date("Y-m-d H:i:s", filemtime($deFile));
		$this->assign($data);
		$this->display();
	}

	/**
	 * 提交编辑
	 *
	 */
	public function doTemplateModify()
	{
		parent::_setMethod('post');
		$getCookie = cookie('tempTheme');
		$jumpUri = empty($getCookie) ? U('Theme/template') : U('Theme/template', array('theme' => $getCookie)) ;
		$fileName = trim($_POST['fileName']);
		empty($fileName) && parent::_message('error', '文件获取错误');
		$content = trim($_POST['content']);
		empty($content) && $content = 'empty';
		$fileHander = file_put_contents($fileName, $content);
		if(false == $fileHander){
			parent::_message('error', '文件写入失败，请检查模板文件夹是否拥用写入权限');
		}else {
			parent::_sysLog('modify', "编辑模板: $fileName");
			parent::_message('success', "$fileName 编辑成功", $jumpUri);
		}

	}

	/**
	 * 检测名称是否合法
	 *
	 * @param unknown_type $param
	 */
	public function checkFileName($param = NULL)
	{
		if (!eregi("^[A-Z0-9]{1,26}$", $param)) {
			parent::_message('error', '名称不合法，必须为英文字母(和/或)数字组合且长度为1-26个字符', 0, 10);
		}
	}

	/**
	 * 批量操作
	 *
	 */
	public function doCommand()
	{
		if(getMethod() == 'get'){
			$operate = trim($_GET['operate']);
		}elseif(getMethod() == 'post'){
			$operate = trim($_POST['operate']);
		}else{
			parent::_message('error', '只支持POST,GET数据');
		}
		switch ($operate){
			case 'deleteTemplate':
				$getCookie = cookie('tempTheme');
				$jumpUri = empty($getCookie) ? U('Theme/template') : U('Theme/template', array('theme' => $getCookie)) ;
				$deFile = base64_decode($_GET['fileName']);
				empty($deFile) && parent::_message('error', '文件名获取错误');
				!file_exists($deFile) && parent::_message('error', '文件不存在，删除失败');
				@unlink($deFile);
				parent::_sysLog('delete', "删除模板:$deFile");
				parent::_message('success', '删除完成', $jumpUri);
				break;
			case 'dirDelete':
				$getDir = base64_decode(trim($_GET['dirPath']));
				if(is_dir($getDir)){
					$fileList = getFile($getDir);
					foreach ((array)$fileList as $row){
						@unlink($getDir . '/' . $row);
					}
				}
				if(rmdir($getDir)){
					parent::_message('success', '目录删除完成', U('Theme/template'));
				}else{
					parent::_message('error', '目录删除失败，请删除此目录下所有文件再删除此目录');
				}
				break;
			default: parent::_message('error', '操作类型错误') ;
		}
	}
}
