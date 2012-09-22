<?php
/**
 *
 * Global(全局)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version         $ID: index.php 2011-7-28 Administrator tengzhiwangluoruanjian$

 */

class GlobalAction extends Action
{
	public $globalMenu, $sysConfig;
	/**
	 * 初始化
	 * 全局类别，全局菜单，系统常量
	 */
	function _initialize()
	{
		//取配置
		if(fileExit(APP_CONFIG.'/cms.config.php')){
			$this->sysConfig = @require_once(APP_CONFIG.'/cms.config.php');
		}else{
			$this->sysConfig = M('Config')->where('id=1')->find();
		}
		//检测是否停止
		$this->assign('sysConfig', $this->sysConfig);
		if($this->sysConfig['web_status'] == 1){
			$this->display('Public:stop');
			exit();
		}

		//取导航
		$this->globalMenu = getCache('Menu');
		$this->assign('globalMenu', $this->globalMenu);

		//导入函数
		Load('extend');
		//分页
		import("ORG.Util.Page");
		//cookie
		import("ORG.Util.Cookie");
	}

	/**
	 * 设置seo信息
	 * Enter description here ...
	 */
	protected function setSeoInfo() {
	     //seo
		$seoInfo = @require_once(APP_CONFIG.'/seo.config.php');
		$this->assign('seoinfo', $seoInfo[MODULE_NAME."_".ACTION_NAME]);
	}

	/**
	 * 数据列表
	 *
	 * @param $conditions 条件
	 * @param $orders 排序
	 * @param $listRows 每页显示数量
	 * @param $joind 是否表关联
	 * @param $table 关联表
	 * @param $join
	 * @param $fields 取字段
	 */
	public function getList($conditions = '', $orders = '' , $listRows = '')
	{
		$condition = !empty($conditions) ? $conditions : '' ;
		$pageCount = $this->dao->where($condition)->count();
		$listRows = empty($listRows) ? 15 : $listRows;
		$orderd = empty($orders) ? 'id DESC' : $orders;
		$paged = new page($pageCount, $listRows);
		$dataContentList = $this->dao->Where($condition)->Order($orderd)->Limit($paged->firstRow.','.$paged->listRows)->select();
		$pageContentBar = $paged->show();
		$this->assign('dataContentList', $dataContentList);
		$this->assign('pageContentBar', $pageContentBar);
		$this->display();
	}
	
	
	/**
	 * 
	 * 得到指定的前N条记录
	 * @param unknown_type $model
	 * @param unknown_type $conditions
	 * @param unknown_type $orders
	 * @param unknown_type $listRows
	 */
	public function getLimitList($model, $conditions = '', $orders = '' , $listRows = '', $fields='')
	{
		$condition = !empty($conditions) ? $conditions : '' ;
		$listRows = empty($listRows) ? 15 : $listRows;
		$orderd = empty($orders) ? 'id DESC' : $orders;
		$selecTmp = $model->Where($condition)->Order($orderd)->Limit($listRows);
		if (!empty($fields)) {
			$selecTmp = $selecTmp->Field($fields);
		}
		$dataContentList = $selecTmp->select();
		return $dataContentList;
	}

	
	/**
	 +----------------------------------------------------------
	 * 根据表单生成查询条件
	 * 进行列表过滤
	 +----------------------------------------------------------
	 * @access protected
	 +----------------------------------------------------------
	 * @param Model $model 数据对象
	 * @param HashMap $map 过滤条件
	 * @param string $sortBy 排序
	 * @param boolean $asc 是否正序
	 +----------------------------------------------------------
	 * @return void
	 +----------------------------------------------------------
	 * @throws ThinkExecption
	 +----------------------------------------------------------
	 */
	protected function _list($model, $map, $sortBy = '', $asc = false) {
		//排序字段 默认为主键名
		if(isset($_GET ['_order'])) {
			$order = $_GET ['_order'];
		} else {
			$order = ! empty($sortBy) ? $sortBy : $model->getPk();
		}
		//排序方式默认按照倒序排列
		//接受 sost参数 0 表示倒序 非0都 表示正序
		if(isset($_GET ['_sort'])) {
			$sort = $_GET ['_sort'] ? 'asc' : 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}
		//取得满足条件的记录数
		$count = $model->where($map)->count('id');
		if($count > 0) {
			//import("ORG.Util.Page");//导入分页类
			//创建分页对象
			if(! empty($_GET ['listRows'])) {
				$listRows = $_GET ['listRows'];
			} else {
				$listRows = '';
			}
			$p = new Page($count, $listRows);
			//分页查询数据

			$voList = $model->where($map)->order("`" . $order . "` " . $sort)->limit($p->firstRow . ',' . $p->listRows)->select();
			//echo $model->getlastsql();
			//分页跳转的时候保证查询条件
			foreach($map as $key => $val) {
				if(! is_array($val)) {
					$p->parameter .= "$key=" . urlencode($val) . "&";
				}
			}
			//分页显示
			$page = $p->show();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$this->assign('list', $voList);
			$this->assign('sort', $sort);
			$this->assign('order', $order);
			$this->assign('sortImg', $sortImg);
			$this->assign('sortType', $sortAlt);
			$this->assign("page", $page);
		}
		Cookie::set('_currentUrl_', __SELF__);
		return;
	}
	
	
	
