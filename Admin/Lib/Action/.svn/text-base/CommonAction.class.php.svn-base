<?php
/**
 *
 * 全局action
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version         $ID: CommonAction.php 2011-8-11 Administrator tengzhiwangluoruanjian$

 */

class CommonAction extends Action {

	/**
	 *
	 * 全局的初始化信息
	 */
	function _initialize() {
		//获取动态的模块
		$modual = getCache('Module', array('left_menu'=>1, 'status'=>0), 'display_order DESC,id ASC ', 'menuModule');
        
		$this->assign('modualSider', $modual);
		 
		//加载扩展类
		Load('extend');
		//导入分页类
		import("ORG.Util.Page");
		//cookie
		import("ORG.Util.Cookie");
		//session
		import("ORG.Util.Session");
		
		
		$adminId = self::_getAdminUid();
        $username = self::_getAdminName();
        $roleId = self::_getRoleId();
        if (!$roleId || !$adminId) redirect(U('Public/login', array('jumpUri' => safe_b64encode($_SERVER['REQUEST_URI']))));
        $this->assign('adminId', $adminId);
        $this->assign('username', $username);
        $this->assign('security', $security);
      
        $appHost = empty($_SERVER['SERVER_NAME']) ? $_SERVER['SERVER_NAME'] : $_SERVER['HTTP_HOST'];
        $frontUrl = 'http://'.$appHost .dirname($_SERVER['SCRIPT_NAME']);
        $this->assign('frontUrl', $frontUrl);
        
	}

	/**
	 *
	 * 管理者信息-uid
	 */
	public static function _getAdminUid() {
		return Session::get("adminId");
	}

	/**
	 *
	 * 管理者信息-name
	 */
	public static function _getAdminName() {
		return Session::get("username");
	}

	/**
	 *
	 * 管理者信息-rid
	 */
	public static function _getRoleId() {
		return Session::get("roleId");
	}

	/**
	 * 验证用户的权限
	 * Enter description here ...
	 */
	protected  function _checkPermission($action = NULL) {
		$formatAction = strtolower($action);
		if(empty($action)) $formatAction = strtolower(MODULE_NAME.'_'.ACTION_NAME);
		$permission = Session::get('permission');
		if($permission != 'all')
		{
			$cacheRole = getCache("AdminRole");
			foreach ($cacheRole as $row) {
				if($row['id'] == $this->_getRoleId())
				{
					$arrPermission = explode(',', strtolower($row['role_permission']).',index_index');
				}
			}
			if(!in_array($formatAction, $arrPermission))
			{
				self::_message('error', '当前角色组无权限进行此操作，请联系管理员授权', 0, 20);
			}
		}
	}

	/**
	 *
	 * 验证提交方法
	 * @param unknown_type $acceptMethodName
	 */
	public function _setMethod($acceptMethodName) {
		$methodName = getMethod();
		if ($methodName != $acceptMethodName) {
			_message('error', '不允许的提交方法');
		}
	}

	/**
	 * 共同的验证
	 * Enter description here ...
	 */
	public function _validationModual() {
		//获取当前的modual
		$modualName = MODULE_NAME;
		if (empty ($modualName)) {
			$this->_message("error", "错误的URL请求");
			exit;
		}
		if(empty($_POST['id'])) {
			$this->_message("error", "无主健批量更新失败");
			exit;
		}
    }
	
    
    /**
     * 
     * 标签更新
     * @param unknown_type $operateType
     * @param unknown_type $tagContext
     * @param unknown_type $titleId
     */
    protected function _tags($operateType, $tagContext, $titleId) {
    	$tagArray = formatQuery(explode(",", $tagContext));
    	if (empty($tagArray) || count($tagArray) == 0) {
    		return;
    	}
    	
    	$titleId = formatQuery($titleId);
    	
    	//删除先前的tag
    	$tagsCache    =    M('TagsCache');
    	$tagsCache->where('title_id='.$titleId);
    	$tagsCache->delete();
    	
    	//添加tag
    	$dataArray = array();
    	foreach ($tagArray as $tagItem) {
    		$dataArray['module']=MODULE_NAME;
    		$dataArray['tag_name']=$tagItem;
    		$dataArray['title_id']=$titleId;
    		$tagsCache->add($dataArray);
    	}
    	
    	$dataCount = array();
    	foreach ($tagArray as $tagItem) {
    		$tagsCache->where("tag_name='".$tagItem."'");
    		$dataCount[$tagItem]=$tagsCache->count();
    	}
    	//统计tag
    	$tags    =    M('Tags');
    	foreach ($dataCount as $keyItem => $valueCount) {
    		$tags->where("tag_name='".$keyItem."'");
    		$id= $tags->getField("id");
    		$tagData['tag_name']=$keyItem;
    	    $tagData['module']=MODULE_NAME;
    		$tagData['total_count']=$valueCount;
    		if (empty($id)) { //新增
    			$tags->add($tagData);
    		} else { //更新
    			$tags->where("id=".$id);
    			$tags->save($tagData);
    		}
    	}
    }
    
    
    /**
	 * 
	 * 更新推荐
	 * @param $operateType
	 */
	protected function _recommend($operateType) {
		//批量更新
		if ($operateType == 'set') {
			$fileValue = 1;
		} else {
			$fileValue = 0;
		}
		$this->_setDataFieldByIDs('recommend', $fileValue);
	}
    
