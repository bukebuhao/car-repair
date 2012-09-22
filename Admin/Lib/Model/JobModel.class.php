<?php 
/**
 * 
 * Job(人才)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: JobModel.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

import("AdvModel");
class JobModel extends AdvModel
{
    protected $_validate = array(
        array('title', 'require', '招聘职位必填', 0, '', Model:: MODEL_BOTH),
        array('content', 'require', '详细要求必填', 0, '', Model:: MODEL_BOTH),
    );

    protected $_auto = array(
        array('title', 'dHtml', Model:: MODEL_BOTH, 'function'),
        array('link_url', 'cvHttp', Model:: MODEL_BOTH, 'function'),
        array('tags', 'formatTags', Model:: MODEL_BOTH, 'function'),
        array('create_time', 'strtotime', Model:: MODEL_BOTH, 'function'),
        array('end_time', 'strtotime', Model:: MODEL_BOTH, 'function'),
		array('update_time', 'time', Model:: MODEL_UPDATE, 'function'),
    );
}