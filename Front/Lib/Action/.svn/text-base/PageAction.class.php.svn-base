<?php
/**
 * 
 * 单页
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: PageAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class PageAction extends GlobalAction
{
    public $dao;
    function _initialize()
    {
        parent::_initialize();
        $this->dao = M('Page');
    }
    
    /**
     * 详细信息
     *
     */
    public function detail()
    {
		$item = trim($_GET['item']);
        parent::getDetail("link_label='{$item}'");
    }
}