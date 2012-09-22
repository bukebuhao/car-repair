<?php 
/**
 * 
 * Config(系统配置)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: ConfigModel.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

import("AdvModel");
class ConfigModel extends AdvModel
{
	protected $_validate = array(
		array('site_name', 'require', '网站名称必须填写', 0, '', Model:: MODEL_BOTH),
		array('run_system', 'require', '运行平台(下载)必须填写', 0, '', Model:: MODEL_BOTH),
		array('global_attach_size', 'require', '允许附件大小必须填写', 0, '', Model:: MODEL_BOTH),
		array('global_attach_suffix', 'require', '允许附件类型必须填写', 0, '', Model:: MODEL_BOTH),
		array('content', 'require', '内容必须', 0, '', Model:: MODEL_BOTH),
	);
	protected $_auto = array(
		array('title', 'dHtml', Model:: MODEL_BOTH, 'function'),
	);
}