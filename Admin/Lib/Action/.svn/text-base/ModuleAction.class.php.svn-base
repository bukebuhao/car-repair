<?php 
/**
 * 
 * Module(模块)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: ModuleAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class ModuleAction extends CommonAction
{
    protected $db = NULL ,$dao;
    function _initialize()
    {
        parent::_initialize();
        $this->db = Db::getInstance();
        $this->dao = D('Module');
    }

    /**
     * 列表
     *
     */
    public function index()
    {
        parent::_checkPermission();
        $count = $this->dao->where($condition)->count();
        $dataList = $this->dao->Order('id DESC')->Where($condition)->select();
        foreach ((array)$dataList as $row){
            if($row['system_module'] == 1 && $row['status'] == 0){
                $system[] = $row;
            }elseif($row['system_module'] == 0 && $row['status'] == 0){
                $install[] = $row;
            }elseif($row['status'] == 1){
                $disble[] = $row;
            }
        }
        $system &&  $this->assign('system', $system);
        $install && $this->assign('install', $install);
        $disble && $this->assign('disble', $disble);
        $this->assign('count', $count);
        parent::_sysLog('index');
        $this->display();
    }

    /**
     * 录入
     *
     */
    public function install()
    {
    	parent::_message('error', '无法安装！');
        parent::_checkPermission();
        $this->display();
    }

    /**
     * 提交录入
     *
     */
    public function doInstall()
    {
        parent::_message('error', '无法安装！');
    	parent::_checkPermission('Module_install');
        parent::_setMethod('post');
        $dot = '/';
        $moduleName = trim($_POST['moduleName']);
        empty($moduleName) && die('模块名称必须填写');
        $ucModuleName = ucfirst($moduleName);

        //检测模块是否存在，如果存在，则安装停止
        $checkExit = $this->dao->Where("module_name='{$ucModuleName}'")->find();
        if($checkExit) die("系统中已经存在模块: $ucModuleName ，安装中止。如需要安装，请先 <a href=\"".U('Module/uninstall', array('id' => $checkExit['id']))."\" style=\"color:#F00\">卸载 {$ucModuleName} 模块 </a>");
        $installMsg = "安装开始.....................................<br />";
        $modulePath = CMS_DATA. $dot .'Module/'.$ucModuleName;
        //检测安装目录是否存在
        if(!is_dir($modulePath))die("{$modulePath} 安装目录不存在，无法安装");
        //测试基本安装文件是否存在
        $installConfig = $modulePath . '/config.php';
        !file_exists($installConfig) && die("$installConfig 安装配置文件不存在");
        $installMsg .= "检测配置文件:  $installConfig <br />";
        $configArray = @require($installConfig);
        $installSql = $modulePath .'/install.sql';
        !file_exists($installSql) && die("$installSql 数据库结构文件不存在");
        $getSqlFile = @file_get_contents($installSql);
        $installMsg .= "读取表结构:  $installSql <br />";
        $moduleAction = $modulePath.$dot.$ucModuleName.'Action.class.php';
        !file_exists($moduleAction) && die("$moduleAction 控制器不存在");
        $installMsg .= "检测控制器:  $moduleAction <br />";
        $moduleModel = $modulePath.$dot.$ucModuleName.'Model.class.php';
        !file_exists($moduleModel) && die("$moduleModel 模型不存在");
        $installMsg .= "检测模型:  $moduleModel <br />";

        //检测后台目录是否可写
        $adminPath = APP_PATH.$dot;
        $adminLibActionPath = $adminPath.'Lib/Action/';
        !is_writable($adminLibActionPath) && die("控制器目录：$adminLibActionPath 不可写，将无法写入文件，安装停止");
        $installMsg .= "检测控制器目录是否可写:  $adminLibActionPath <br />";
        $adminLibModelPath = $adminPath.'Lib/Model/';
        !is_writable($adminLibModelPath) && die("模型目录：$adminLibModelPath 不可写，将无法写入文件，安装停止");
        $installMsg .= "检测模型目录是否可写:  $adminLibModelPath <br />";
        $adminTplPath = $adminPath.'Tpl/default/';
        !is_writable($adminTplPath) && die("视图(模板)目录：$adminTplPath 不可写，将会无法写入模板，安装停止");
        $installMsg .= "检测视图(模板)目录是否可写:  $adminTplPath <br />";

        //复制文件
        @copy($moduleAction, $adminLibActionPath.$ucModuleName.'Action.class.php');
        $installMsg .= "复制控制器到目录:  {$adminLibActionPath}{$ucModuleName}.Action.class.php <br />";
        @copy($moduleModel, $adminLibModelPath.$ucModuleName.'Model.class.php');
        $installMsg .= "复制模型到目录:  {$adminLibModelPath}{$ucModuleName}.Model.class.php <br />";
        copyDir($modulePath , $adminTplPath, 1);
        $installMsg .= "复制视图(模板)到目录:  $adminTplPath <br />";

        //SQL拆分
        $sqlArray = splitsql($getSqlFile);
        //执行SQL
        foreach ((array)$sqlArray as $row){
            $this->db->query($row);
        }
        $installMsg .= "执行数据表结构: $installSql <br />";
        //创建数据
        $createArray['module_title'] = $moduleConf['title'];
        $createArray['module_name'] = $moduleConf['name'];
        $createArray['module_permission'] = empty($moduleConf['permission']) ? 'all' : $moduleConf['permission'];
        $createArray['build_version'] = $moduleConf['version'];
        $createArray['create_time'] = time();

        if($daoCreate = $this->dao->create($createArray)){
            $daoAdd = $this->dao->add();
            if(false !== $daoAdd)
            {
                parent::_sysLog('insert');
                $installMsg .= "写入模块相关数据<br />";
            }else
            {
                $installMsg .= "模块相关数据写入失败<br />";
            }
        }else{
            die($this->dao->getError());
        }
        $installMsg .= "安装完成.....................................<br />";

        if(!empty($moduleConf['permission'])){
            $installMsg .= "模块：$ucModuleName 需要设置权限，相应角色组才能正常管理 <a href=\"".U('AdminRole/index')."\" style=\"color:#F00\">现在设置权限</a>";
        }
        $installMsg .= "  <a href=\"".U($ucModuleName.'/index')."\" style=\"color:#F00\">管理此模块</a>";
        parent::_sysLog('update', "安装模块 :{$moduleName}");
        die($installMsg) ;
    }

    /**
     * 卸载模块
     *
     */
    public function uninstall()
    {
         parent::_message('error', '无法卸载！');
    	//parent::_checkPermission();
        $item = intval($_GET['id']);
        $getModule = $this->dao->Where("id='{$item}'")->find();
        empty($getModule) && parent::_message('errorUri', '系统中未找到此模块', U('Module/index'));
        if($getModule['system_module'] == 1) parent::_message('errorUri', '系统内置模块不允许卸载，否则会引起不必要的错误', U('Module/index'), 10);
        $this->assign('vo', $getModule);
        $this->display();
    }

    /**
     * 执行卸载
     *
     */
    public function doUninstall()
    {
       parent::_message('error', '无法卸载！');
    	parent::_checkPermission('Module_uninstall');
        parent::_setMethod('post');
        $dot = '/';
        $postModuleName = trim($_POST['moduleName']);
        $moduleName = ucfirst($postModuleName);
        $getModule = $this->dao->Where("module_name='{$moduleName}'")->find();
        if(empty($getModule) || empty($postModuleName))die('系统中未找到此模块');
        if($getModule['system_module'] == 1) die('系统内置模块不允许卸载，否则会引起不必要的错误');
        $uninstallMsg = "卸载开始.....................................<br />";

        //目录
        $adminPath = APP_PATH.$dot;
        $adminLibActionPath = $adminPath.'Lib/Action/';
        $adminLibModelPath = $adminPath.'Lib/Model/';

        //删除控制器
        @unlink($adminLibActionPath.$moduleName.'Action.class.php');
        $uninstallMsg .= "删除控制器:  {$adminLibActionPath}{$moduleName}Action.class.php <br />";
        //删除模型
        @unlink($adminLibModelPath.$moduleName.'Model.class.php');
        $uninstallMsg .= "删除模型:  {$adminLibModelPath}{$moduleName}Model.class.php <br />";

        //删除视图
        $tplPath = $adminPath.'Tpl/default/'.$moduleName;
        if(is_dir($tplPath)){
            $fileList = getFile($tplPath);
            foreach ((array)$fileList as $row){
                @unlink($tplPath . '/' . $row);
            }
            @rmdir($tplPath);
            $uninstallMsg .= "删除视图(模板)目录:  $tplPath <br />";
        }

        //清理表结构
        $modulePath = CMS_DATA.$dot.'Module/'.$moduleName;
        $uninstallSql = $modulePath.'/uninstall.sql';
        if(file_exists($uninstallSql)){
            $getSqlFile = @file_get_contents($uninstallSql);
            //SQL拆分
            $sqlArray = splitsql($getSqlFile);
            //执行SQL
            foreach ((array)$sqlArray as $row){
                $this->db->query($row);
            }
            $uninstallMsg .= "执行数据表结构: $uninstallSql <br />";
        }
        //清理模块信息
        $this->dao->where("module_name='{$moduleName}'")->delete();
        writeCache('Module');
        $uninstallMsg .= "卸载完成.....................................<br />";
        parent::_sysLog('update', "卸载完成 :{$postModuleName}");
        die($uninstallMsg) ;
    }

    /**
     * 编辑
     *
     */
    public function modify()
    {
        parent::_checkPermission();
        $dot = '/';
        $item = intval($_GET['id']);
        $getModule = $this->dao->where("id='{$item}'")->find();
        empty($getModule) && parent::_message('errorUri', '系统中未找到此模块', U('Module/index'));
        $modulePath = CMS_DATA.$dot.'Module/'.$getModule['module_name'];
        $installSql = $modulePath.'/install.sql';
        $uninstallSql = $modulePath.'/uninstall.sql';
        if(file_exists($installSql)){
            $installSql = file_get_contents($installSql);
            $this->assign('installSql', $installSql);
        }
        if(file_exists($uninstallSql)){
            $uninstallSql = file_get_contents($uninstallSql);
            $this->assign('uninstallSql', $uninstallSql);
        }
        $this->assign('vo', $getModule);
        $this->display();
    }

    /**
     * 提交编辑
     *
     */
    public function doModify()
    {
        parent::_checkPermission('Module_modify');
        parent::_setMethod('post');
        if($daoCreate = $this->dao->create())
        {
            $daoSave = $this->dao->save();
            if(false !== $daoSave)
            {
                writeCache('Module');
                parent::_sysLog('modify');
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
}
