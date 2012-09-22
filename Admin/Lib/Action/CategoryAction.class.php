<?php 
/**
 * 
 * Category(分类)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: CategoryAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class CategoryAction extends CommonAction
{
    protected $dao, $category;
    function _initialize()
    {
        parent::_initialize();
        $getData = getCache('Module');
        //过滤无关模块
        foreach ((array)$getData as $rw){
            if(!in_array($rw['module_name'], array('Menu','Tags','Comment','Admin','Theme','AdminRole','Module','Config','Database','AdminLog','Tools','Label'))){
                $data['module'][] = $rw;
            }
        }
        $this->assign($data);
        $this->dao = D('Category');
        $this->category = $this->dao->Order('display_order DESC,id DESC')->select();

    }

    /**
     * 列表
     *
     */
    public function index()
    {
        parent::_checkPermission();
        $dataList = getCategory($this->category);
        if($dataList != false)
        {
            $this->assign('dataList',$dataList);
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
        parent::_checkPermission('Category_insert');
        $dataList = getCategory($this->category);
        $parentId = trim($_GET['parentId']);
        $this->assign('parentId', $parentId);
        $this->assign('dataList', $dataList);
        $this->display();
    }

    /**
     * 提交录入
     *
     */
    public function doInsert()
    {
        parent::_checkPermission('Category_insert');
        parent::_setMethod('post');
        if($daoCreate = $this->dao->create())
        {
            //清空不是根目录的所属模块
            $this->dao->module = empty($_POST['parent_id']) ? $_POST['module'] : '' ;
            $daoAdd = $this->dao->add();
            if(false !== $daoAdd)
            {
                writeCache('Category');
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
        parent::_checkPermission('Category_modify');
        $item = intval($_GET['id']);
        $record = $this->dao->Where('id='.$item)->find();
        if(empty($item) || empty($record)) parent::_message('error', '记录不存在');
        $dataList = getCategory($this->category);
        $this->assign('vo', $record);
        $this->assign('dataList', $dataList);
        $this->display();
    }

    /**
     * 提交编辑
     *
     */
    public function doModify()
    {
        parent::_checkPermission('Category_modify');
        parent::_setMethod('post');
        $item = intval($_POST['id']);
        $parentId = intval($_POST['parent_id']);
        empty($item) && parent::_message('error', '记录不存在');
        //检测上级分类是否合法
        $this->parentTrue($item, $parentId);
        if($daoSave = $this->dao->create())
        {
            //清空不是根目录的所属模块
            $this->dao->module = empty($_POST['parent_id']) ? $_POST['module'] : '' ;
            $daoSave = $this->dao->save();
            if(false !== $daoSave)
            {
                writeCache('Category');
                parent::_sysLog('modify', "编辑:$item");
                parent::_message('success', '编辑完成');
            }else
            {
                parent::_message('error', '编辑失败');
            }
        }else
        {
            parent::_message('error', $this->dao->getError());
        }
    }

    /**
     * 检测上级分类是否合法
     * 父类不能为本身，父类的父类不能为该子类
     * @param unknown_type $item
     * @param unknown_type $parentId
     */
    public function parentTrue($item = 0, $parentId = 0)
    {
        $subCategory = getCategory($this->category, $item);
        if(empty($subCategory)){
            $getCategory[] = $item;
        }else{
            foreach ((array)$subCategory as $row){
                $getCategory[] = $row['id'];
            }
            //将本身ID加入检测对象
            array_push($getCategory, $item);
        }
        if (in_array($parentId, $getCategory)) parent::_message('error', '所选择的上级分类不能是当前分类或者当前分类的下级分类!', 0, 5);
    }

    /**
     * 批量操作
     *
     */
    public function doCommand()
    {
        parent::_checkPermission('Category_command');
        if(getMethod() == 'get'){
            $operate = trim($_GET['operate']);
        }elseif(getMethod() == 'post'){
            $operate = trim($_POST['operate']);
        }else{
            parent::_message('error', '只支持POST,GET数据');
        }
        switch ($operate){
            case 'delete':
                $idArr = $_POST['id'];
                $deleteIds = '';
                if(!empty($idArr)){
                    foreach ((array)$idArr as $key=>$row){
                        $check = $this->dao->Where('id='.$row)->find();
                        if($check['protected'] == 1){
                            parent::_message('error', "{$row} 分类受保护中，删除会导致其它模块不能正常运行", 0, 5);
                        }else{
                            $subCategory = getCategory($this->category, $row);
                            foreach ((array)$subCategory as $subId){
                                $imlodeSub[] = $subId['id'];
                            }
                            $implodeArr = implode(',', $imlodeSub);
                            $deleteArr = empty($implodeArr) ? $row :  $implodeArr.','.$row;
                            $this->dao->Where("id IN({$deleteArr})")->delete();

                            self::_sysLog('delete', "删除: {$deleteArr}");

                            $deleteIds .= " 类别{$key}:".$deleteArr;
                            if ($key == count($idArr)-1) {
                            	parent::_message('success', "{$deleteIds} 删除完成");
                            }
                        }
                    }

                }else {
                    parent::_message('error', '未选择要删除的记录') ;
                }
                break;
            case 'setStatus': parent::_setStatus('set');break;
            case 'unSetStatus': parent::_setStatus('unset');break;
            case 'update': parent::_batchModify($_POST, array('display_order'));break;
            default: parent::_message('error', '操作类型错误') ;
        }
    }
}