    /**
	 * 
	 * 是否置顶
	 * @param $operateType
	 */
	protected function _setTop($operateType) {
		//批量更新
		if ($operateType == 'set') {
			$fileValue = 1;
		} else {
			$fileValue = 0;
		}
		$this->_setDataFieldByIDs('istop', $fileValue);
	}
    /**
	 * 
	 * 更新状态
	 * @param $operateType
	 */
	protected function _setStatus($operateType) {
		//批量更新
		if ($operateType == 'set') {
			$fileValue = 0;
		} else {
			$fileValue = 1;
		}
		$this->_setDataFieldByIDs('status', $fileValue);
	}
	
	
    /**
	 * 
	 * 移动栏目
	 * @param $operateType
	 */
	protected function _move($newCategory) {
		$this->_setDataFieldByIDs('category_id', $newCategory);
	}
    
	/**
	 * 
	 * 更新状态
	 * @param $operateType
	 */
	protected function _setDataFieldByIDs($fieldName, $fileValue) {
		  //共同的验证
	     self::_validationModual();
		//获取当前的modual
		$modualName = MODULE_NAME;
		
		$ModualData    =    D($modualName);
		$ids = formatQuery($_POST['id']);
		//字段值
		$saveData[$fieldName] = $fileValue;
	
		$resultFlag = $ModualData->where("id in (".implode(",",$_POST['id']).")")->save($saveData);
		
		if ($resultFlag) {
			$this->_message("success", "批量更新".implode(",",$_POST['id']));
		} else {
			$this->_message("success", "没有更新".implode(",",$_POST['id']));
		}
		$this->_sysLog('批量更新'.$fieldName.implode(",",$_POST['id']));
	}

	
	/**
	 * 
	 * 批量更新 
	 * @param unknown_type $data post获取数据
	 * @param unknown_type $fieldArray 更新的字段
	 */
	protected function _batchModify($data, $fieldArray) {
	    //共同的验证
	     self::_validationModual();
	       
		//获取当前的modual
		$modualName = MODULE_NAME;
		//批量数据
		$dataList = array();
		foreach ($fieldArray as $field) {
			$fieldDataArray = $data[$field];
			$i=0;
			foreach ($fieldDataArray as $filedData) {
				$dataList[$i][$field] = formatQuery($filedData);
				$i++;
			}
		}
		 
		$ModualData    =    D($modualName);
		$ids = formatQuery($_POST['id']);
		$i=0;
		$resultFlag = false;
		//批量更新
		foreach ($ids as $id) {
			$resultFlag = $ModualData->where('id='.$id)->save($dataList[$i]) ? true : $resultFlag;
			$i++;
		}
		if ($resultFlag) {
			$this->_message("success", "批量更新数据".implode(",",$_POST['id']));
		} else {
			$this->_message("success", "没有更新数据".implode(",",$_POST['id']));
		}
		$this->_sysLog('批量更新数据'.implode(",",$_POST['id']));
		 
		 
	}

	/**
	 *
	 * 删除数据
	 */
	protected function _delete(){
		 //共同的验证
	    self::_validationModual();
		//获取当前的modual
		$modualName = MODULE_NAME;
			      
		//获取删除的id
		$id = formatQuery($_POST['id']);

		$ModualData    =    M($modualName);
		$ModualData->create();
		$result = true;
		if (!empty($id)) {
			foreach ($id as $idItem) {
				$result &= $ModualData->delete($idItem);
				if ($result) {
					$this->_sysLog('成功删除'.$idItem);
				} else {
					$this->_sysLog('删除失败'.$idItem);
				}
					
			}
			if ($result) {
				$this->_message("success", "删除成功!");
				return;
			}

		}
		$this->_message("error", "删除失败!");
		return;

	}


	/**
	 *
	 * 日志信息
	 * @param $modual
	 */
	public static function _sysLog($operateType, $addAction) {
		//兼容以前的
		$addAction = empty($addAction) ? $operateType : $addAction;
		
		$AdminLogModel    =    D("AdminLog");
		$dataArray['user_id']= self::_getAdminUid();
		$dataArray['username']= self::_getAdminName();
		$dataArray['action']= __SELF__;
		if (isset($addAction)) {
			$dataArray['action'].='('.$addAction.')';
		}
		$dataArray['ip']= get_client_ip();
		$dataArray['create_time']= microtime();
		if($vo = $AdminLogModel->create($dataArray)) {
			$result=$AdminLogModel->add();
			if(!$result){
				// 手动记录错误日志
				Log::write($AdminLogModel->getError(),Log::ERR);
			}
		} else{
			// 手动记录错误日志
			Log::write('管理员操作日志记录错误！',Log::ERR);
		}
	}

	/**
	 * 输出信息
	 *
	 * @param $type
	 * @param $content
	 * @param $jumpUrl
	 * @param $time
	 * @param $ajax
	 */
	protected function _message($type = 'success', $content = '更新成功', $jumpUrl, $time = 30, $ajax = false)
	{
		//$jumpUrl = empty($jumpUrl) ? __URL__ : $jumpUrl ;
		$jumpUrl = empty($jumpUrl) ? $_SERVER['HTTP_REFERER'] : $jumpUrl ;
		$jumpUrl = empty($jumpUrl) ? __URL__ : $jumpUrl ;
		switch ($type){
			case 'success':
				$this->assign('jumpUrl', $jumpUrl);
				$this->assign('waitSecond', $time);
				$this->success($content, $ajax);
				break;
			case 'error':
				$this->assign('jumpUrl', $_SERVER['HTTP_REFERER']);
				$this->assign('waitSecond', $time);
				$this->assign('error', $content);
				$this->error($content, $ajax);
				break;
			case 'redirect':
				$this->assign('waitSecond', $time);
                $this->redirect($jumpUrl);
				break;
			default:
				die('error type');
				break;
		}
	}


}