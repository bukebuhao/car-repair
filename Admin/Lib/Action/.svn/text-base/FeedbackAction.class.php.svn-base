<?php 
/**
 * 
 * Feedback(留言)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: FeedbackAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class FeedbackAction extends CommonAction
{
    public $dao;
    function _initialize()
    {
        parent::_initialize();
        $this->dao = D('Feedback');
    }

    /**
     * 列表
     *
     */
    public function index()
    {
    	parent::_checkPermission();
        $condition = array();
        $username = formatQuery($_GET['username']);
        $title = formatQuery($_GET['title']);
        $content = formatQuery($_GET['rcontent']);
        $orderBy = trim($_GET['orderBy']);
        $setOrderBy = empty($orderBy) ? 'id' : $orderBy ;
        $orderType = trim($_GET['orderType']);
        $setOrderType = empty($orderType) ? 'DESC' : $orderType ;
        $setOrder = $setOrderBy.' '.$setOrderType;
        $pageSize = intval($_GET['pageSize']);
        $username &&  $condition['username'] = array('like', '%'.$username.'%');
        $title &&  $condition['title'] = array('like', '%'.$title.'%');
        $content &&  $condition['content'] = array('like', '%'.$content.'%');
        $count = $this->dao->Where($condition)->count();
        $listRows = empty($pageSize) || $pageSize > 100 ? 15 : $pageSize ;
        $p = new page($count, $listRows);
        $dataList = $this->dao->Where($condition)->Order($setOrder)->Limit($p->firstRow.','.$p->listRows)->select();
        $page  = $p->show();
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
    	parent::_checkPermission();
        $item=intval($_GET["id"]);
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
    	parent::_checkPermission('Feedback_modify');
    	parent::_setMethod('post');
        $item = intval($_POST['id']);
        if(empty($item)) parent::_message('error', '记录不存在');
        if($daoCreate = $this->dao->create())
        {
            !empty($_POST['reply_content']) && $this->dao->reply_time = time();
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
    	parent::_checkPermission('Feedback_command');
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
            case 'setStatus': parent::_setStatus('set');break;
            case 'unSetStatus': parent::_setStatus('unset');break;
            default: parent::_message('error', '操作类型错误') ;
        }
    }
}
