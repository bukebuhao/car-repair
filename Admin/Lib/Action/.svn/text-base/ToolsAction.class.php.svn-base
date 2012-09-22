<?php
/**
 * 
 * Tools(工具箱)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: ToolsAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class ToolsAction extends CommonAction
{
    function _initialize()
    {
        parent::_initialize();
        parent::_checkPermission('Tools');
        $this->assign($data);
    }

    /**
	 * 系统配置
	 *
	 */
    public function index()
    {
        parent::_sysLog('index');
        $this->display();
    }

    /**
     * 更新缓存
     *
     */
    public function doCache()
    {
        parent::_setMethod('post');
        $cacheModule = $_POST['dataArr'];
        $array = explode(',', $cacheModule);
        //配置缓存
        if(in_array('config', $array)){
            configCache();
            $msgArr = '执行配置缓存.<br />';
        }

        if(in_array('role', $array)){
            writeCache('AdminRole');
            $msgArr .= '执行角色缓存.<br />';
        }
        if(in_array('category', $array)){
            writeCache('Category', 0, 'id');
            $msgArr .= '执行分类缓存.<br />';
        }
        if(in_array('menu', $array)){
            writeCache('Menu', 0, 'id');
            $msgArr .= '执行菜单缓存.<br />';
        }
        if(in_array('link', $array)){
            writeCache('Link', 0, 'display_order DESC,id');
            $msgArr .= '执行链接缓存.<br />';
        }
        if(in_array('tags', $array)){
            writeCache('Tags', 0, 'total_count DESC');
            $msgArr .= '执行Tags缓存.<br />';
        }
        if(in_array('ad', $array)){
            writeCache('Ad', 0, 'display_order DESC,id');
            $msgArr .= '执行广告缓存.<br />';
        }
        if(in_array('module', $array)){
            writeCache('Module');
            $msgArr .= '执行模块缓存.<br />';
        }
        if(in_array('core', $array)){
            clearCore();
            $msgArr .= '执行核心缓存.<br />';
        }
        $msgArr .= '<br /><span style="color:#00F">执行完成..........................</span>';
        parent::_sysLog('update', '更新缓存');
        die($msgArr);
    }

    /**
     * 重新统计Tags计数
     *
     */
    public function countTags()
    {
    	$getModule = trim($_GET['module']);
        $pageSize = intval($_GET['pageSize']);
        if(empty($getModule)){
            parent::_message('error', '请选择Tags模块');
        }
        $pageSize = empty($pageSize) ? 20 : $pageSize;
        $daoTags = M('Tags');
        $daoTagsCache = M('TagsCache');
        $start = $_GET['start'];
        $countTags = $daoTags->Where("module='{$getModule}'")->count();
        empty($countTags) &&  parent::_message('success',"更新成功", U('Tools/index'));
        $totalPage = ceil($countTags/$pageSize);

        $page = intval($_GET['page']);
        $page = empty($page) || $page < 0 ? 0 : $page ;
        $next = $page + 1;
        $limitA = $pageSize * $page;
        $limitB = ($pageSize * $page) + $pageSize;
        $dataList = $daoTags->Limit("$limitA,$pageSize")->Where("module='{$getModule}'")->select();

        foreach ((array)$dataList as $row){
            $countTC = $daoTagsCache->Where("module='{$getModule}' and tag_name='{$row['tag_name']}'")->count();
            $daoTags->execute("UPDATE __TABLE__ SET total_count={$countTC} where id={$row['id']}");
        }
        if($next < $totalPage){
            parent::_message('success',"正在更新{$getModule}: $limitA - $limitB 。共有 $countTags 条记录需要更新", U('Tools/countTags',array('page' => $next, 'module' => $getModule, 'pageSize' => $pageSize)));
        }else{
            parent::_sysLog('update', '重新统计Tags计数');
            parent::_message('success',"更新完成", U('Tools/index'));
        }
    }
}
