<?php
/**
 * 
 * 招聘
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: NewsAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $
 */

class JobAction extends GlobalAction
{
    public $dao, $resumeDao;
    function _initialize()
    {
        parent::_initialize();
        $this->dao = M('Job');
        $this->resumeDao = D('Resume');
    }
    
    /**
     * 列表
     *
     */
    public function index()
    {
        parent::getList('status=0');
    }
    
    /**
     * 内容
     *
     */
    public function detail(){
        $titleId = intval($_GET['item']);
        parent::getDetail("id={$titleId}", 'view_count');
    }

    /**
     * 提交应聘
     *
     */
    public function doResume()
    {
        $verifyCode = intval(trim($_POST['verifyCode']));
        empty($verifyCode) && parent::_message('error', '验证码必须填写');
        if(md5($verifyCode) != Session::get('verify'))
        {
            parent::_message('error', '验证码错误');
        }
        if($daoCreate = $this->resumeDao->create())
        {
            $uploadFile = upload($this->getActionName(),1,0,0);
            if ($uploadFile)
            {
                $this->resumeDao->attach_file = formatAttachPath($uploadFile[0]['savepath']) . $uploadFile[0]['savename'];
            }
            $daoAdd = $this->resumeDao->add();
            if(false !== $daoAdd)
            {
                parent::_message('success', '应聘资料提交成功，等待管理员处理');
            }else
            {
                parent::_message('error', '应聘资料提交失败，请检查必填项');
            }
        }else
        {
            parent::_message('error', $this->resumeDao->getError());
        }
    }
}