	/**
	 * 数据列表,表关联
	 *
	 * @param $conditions 条件
	 * @param $orders 排序
	 * @param $listRows 每页显示数量
	 * @param $joind 是否表关联
	 * @param $table 关联表
	 * @param $join
	 * @param $fields 取字段
	 */
	public function getJoinList($conditions = '', $orders = '' , $listRows = '', $table = '', $join = '', $fields = '')
	{
		$condition = !empty($conditions) ? $conditions : '' ;
		$pageCount = $this->dao->Where($condition)->Table($table)->Join($join)->Field($fields)->count();
		$listRows = empty($listRows) ? 15 : $listRows;
		$orderd = empty($orders) ? 'id DESC' : $orders;
		$paged = new page($pageCount, $listRows);
		$dataContentList = $this->dao->Table($table)->join($join)->field($fields)->Where($condition)->Order($orderd)->Limit($paged->firstRow.','.$paged->listRows)->select();
		$pageContentBar = $paged->show();
		$this->assign('dataContentList', $dataContentList);
		$this->assign('pageContentBar', $pageContentBar);
		$this->display();
	}

public function getSqlList($searchSql = '', $orders = '' , $listRows = '')
	{
		$searchSql = preg_replace("/_DBPREFIX_/", C('DB_PREFIX'), $searchSql);
		$Model = new Model();
		$countSql = "select count(*) as totalCount from (".$searchSql.") tc";
		$count = $Model->query($countSql);
		$listRows = empty($listRows) ? 15 : $listRows;
		//import("ORG.Util.Page");//导入分页类
		$paged = new page($count[0]['totalCount'], $listRows);
		
		$orderd = empty($orders) ? 'id DESC' : $orders;
		$searchSql.=" Order by ".$orderd;
		$searchSql.=" Limit {$paged->firstRow},{$paged->listRows}";
		
		$dataContentList = $Model->query($searchSql);
		$pageContentBar = $paged->show();
		$this->assign('dataContentList', $dataContentList);
		$this->assign('pageContentBar', $pageContentBar);
		Cookie::set('_currentUrl_', __SELF__);
		
		$this->display();
	}
	
	
	/**
	 * 数据集
	 *
	 * @param $conditions 条件
	 *
	 */
	public function getDetail($conditions = '', $viewCount = false, $model, $templateFile)
	{
		$model = empty($model) ? $this->dao : $model;
		empty($conditions) && self::_message('errorUri', '查询条件丢失', U('Index/index'));
		$contentDetail = $model->Where($conditions)->find();
		empty($contentDetail) && self::_message('errorUri', '记录不存在', U('Index/index'));
		//更新查看次数
		$viewCount && $model->setInc($viewCount, $conditions);
		$this->assign('contentDetail', $contentDetail);
		$templateFile = empty($contentDetail['template']) ? $templateFile : $contentDetail['template'];
		if (empty($templateFile)) {
			$templateFile = '';
		}
		$this->display($templateFile);
	}

