<?php
/**
 *
 * Uploads(上传模块)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: ThemeAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class UploadsAction extends CommonAction {
	
	function _initialize() {
			 
		//加载扩展类
		Load('extend');
	}
	/**
	 * 上传图片
	 * Enter description here ...
	 */
	public function uploadImage() {
		$uploadFile = upload($_POST['file']);
		if ($uploadFile != null && fileExit($uploadFile[0]['savepath'].$uploadFile[0]['savename'])) {
			$filePath = formatAttachPath($uploadFile[0]['savepath']) . $uploadFile[0]['savename'];
			//保存到数据库里
			$pictureDao = D('Picture');
			$pictureDao->title='上传的图片';
			$pictureDao->path=$filePath;
			$daoAdd = $pictureDao->add();
            if(false !== $daoAdd)
            {
            	 parent::_sysLog('uploadImage', "上传成功:$daoAdd");
            	 $this->ajaxReturn($filePath, "上传图片成功!", 1);
            } else {
            	 @unlink($uploadFile[0]['savepath'] . $uploadFile[0]['savename']);
            }
		}
		$this->ajaxReturn(0, "上传异常!", 0);
	}
}