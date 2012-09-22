<?php 
/**
 * 
 * Link(友情链接)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: LinkModel.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */
 
import("AdvModel");
class LinkModel extends AdvModel 
{
    protected $_validate = array(
        array('title','require', '网站名称必填', 3),
        array('link_url','require', '链接地址必填', 3),
    );
    protected $_auto = array(
        array('title', 'dHtml', Model:: MODEL_UPDATE, 'function'),
        array('link_url', 'cvHttp', Model:: MODEL_BOTH, 'function'),
        array('create_time', 'time', Model:: MODEL_INSERT, 'function'),
        array('update_time', 'time', Model:: MODEL_UPDATE, 'function'),
    );
}