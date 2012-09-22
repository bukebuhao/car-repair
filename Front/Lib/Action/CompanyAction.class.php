<?php
/**
 * 企业模块
 */
class CompanyAction extends GlobalAction
{
	
	public function index() {
    	parent::getDetail("link_label='about'", false, M('Page'));
		$this->display();
    }
    
    public function detail() {
    	$itemId = formatQuery(trim($_GET['itemId']));
        parent::getDetail("link_label='{$itemId}'", false, M('Page'), 'index');
    }
    
    public function picture() {
    	
    	
    	
    	
    }
}