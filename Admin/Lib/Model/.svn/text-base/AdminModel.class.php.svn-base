<?php 
/**
 * 
 * Admin(管理员)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: AdminModel.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

import("AdvModel");
class AdminModel extends AdvModel
{
    protected $_validate = array(
        array('username', 'require', '用户名必填', 0, '', Model:: MODEL_BOTH),
        array('password', 'require', '密码必填', 0, '', Model:: MODEL_INSERT),
        array('username', '', '用户已经存在', 1, 'unique', Model:: MODEL_INSERT),
    );
    protected $_auto = array(
        array('create_time', 'time', Model:: MODEL_INSERT, 'function'),
		array('update_time', 'time', Model:: MODEL_UPDATE, 'function'),
    );
}