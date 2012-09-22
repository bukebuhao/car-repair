<?php
/**
 * 
 * Job(人才)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: JobAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class JobAction extends CommonAction
{
    public $dao;
    function _initialize()
    {
        parent::_initialize();
        $this->dao = D('Job');
    }

    /**
     * 列表
     *
     */
    public function index()
    {
    	parent::_checkPermission();
        $condition = array();
        $title = formatQuery($_GET['title']);
        $orderBy = trim($_GET['orderBy']);
        $orderType = trim($_GET['orderType']);
        $status =  intval($_GET['status']);
        $istop = intval($_GET['istop']);
        $viewCount = intval($_GET['viewCount']);
        $viewCount1 = intval($_GET['viewCount1']);
        $setViewCount = setViewCount($viewCount, $viewCount1);
        $setOrder = setOrder(array(array('viewCount', 'view_count'), 'id'), $orderBy, $orderType);
        $pageSize = intval($_GET['pageSize']);
        $title &&  $condition['title'] = array('like', '%'.$title.'%');
        $status && $condition['status'] = array('eq', $status);
        $istop && $condition['istop'] = array('eq', $istop);
        $viewCount1 && $condition['view_count'] = array('between', $setViewCount);
        $count = $this->dao->where($condition)->count();
        $listRows = empty($pageSize) || $pageSize > 100 ? 15 : $pageSize ;
        $p = new page($count, $listRows);
        $dataList = $this->dao->Where($condition)->Order($setOrder)->Limit($p->firstRow.','.$p->listRows)->select();
        $page = $p->show();
        if($dataList !== false){
            $this->assign('dataList', $dataList);
            $this->assign('pageBar', $page);
        }
        parent::_sysLog('index');
        $this->display();
    }

    /**
     * 录入
     *
     */
    public function insert()
    {
    	parent::_checkPermission();
        $this->display();
    }

    /**
     * 提交录入
     *
     */
    public function doInsert()
    {
    	parent::_checkPermission('Job_insert');
    	parent::_setMethod('post');
        if($daoCreate = $this->dao->create())
        {
            $style = createStyle($_POST);
            $this->dao->title_style = $style['title_style'];
            $this->dao->title_style_serialize = $style['title_style_serialize'];
            $uploadFile = upload($this->getActionName(), 0, 0, 0);
            $this->dao->user_id = parent::_getAdminUid();
            if ($uploadFile)
            {
                $this->dao->attach = 1;
                $this->dao->attach_image = formatAttachPath($uploadFile[0]['savepath']) . $uploadFile[0]['savename'];
            }
            $daoAdd = $this->dao->add();
            if(false !== $daoAdd)
            {
                parent::_tags('insert', $_POST['tags'], $daoAdd);
                parent::_sysLog('insert', "录入:$daoAdd");
                parent::_message('success', '录入成功');
            }else
            {
                parent::_message('error', '录入错误');
            }
        }else
        {
            parent::_message('error', $this->dao->getError());
        }
    }

    /**
     * 编辑
     *
     */
    public function modify()
    {
    	parent::_checkPermission();
        $item = intval($_GET["id"]);
        $record = $this->dao->Where('id='.$item)->find();
        if(empty($item) || empty($record)) parent::_message('error', '记录不存在');;
        $this->assign('vo', $record);
        $this->display();
    }

    /**
     * 提交编辑
     *
     */
    public function doModify()
    {
    	parent::_checkPermission('Job_modify');
    	parent::_setMethod('post');
        $item = intval($_POST['id']);
        empty($item) && parent::_message('error', '记录不存在');;
        if($daoCreate = $this->dao->create())
        {
            $style = createStyle($_POST);
            $this->dao->title_style = $style['title_style'];
            $this->dao->title_style_serialize = $style['title_style_serialize'];
            $uploadFile = upload($this->getActionName(), 0, 0, 0);
            if ($uploadFile)
            {
                $this->dao->attach = 1;
                $this->dao->attach_file = formatAttachPath($uploadFile[0]['savepath']) . $uploadFile[0]['savename'];
                deleteUploadFile($this->upload.$_POST['old_file']);
            }
            $daoSave = $this->dao->save();
            if(false !== $daoSave)
            {
                parent::_tags('modify', $_POST['tags'], $item);
                parent::_sysLog('modify', "编辑:$item");
                parent::_message('success', '更新完成');
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
	 * 简历
	 *
	 */
    public function resume()
    {
    	parent::_checkPermission('Job_resume');
        $condition = array();
        $realname = formatQuery($_GET['realname']);
        $orderBy = trim($_GET['orderBy']);
        $orderType = trim($_GET['orderType']);
        $status =  intval($_GET['status']);
        $setOrder = setOrder(array('a.id'), $orderBy, $orderType);
        $pageSize = intval($_GET['pageSize']);
        $realname &&  $condition['realname'] = array('like','%'.$realname.'%');
        $status && $condition['status'] = array('eq', $status);
        $dao = D("Resume");
        $count = $this->dao->where($condition)->count();
        $listRows = empty($pageSize) || $pageSize> 100 ? 15 : $pageSize ;
        $p = new page($count, $listRows);
        $dataList = $this->dao->Table(C('DB_PREFIX').'resume a')->Join(C('DB_PREFIX').'job b on a.job_id=b.id')->Field('a.*,b.title')->order($setOrder)->Where($condition)->Limit($p->firstRow.','.$p->listRows)->select();
        $page = $p->show();
        if($dataList !== false){
            $this->assign('dataList', $dataList);
            $this->assign('pageBar', $page);
        }
        parent::_sysLog('index');
        $this->display();
    }

    /**
	 * 提交简历编辑
	 *
	 */
    public function resumeDetail()
    {
    	parent::_checkPermission('Job_resume');
        $item = intval($_GET["id"]);
        $dao = D("Resume");
        //$record = $this->dao->where('id='.$item)->find();
        $record = $this->dao->Table(C('DB_PREFIX').'resume a')->Join(C('DB_PREFIX').'job b on a.job_id=b.id')->Field('a.*,b.title')->Where('a.id='.$item)->find();
        if(empty($item) || empty($record)) parent::_message('error', '记录不存在', U('Job/order'));
        if($record['status'] == 0){
            $update['status'] = 1;
            $this->dao->where('id='.$item)->save($update);
        }
        $this->assign('vo', $record);
        parent::_sysLog('index');
        $this->display();
    }

    /**
	 * 简历查看/编辑
	 *
	 */
    public function resumeModify()
    {
    	parent::_checkPermission('Job_resume');
    	parent::_setMethod('post');
        $item = intval($_POST['id']);
        empty($item) && parent::_message('error', '记录不存在');;
        $dao = D("Resume");
        if($daoCreate = $this->dao->create())
        {
            $daoSave = $this->dao->save();
            if(false !== $daoSave)
            {
            	parent::_sysLog('modify', "更新简历:$item");
                parent::_message('success', '更新完成', U('Job/resume'));
            }else
            {
                parent::_message('error', '更新失败', U('Job/resume'));
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
    	parent::_checkPermission('Job_command');
        if(getMethod() == 'get'){
            $operate = trim($_GET['operate']);
        }elseif(getMethod() == 'post'){
            $operate = trim($_POST['operate']);
        }else{
            parent::_message('error', '只支持POST,GET数据');
        }
        switch ($operate){
            case 'delete': parent::_delete();break;
            case 'resumeDelete': parent::_delete('resume', U('Job/resume'));break;
            case 'setTop': parent::_setTop('set');break;
            case 'unSetTop': parent::_setTop('unset');break;
            case 'setStatus': parent::_setStatus('set');break;
            case 'unSetStatus': parent::_setStatus('unset');break;
            case 'update': parent::_batchModify($_POST, array('title', 'number', 'status'));break;
            default: parent::_message('error', '操作类型错误') ;
        }
    }
}
