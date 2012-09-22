<?php 
/**
 * 
 * Index(后台首页)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: IndexAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */

class IndexAction extends CommonAction{
    function _initialize()
    {
        parent::_initialize();
        parent::_checkPermission();
    }

    /**
     * 后台管理首页
     *
     */
    public function index()
    {
    	$data['serverSoft'] = $_SERVER['SERVER_SOFTWARE'];
        $data['serverOs'] = PHP_OS;
        $data['phpVersion'] = PHP_VERSION;
        $data['phpUploadSize'] = ini_get('file_uploads') ? ini_get('upload_max_filesize'): '禁止上传';
        $data['serverUri'] = $_SERVER['SERVER_NAME'];
        $data['maxExcuteTime'] = ini_get('max_execution_time').' 秒';
        $data['maxExcuteMemory'] = ini_get('memory_limit');
        $data['phpGpc'] = get_magic_quotes_gpc() ? '开启' : '关闭';;
        $data['excuteUseMemory'] =  function_exists('memory_get_usage') ? byte_format(memory_get_usage()) : '未知';
        $dao = new Model();
        $getMysqlVersion = $dao->query('select version()');
        $data['mysqlVersion'] = $getMysqlVersion[0]['version()'];
        $data['sys_version'] = C('APP_VESION');
        $update = safe_b64encode(serialize($data));
        //获取备忘信息
        $data['notepad'] = M('Admin')->Where("id=".parent::_getAdminUid())->find();
        $this->assign($data);
        $this->assign('update', $update);
        parent::_sysLog('index');
        $this->display();
    }

    /**
     * 更新备忘信息
     *
     */
    public function updateNotepad()
    {
        $notepad = substr($_POST['notepad'], 0, 2000);
        $dao = M('Admin');
        if($daoCreate = $dao->create())
        {
            $dao->notepad = $notepad;
            $dao->id = parent::_getAdminUid();
            $daoSave = $dao->save();
            if(false !== $daoSave)
            {
               die('ok');
            }else
            {
                die('更新失败');
            }
        }else
        {
           die($dao->getError());
        }
    }
}
