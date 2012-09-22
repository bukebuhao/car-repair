<?php 
/**
 * 分类 Model
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version         $ID: admin.php 2011-7-28 Administrator tengzhiwangluoruanjian$
 */

import("AdvModel");
class CategoryModel extends AdvModel
{
    protected $_validate = array(
        array('title', 'require','标题必填',0,'',Model:: MODEL_BOTH),
    );
    protected $_auto = array(
        array('title', 'dHtml', Model:: MODEL_BOTH, 'function'),
        array('create_time', 'time', Model:: MODEL_INSERT, 'function'),
        array('update_time', 'time', Model:: MODEL_UPDATE, 'function'),
    );
}