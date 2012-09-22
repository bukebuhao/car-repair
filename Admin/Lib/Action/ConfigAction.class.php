<?php
/**
 * 
 * Config(系统配置)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: ConfigAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class ConfigAction extends CommonAction
{
    private $dbconfig,$dao;
    function _initialize()
    {
        parent::_initialize();
        $this->dbconfig = getContent('db.config.php', APP_CONFIG);
        $this->dao = D('Config');
    }

    /**
	 * 系统配置
	 *
	 */
    public function index()
    {
        parent::_checkPermission();
        $record = $this->dao->where('id=1')->find();
        $this->assign('vo', $record);
        parent::_sysLog('index');
        $this->display();
    }

    /**
     * 提交编辑
     *
     */
    public function doModify()
    {
        parent::_checkPermission('Config_modify');
        parent::_setMethod('post');
        $id = intval($_POST['id']);
        empty($id) && parent::_message('error', '记录不存在');
        if($daoCreate = $this->dao->create())
        {
            $this->dao->sys_log_ext = empty($_POST['sys_log_ext']) ? '' : implode(',', $_POST['sys_log_ext']);
            $daoSave = $this->dao->save();
            if(false !== $daoSave)
            {
                $_POST['sys_log_ext'] = empty($_POST['sys_log_ext']) ? '' : implode(',', $_POST['sys_log_ext']);
                configCache((array)$_POST);
                parent::_sysLog('modify', "编辑系统配置");
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
     * 内核配置
     *
     */
    public function core()
    {
        parent::_checkPermission('Config_core');
        $this->assign(getContent('app.config.php'));
        $this->display();
    }

    /**
     * 提交内核配置
     *
     */
    public function doCore()
    {
        parent::_setMethod('post');
        parent::_checkPermission('Config_coreModify');
        $config = getContent('app.config.php');
        $configHeader = "<?php\n/** \n*  数据库配置文件\n*\n* @package      	companycms\n* @author     zxz (QQ:396774497)\n* @copyright  Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)\n* @license    http://www.tengzhiinfo.com/license.txt\n* @version    \$ID: db.config.php 2011-7-28 Administrator$\n*/\n\n\nreturn array(\r\n";
        $configFooter .= ');';
        $config['APP_DEBUG'] = trim($_POST['APP_DEBUG']);
        $config['APP_VESION'] = '1.0';
        $config['DATA_CACHE_TYPE'] = 'file';
        $config['DATA_CACHE_TIME'] = 600;
        $config['URL_ROUTER_ON'] = trim($_POST['URL_ROUTER_ON']);
        $config['URL_DISPATCH_ON'] = trim($_POST['URL_DISPATCH_ON']);
        $config['URL_MODEL'] = trim($_POST['URL_MODEL']);
        $config['URL_PATHINFO_MODEL'] = trim($_POST['URL_PATHINFO_MODEL']);
        $config['URL_PATHINFO_DEPR'] = trim($_POST['URL_PATHINFO_DEPR']);
        $config['URL_HTML_SUFFIX'] = trim($_POST['URL_HTML_SUFFIX']);
        $config['TMPL_CACHE_ON'] = trim($_POST['TMPL_CACHE_ON']);
        $config['TMPL_CACHE_ON'] = trim($_POST['TMPL_CACHE_ON']);
        $config['TOKEN_ON'] = trim($_POST['TOKEN_ON']);
        $config['TOKEN_NAME'] = trim($_POST['TOKEN_NAME']);
        $config['TMPL_CACHE_ON'] = trim($_POST['TMPL_CACHE_ON']);
        $config['TMPL_CACHE_TIME'] = trim($_POST['TMPL_CACHE_TIME']);
        foreach((array)$config as $key => $value)
        {
            if($value === true || $value == 'true'){
                $configBody .= "    '".$key."' => true,\r\n";
            }else if($value === false || $value == 'false'){
                $configBody .= "    '".$key."' => false,\r\n";
            } else if(is_numeric($value)){
                $configBody .= "    '".$key."' => $value,\r\n";
            }else{
                $configBody .= "    '".$key."' => '$value',\r\n";
            }
        }

        $configData = $configHeader . $configBody . $configFooter;
        putContent($configData, 'app.config.php', APP_CONFIG);
        parent::_sysLog('modify', "编辑内核配置");
        parent::_message('success', '内核更新成功');
    }
}
