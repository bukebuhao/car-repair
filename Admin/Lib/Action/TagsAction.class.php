<?php 
/**
 * 
 * Tags(公共)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: TagsAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class TagsAction extends CommonAction
{
    public $dao;
	function _initialize()
	{
		parent::_initialize();
		parent::_checkPermission('Tags');
		$this->dao = D('Tags');
	}

	/**
     * 列表
     *
     */
	public function index()
	{
		$condition = array();
		$tagName = formatQuery($_GET['tagName']);
		$module = trim($_GET['module']);
		$orderBy = trim($_GET['orderBy']);
		$orderType = trim($_GET['orderType']);
		$setOrder = setOrder(array(array('viewCount', 'view_count'), 'id'), $orderBy, $orderType);
		$pageSize = trim($_GET['pageSize']);
		$tagName &&  $condition['tag_name'] = array('like', '%'.$tagName.'%');
		$module &&  $condition['model'] = array('eq', $module);
		$count = $this->dao->where($condition)->count();
		$listRows = empty($pageSize) ? 15 : $pageSize ;
		$p = new page($count, $listRows);
		$dataList = $this->dao->Where($condition)->Order($setOrder)->Limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show();
		if($dataList !== false)
		{
			$this->assign('pageBar', $page);
			$this->assign('dataList', $dataList);
		}
		parent::_sysLog('index');
		$this->display();
	}


	/**
     * 批量操作
     *
     */
	public function doCommand()
	{
		if(getMethod() == 'get'){
            $operate = trim($_GET['operate']);
        }elseif(getMethod() == 'post'){
            $operate = trim($_POST['operate']);
        }else{
            parent::_message('error', '只支持POST,GET数据');
        }
		switch ($operate){
			case 'delete': parent::_delete();break;
			default: parent::_message('error', '操作类型错误') ;
		}

	}
}
