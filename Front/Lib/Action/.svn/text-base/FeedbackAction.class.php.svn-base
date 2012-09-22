<?php
/**
 * 
 * 留言
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: FeedbackAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $
 */

class FeedbackAction extends GlobalAction
{
    public $dao;
    function _initialize()
    {
        parent::_initialize();
    }

    /**
     * 列表
     *
     */
    public function index()
    {
  		$this->dao = M('Feedback');
    	parent::getList('status=0', 0, 8);
    }

    /**
     * 提交留言
     *
     */
    public function insert()
    {
    	$data['title'] = formatQuery(trim($_POST['title']));
        $data['username'] = formatQuery(trim($_POST['username']));
        $data['email'] = formatQuery(trim($_POST['email']));
        $data['content'] = trim($_POST['content']);
        $data['ip'] = get_client_ip();
        $data['create_time'] = time();
        $verifyCode = intval(trim($_POST['verifyCode']));
        empty($verifyCode) && parent::_message('error', '验证码必须填写');
        if(md5($verifyCode) != Session::get('verify'))
        {
            parent::_message('error', '验证码错误');
        }
        
        $this->dao = D('Feedback');
        if($daoCreate = $this->dao->create($data))
        {
            $this->dao->status = $this->sysConfig['comment_verify'] == 1 ? 1 : 0 ;
            $daoAdd = $this->dao->add();
            if(false !== $daoAdd)
            {
                parent::_message('success', '留言成功');
            }else
            {
                parent::_message('error', '留言失败，请检查必填项');
            }
        }else
        {
            parent::_message('error', $this->dao->getError());
        }
    }
    
    /**
     * 查看详细的留言信息
     * Enter description here ...
     */
     public function detail(){
     	$Id = intval($_GET['id']);
     	parent::getDetail("id={$Id}", false, M('Feedback'), 'detail');
     }
}