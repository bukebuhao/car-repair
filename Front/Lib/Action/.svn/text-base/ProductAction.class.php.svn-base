<?php
/**
 * 
 * 产品
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: ProductAction.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $
 */

class ProductAction extends GlobalAction
{
    function _initialize()
    {
        parent::_initialize();
        $data['productCategory'] = getCategory(getCache('Category'), 6,0);
        $this->assign($data);
    }
    
    /**
     * 产品列表
     *
     */
    public function index()
    {
    	$this->setSeoInfo();
    	$category = intval($_GET['category']);
        $sql = 'SELECT a.*, b.title as categoryName FROM _DBPREFIX_product a LEFT JOIN _DBPREFIX_category b on a.category_id=b.id WHERE 1=1';
        if (!empty($_GET['category'])) {
        	$sql.= " and a.category_id=".formatQuery($category);
        }
    	if (!empty($_GET['keyword'])) {
        	$sql.= " and a.title like '%".formatQuery($_GET['keyword'])."%'";
        }
        $sql.= " and a.status=0";
        parent::getSqlList($sql,'a.update_time desc, display_order asc');
    }
    
    /**
     * 浏览内容页
     *
     */
    public function detail(){
        $titleId = intval($_GET['id']);
        $sql = 'SELECT a.*, b.title as categoryName FROM _DBPREFIX_product a LEFT JOIN _DBPREFIX_category b on a.category_id=b.id WHERE a.id='.formatQuery($titleId).' and a.status=0 LIMIT 1 ';
        parent::getSqlDetail($sql, $titleId);
     }

}