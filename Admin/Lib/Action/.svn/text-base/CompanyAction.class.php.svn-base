<?php 
/**
 * 
 * Company(企业管理)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: PageAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class CompanyAction extends CommonAction
{
	function _initialize()
	{
		parent::_initialize();
	}
	/**
     * 公司首页
     *
     */
	public function index()
	{
		//$this->redirect('Page/index');
		$this->assign("sessionId", Session::id());
		$this->display('picture');
	}

	
	/**
	 * 
	 * 公司的掠影
	 */
	public function picture() {
		$this->assign("sessionId", Session::id());
		$this->display();
	}
	
}
