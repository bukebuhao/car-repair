<?php 
/**
 * 
 * AdminLog(系统日志)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: AdminLogAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class AdminLogAction extends CommonAction
{
    public $dao;
    function _initialize()
    {
        parent::_initialize();
        parent::_checkPermission('AdminLog');
        $this->dao = D('AdminLog');
       
    }

    /**
     * 列表
     *
     */
    public function index()
    {
        $condition = array();
        $username = formatQuery($_GET['username']);
        $userId = intval($_GET['userId']);
        $orderBy = trim($_GET['orderBy']);
        $ip = trim($_GET['ip']);
        $orderType = trim($_GET['orderType']);
        $setOrder = setOrder(array(array('userId', 'user_id'), 'id'), $orderBy, $orderType);
        $pageSize = intval($_GET['pageSize']);
        $username &&  $condition['username'] = $username;
        $ip &&  $condition['ip'] = $ip;
        $userId &&  $condition['user_id'] = array('eq', $userId);
        $count = $this->dao->where($condition)->count();
        $listRows = empty($pageSize) ? 15 : $pageSize ;
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
     * 操作指令
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
            //case 'update': parent::_batchModify($_POST,array('id', 'action'));break;
            default: parent::_message('error', '操作类型错误') ;
        }
    }
}
