<?php 
/**
 * 
 * Feedback(留言)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: FeedbackModel.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */
 
import("AdvModel");
class FeedbackModel extends AdvModel 
{
    protected $_validate = array(
        array('title', 'require', '留言主题必填', 0, '', Model:: MODEL_BOTH),
        array('content', 'require', '留言内容必填', 0, '', Model:: MODEL_BOTH),
    );
}