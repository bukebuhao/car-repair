<?php 
/**
 * 
 * Comment(评论)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: CommentModel.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

import("AdvModel");
class CommentModel extends AdvModel 
{
    protected $_validate = array(
	    //array('subject','require','标题必填',0,'','all'),
	    //array('content','require','内容必填',0,'','all'),
    );

    protected $_auto = array(
	    array('create_time', 'time', Model:: MODEL_INSERT, 'function'),
        array('update_time', 'time', Model:: MODEL_UPDATE, 'function'),
    );
}