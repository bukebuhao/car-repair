<?php 
/**
 * 
 * AdminRole(角色组)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: AdminRoleModel.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

import("AdvModel");
class AdminRoleModel extends AdvModel
{
	protected $_validate = array(
		array('role_name', 'require', '角色组名称必填', 0, '', Model:: MODEL_BOTH),
	);
	protected $_auto = array(
		array('create_time', 'time', Model:: MODEL_INSERT, 'function'),
		array('update_time', 'time', Model:: MODEL_UPDATE, 'function'),
	);
}