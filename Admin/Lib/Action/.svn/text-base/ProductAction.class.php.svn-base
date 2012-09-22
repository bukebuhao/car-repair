<?php 
/**
 * 
 * Product(产品)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: ProductAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class ProductAction extends CommonAction
{
    protected $category, $dao;
    function _initialize()
    {
        parent::_initialize();
        //取所有分类，过滤出新闻模块主ID
        $category = M('Category')->Order('display_order DESC,id DESC')->select();
        foreach ((array)$category as $row){
            if($row['module'] == 'Product'){
                $parentId = $row['id'];
            }
        }
        //取主ID下属分类
        $this->assign('parentId', $parentId);
        $this->assign('category', $category);
        $this->dao = D('Product');
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
        $recommend = intval($_GET['recommend']);
        $categoryId = intval($_GET['categoryId']);
        $status =  intval($_GET['status']);
        $istop = intval($_GET['istop']);
        $createTime = trim($_GET['createTime']);
        $createTime1 = trim($_GET['createTime1']);
        $viewCount = intval($_GET['viewCount']);
        $viewCount1 = intval($_GET['viewCount1']);
        $setOrder = setOrder(array(array('viewCount','a.view_count'), 'a.id'), $orderBy, $orderType, 'a');
        $setTime = setTime($createTime, $createTime1);
        $setViewCount = setViewCount($viewCount, $viewCount1);
        $pageSize = intval($_GET['pageSize']);
        $title &&  $condition['a.title'] = array('like', '%'.$title.'%');
        $recommend &&  $condition['a.recommend'] = array('eq', $recommend);
        $categoryId &&  $condition['a.category_id'] = array('eq', $categoryId);
        $status && $condition['a.status'] = array('eq', $status);
        $istop &&  $condition['a.istop'] = array('eq', $istop);
        $createTime1 && $condition['a.create_time'] = array('between', $setTime);
        $viewCount1 && $condition['a.view_count'] = array('between', $setViewCount);
        $count = $this->dao->where($condition)->count();
        $listRows = empty($pageSize) || $pageSize>100 ? 15 : $pageSize ;
        $p = new page($count,$listRows);
        $dataList = $this->dao->Table(C('DB_PREFIX').'product a')->Join(C('DB_PREFIX').'category b on a.category_id=b.id')->Field('a.*,b.title as category')->Order($setOrder)->Where($condition)->Limit($p->firstRow.','.$p->listRows)->select();
        $page = $p->show();
        if($dataList !== false)
        {
            $this->assign('page', $page);
            $this->assign('dataList', $dataList);
        }
        parent::_sysLog('index');
        $this->display();
    }

    /**
     * 写入
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
        parent::_checkPermission('Product_insert');
        if($daoCreate = $this->dao->create())
        {
            $style = createStyle($_POST);
            $this->dao->user_id = parent::_getAdminUid();
            $this->dao->username = parent::_getAdminName();
            $this->dao->title_style = $style['title_style'];
            $this->dao->title_style_serialize = $style['title_style_serialize'];
            $uploadFile = upload($this->getActionName());
            if ($uploadFile)
            {
                $this->dao->attach = 1;
                $this->dao->attach_image = formatAttachPath($uploadFile[0]['savepath']) . $uploadFile[0]['savename'];
                $this->dao->attach_thumb = fileExit($uploadFile[0]['savepath'] . splitThumb($uploadFile[0]['savename'])) ? formatAttachPath($uploadFile[0]['savepath']) . splitThumb($uploadFile[0]['savename']) : '' ;

            }
            $daoAdd = $this->dao->add();
            if(false !== $daoAdd)
            {
                parent::_tags('insert', $_POST['tags'], $daoAdd);
                parent::_sysLog('insert', "录入:$daoAdd");
                parent::_message('success', '录入成功');
            }else
            {
                parent::_message('error', '录入失败');
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
        $record = $this->dao->where('id='.$item)->find();
        if (empty($item) || empty($record)) parent::_message('error', '记录不存在');
        $this->assign('vo', $record);
        $this->display();
    }

    /**
     * 提交编辑
     *
     */
    public function doModify()
    {
        parent::_checkPermission('Product_modify');
        parent::_setMethod('post');
        $item = intval($_POST['id']);
        empty($item) && parent::_message('error', '记录不存在');
        if($daoCreate = $this->dao->create())
        {
            $style = createStyle($_POST);
            $this->dao->title_style = $style['title_style'];
            $this->dao->title_style_serialize = $style['title_style_serialize'];
            $uploadFile = upload($this->getActionName());
            if ($uploadFile)
            {
                $this->dao->attach = 1;
                $this->dao->attach_image = formatAttachPath($uploadFile[0]['savepath']) . $uploadFile[0]['savename'];
                $this->dao->attach_thumb = fileExit($uploadFile[0]['savepath'] . splitThumb($uploadFile[0]['savename'])) ? formatAttachPath($uploadFile[0]['savepath']) . splitThumb($uploadFile[0]['savename']) : '' ;

                deleteUploadFile($this->upload.$_POST['old_image']);
                deleteUploadFile($this->upload.$_POST['old_thumb']);
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
	 * 订单
	 *
	 */
    public function order()
    {
        parent::_message('error', '暂时不提供该功能！');
    	parent::_checkPermission('Product_order');
        $condition = array();
        $realname = formatQuery($_GET['realname']);
        $orderBy = trim($_GET['orderBy']);
        $orderType = trim($_GET['orderType']);
        $status =  intval($_GET['status']);
        $setOrder = setOrder(array('a.id'), $orderBy, $orderType);
        $pageSize = intval($_GET['pageSize']);
        $realname &&  $condition['realname'] = array('like', '%'.$realname.'%');
        $status && $condition['status'] = array('eq', $status);
        $dao = D("Order");
        $count = $this->dao->where($condition)->count();
        $listRows = empty($pageSize) || $pageSize> 100 ? 15 : $pageSize ;
        $p = new page($count, $listRows);
        $dataList = $this->dao->Table(C('DB_PREFIX').'order a')->Join(C('DB_PREFIX').'product b on a.title_id=b.id')->Field('a.*,b.title')->Order($setOrder)->Where($condition)->Limit($p->firstRow.','.$p->listRows)->select();
        $page = $p->show();
        if($dataList !== false){
            $this->assign('dataList', $dataList);
            $this->assign('pageBar', $page);
        }
        parent::_sysLog('index');
        $this->display();
    }

    /**
	 * 提交订单信息
	 *
	 */
    public function orderDetail()
    {
    	parent::_message('error', '暂时不提供该功能！');
        parent::_checkPermission('Product_order');
        $item = intval($_GET["id"]);
        $dao = D("Order");
        $record = $this->dao->Table(C('DB_PREFIX').'order a')->Join(C('DB_PREFIX').'product b on a.title_id=b.id')->Field('a.*,b.title')->where('a.id='.$item)->find();
        if(empty($item) || empty($record)) parent::_message('error', '记录不存在', U('Product/order'));
        if($record['status'] == 0){
            $update['status'] = 1;
            $this->dao->Where('id='.$item)->save($update);
        }
        $this->assign('vo', $record);
        parent::_sysLog('index');
        $this->display();
    }

    /**
	 * 订单查看/编辑
	 *
	 */
    public function orderModify()
    {
  		parent::_message('error', '暂时不提供该功能！');
    	parent::_checkPermission('Product_order');
        parent::_setMethod('post');
        $item = intval($_POST['id']);
        empty($item) && parent::_message('error','记录不存在', U('Product/order'));;
        $dao = D("Order");
        if($daoCreate = $this->dao->create())
        {
            $daoSave = $this->dao->save();
            if(false !== $daoSave)
            {
                parent::_sysLog('modify');
                parent::_message('success', '更新完成', U('Product/order'));
            }else
            {
                parent::_message('error', '更新失败', U('Product/order'));
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
        parent::_checkPermission('Product_command');
        if(getMethod() == 'get'){
            $operate = trim($_GET['operate']);
        }elseif(getMethod() == 'post'){
            $operate = trim($_POST['operate']);
        }else{
            parent::_message('error', '只支持POST,GET数据');
        }
        $newCategory = intval($_POST['newCategory']);
        switch ($operate){
            case 'delete': parent::_delete(0, 0, array('attach_image', 'attach_thumb'));break;
            //case 'orderDelete': parent::_delete('Order', U('Product/order'));break;
            case 'recommend': parent::_recommend('set');break;
            case 'unRecommend': parent::_recommend('unset');break;
            case 'setTop': parent::_setTop('set');break;
            case 'unSetTop': parent::_setTop('unset');break;
            case 'setStatus': parent::_setStatus('set');break;
            case 'unSetStatus': parent::_setStatus('unset');break;
            case 'update': parent::_batchModify($_POST, array('title'));break;
            case 'move': parent::_move($newCategory);break;
            default: parent::_message('error', '操作类型错误') ;
        }
    }
}
