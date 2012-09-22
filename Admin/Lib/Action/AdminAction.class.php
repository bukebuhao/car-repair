<?php 
/**
 * 
 * Admin(管理员)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: AdminAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class AdminAction extends CommonAction
{
    public $dao;
	public function _initialize()
	{
		parent::_initialize();
		$this->dao = D('Admin');
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
		$userId = intval($_GET['userId']);
		$orderBy = trim($_GET['orderBy']);
		$orderType = trim($_GET['orderType']);
		$setOrder = setOrder(array(array('loginCount', 'a.login_count'), array('id', 'a.id')), $orderBy, $orderType);
		$pageSize = intval($_GET['pageSize']);
		$username &&  $condition['a.username'] = array('like', '%'.$username.'%');
		$userId &&  $condition['a.id'] = array('eq', $userId);
		$count = $this->dao->where($condition)->count();
		$listRows = empty($pageSize) || $pageSize > 100 ? 15 : $pageSize ;
		$p = new page($count, $listRows);
		$dataList = $this->dao->Table(C('DB_PREFIX').'admin a')->Join(C('DB_PREFIX').'admin_role b on a.role_id=b.id')->Field('a.*,b.role_name as role_name')->Order($setOrder)->Where($condition)->Limit($p->firstRow.','.$p->listRows)->select();
		$page = $p->show();
		if($dataList!=false){
			$this->assign('roleList', $adminRoleList);
			$this->assign('pageBar', $page);
			$this->assign('dataList', $dataList);
		}
		parent::_sysLog('index');
		$this->display();
	}

	/**
     * 录入
     *
     */
	public function insert(){
		parent::_checkPermission();
		$roleList = M('AdminRole')->Order("id DESC")->select();
		empty($roleList) && parent::_message('error', '用户组丢失，请检查');
		$this->assign('roleList', $roleList);
		$this->display();
	}

	/**
     * 提交录入
     *
     */
	public function doInsert()
	{
		parent::_checkPermission('Admin_insert');
		parent::_setMethod('post');
		if(!isEnglist($_POST['username'])) parent::_message('error', '用户名必须为英文或英文数字的组合');
		if($daoCreate = $this->dao->create()) {
			$this->dao->password = md5($_POST['password']);
			$daoAdd = $this->dao->add();
			if(false !== $daoAdd){
				parent::_sysLog('insert', "录入:$daoAdd");
				parent::_message('success', '录入成功');
			}else{
				parent::_message('error', '录入失败');
			}
		}else{
			parent::_message('error', $this->dao->getError());
		}
	}

	/**
     * 编辑
     *
     */
	public function modify()
	{
		$item = intval($_GET["id"]);
		$jumpUri = trim($_GET['jumpUri']);
		//编辑自己资料时跳过权限检测，可以编辑自己帐户信息
		if($item != parent::_getAdminUid()) parent::_checkPermission();
		$record = $this->dao->Where('id='.$item)->find();
		if (empty($item) || empty($record)) parent::_message('error', '记录不存在');
		$roleList = M('AdminRole')->Order("id")->select();
		empty($roleList) && parent::_message('error', '当前无角色组，请先录入角色组');
		$this->assign('roleList', $roleList);
		$this->assign('jumpUri', $jumpUri);
		$this->assign('vo', $record);
		$this->display();
	}

	/**
     * 提交编辑
     *
     */
	public function doModify()
	{
	    parent::_setMethod('post');
		$item = intval($_POST['id']);
		//在无管理员管理权限的情况下，可以编辑自己帐户信息
		if($item != parent::_getAdminUid()) parent::_checkPermission('Admin_modify');
		empty($item) && parent::_message('error', 'ID获取错误,未完成编辑');
		$password = $_POST['password'];
		$opassword = $_POST['opassword'];
		if($this->dao->create()) {
			if(!empty($password)){
				$this->dao->password = md5($password);
			}else{
				$this->dao->password = $opassword;
			}
			if($item == 1) {
				//防止修改默认用户所发属组导致不能登录
				$this->dao->role_id = 1;
			}
			$daoSave = $this->dao->save();
			if(false !== $daoSave)
			{
				//防止无权限操作情况下，修改自身资料跳转死循环
				$jumpUri = empty($_POST['jumpUri']) ? 0 : U('Index/index');
				parent::_sysLog('modify', "编辑:$item");
				parent::_message('success', '更新成功', $jumpUri);
			}else
			{
				parent::_message('error', '更新失败');
			}
		}else{
			parent::_message('error', $this->dao->getError());
		}
	}

	/**
     * 批量操作
     *
     */
	public function doCommand()
	{
		parent::_checkPermission('Admin_command');
		if(getMethod() == 'get'){
			$operate = trim($_GET['operate']);
		}elseif(getMethod() == 'post'){
			$operate = trim($_POST['operate']);
		}else{
			parent::_message('error', '只支持POST,GET数据');
		}
		switch ($operate){
			case 'delete': parent::_delete();break;
			case 'update': parent::_batchModify($_POST, array('realname')); break;
			default: parent::_message('error', '操作类型错误') ;
		}
	}
}
