<?php 
/**
 * 
 * AdminLog(系统日志)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: AdminLogModel.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

import("AdvModel");
class AdminLogModel extends AdvModel
{
   protected $_auto = array(
		array('create_time', 'time', Model:: MODEL_BOTH, 'function'),
	);
}