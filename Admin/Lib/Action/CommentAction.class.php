<?php 
/**
 * 
 * Comment(回复)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: CommentAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class CommentAction extends CommonAction
{
    public $dao;
	function _initialize()
	{
		parent::_initialize();
		$this->dao = D('Comment');
	}

	/**
     * 列表
     */
	public function index()
	{
		parent::_checkPermission();
		$condition = array();
		$title = formatQuery($_GET['title']);
		$content = formatQuery($_GET['rcontent']);
		$model = formatQuery($_GET['model']);
		$orderBy = trim($_GET['orderBy']);
		$orderType = trim($_GET['orderType']);
		$setOrder = setOrder(array('status','id'), $orderBy, $orderType);
		$pageSize = intval($_GET['pageSize']);
		$title &&  $condition['title'] = array('like', '%'.$title.'%');
		$content &&  $condition['content'] = array('like', '%'.$content.'%');
		$model && $condition['model'] = array('eq', $model);
		$count = $this->dao->where($condition)->count();
		$listRows = empty($pageSize) || $pageSize > 100 ? 15 : $pageSize ;
		$p = new page($count, $listRows);
		$dataList = $this->dao->Where($condition)->Order($setOrder)->Limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show();
		$countData = count($dataList);
		for ($key=0; $key < $countData; $key++)
		{
			switch ($dataList[$key]['module']){
				case 'News':
					$titleInfo[$key] = D('News')->where('id='.$dataList[$key]['title_id'])->find();
					$titleInfo[$key]['commentTitle'] = $titleInfo[$key]['title'];
					break;
				case 'Product':
					$titleInfo[$key] = D('Product')->where('id='.$dataList[$key]['title_id'])->find();
					$titleInfo[$key]['commentTitle'] = $titleInfo[$key]['title'];
					break;
				case 'Download':
					$titleInfo[$key] = D('Download')->where('id='.$dataList[$key]['title_id'])->find();
					$titleInfo[$key]['commentTitle'] = $titleInfo[$key]['title'];
					break;
			}
			$dataList[$key]['commentTitle'] =  $titleInfo[$key]['commentTitle'];
		}
		if($dataList !== false)
		{
			$this->assign('pageBar', $page);
			$this->assign('dataList', $dataList);
		}
		parent::_sysLog('index');
		$this->display();
	}

	/**
     * 编辑&回复
     *
     */
	public function modify()
	{
		parent::_checkPermission('Comment_modify');
		$item = intval($_GET["id"]);
		$record = $this->dao->Where('id='.$item)->find();
		if(empty($item) || empty($record)) parent::_message('error', '记录不存在');
		$this->assign('vo', $record);
		$this->display();
	}

	/**
     * 提交编辑&回复
     *
     */
	public function doModify()
	{
		parent::_checkPermission('Comment_modify');
		parent::_setMethod('post');
		$item = intval($_POST['id']);
		if(empty($item)) parent::_message('error', '记录不存在');
		if($daoCreate = $this->dao->create())
		{
			$this->dao->reply_time = !empty($_POST['reply_content']) ? time() : 0 ;
			$daoSave = $this->dao->save();
			if(false !== $daoSave)
			{
				parent::_sysLog('modify', "编辑:$item");
				parent::_message('success', '更新成功');
			}else
			{
				parent::_message('error', '更新失败');
			}
		}else
		{
			parent::_message('error', $this->dao->getError());
		}
	}

	/**
     * 批量操作
     *
     */
	public function doCommand()
	{
		parent::_checkPermission('Comment_command');
		if(getMethod() == 'get'){
			$operate = trim($_GET['operate']);
		}elseif(getMethod() == 'post'){
			$operate = trim($_POST['operate']);
		}else{
			parent::_message('error', '只支持POST,GET数据');
		}
		switch ($operate){
			case 'delete': parent::_delete();break;
			case 'update': parent::_batchModify($_POST, array('status'));break;
			default: parent::_message('error', '操作类型错误') ;
		}
	}
}