	/**
	 * 数据集,表关联
	 * 此处查询条件可能为数组
	 * @param $conditions 条件
	 * @param $joind 是否表关联
	 * @param $table 关联表
	 * @param $join
	 * @param $fields 取字段
	 */
	public function getJoinDetail($conditions = '', $viewCount = false, $table = '', $join = '', $fields = '')
	{
		empty($conditions) && self::_message('errorUri', '查询条件丢失', U('Index/index'));

		$condition1 = is_array($conditions) ? $conditions[0] : $conditions;
		$condition2 = is_array($conditions) ? $conditions[1] : $conditions;

		$contentDetail = $this->dao->Table($table)->Join($join)->Field($fields)->Where($condition1)->find();
		empty($contentDetail) && self::_message('errorUri', '记录不存在', U('Index/index'));
		//更新查看次数
		$viewCount && $this->dao->setInc($viewCount, $condition2);
		$this->assign('contentDetail', $contentDetail);
		$this->display($contentDetail['template']);
	}
	
	public function getSqlDetail($searchSql = '', $id, $viewCount = 'view_count')
	{
		empty($searchSql) && self::_message('errorUri', '查询条件丢失', U('Index/index'));
		$searchSql = preg_replace("/_DBPREFIX_/", C('DB_PREFIX'), $searchSql);
		
		$Model = new Model();
		$contentDetail = $Model->query($searchSql);
		empty($contentDetail) && self::_message('errorUri', '记录不存在', U('Index/index'));
		$contentDetail = $contentDetail[0];
		//更新查看次数
		$detailModel = D($this->getActionName());
		$viewCount && $detailModel->setInc($viewCount, "id={$id}");
		$this->assign('contentDetail', $contentDetail);
		//seo
		$seoInfo = array();
		$companyName= $this->sysConfig['company_name'];
		$seoInfo['seotitle']=$contentDetail['title'].'_'.$companyName;
		$seoInfo['seokeyword']=$contentDetail['keyword'].'_'.$companyName;
		$seoInfo['seodescription']=$contentDetail['description'].'_'.$companyName;
		$this->assign('seoinfo', $seoInfo);
		
		
		$template = empty($contentDetail['template']) ? '': $contentDetail['template'];
		$this->display($template);
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
	protected function _message($type = 'success', $content = '更新成功', $jumpUrl = __URL__, $time = 30, $ajax = false)
	{
		$jumpUrl = empty($jumpUrl) ? __URL__ : $jumpUrl ;
		switch ($type){
			case 'success':
				$this->assign('jumpUrl', $jumpUrl);
				$this->assign('waitSecond', $time);
				$this->success($content, $ajax);
				break;
			case 'error':
				$this->assign('jumpUrl', 'javascript:history.back(-1);');
				$this->assign('waitSecond', $time);
				$this->assign('error', $content);
				$this->error($content, $ajax);
				break;
			case 'errorUri':
				$this->assign('jumpUrl', $jumpUrl);
				$this->assign('waitSecond', $time);
				$this->assign('error', $content);
				$this->error($content, $ajax);
				break;
			default:
				die('error type');
				break;
		}
	}
	
	/**
	 * 
	 * 获取指定类型的广告
	 * @param unknown_type $categoryId
	 */
    protected function getAd($categoryId) {
    	$adList = getCache('Ad');
    	$returnAd = array();
    	
    	$categoryId = empty($categoryId) ? 33 : intval($categoryId);
    	foreach ($adList as $row) {
    		if ($row['category_id'] == $categoryId) {
    			array_push($returnAd, $row);
    		}
    	}
    	return $returnAd;
    }
	
}

