<?php

class IndexAction extends GlobalAction
{
    /**
    +----------------------------------------------------------
    * 默认操作
    +----------------------------------------------------------
    */
    public function index()
    {
        //$this->display(THINK_PATH.'/Tpl/Autoindex/hello.html');
        $this->setSeoInfo();
        //新闻
		$this->getNewsList();
		//产品
		$this->getProductList();
		//广告
		$this->assign("adFirstList", parent::getAd());
		
        $this->display();
    }

    

    /**
     * 新闻资讯
     * Enter description here ...
     */
    private function getNewsList() {
    	//得到新闻信息
        $NewsModel	= M("News");
        $conditions['status'] = 0;
        $conditions['recommend'] = 1;
        $orders = "update_time desc, display_order asc";
        $newList = $this->getLimitList($NewsModel, $conditions, $orders, 10, 'id, title, title_style');
        $this->assign("newList", $newList);
    }
    
    /**
     * 最新推荐产品
     * Enter description here ...
     */
    private function getProductList() {
    	//得到产品
        $ProductModel	= M("Product");
        $conditions['status'] = 0;
        $conditions['recommend'] = 1;
        $orders = "update_time desc, display_order asc";
        $productList = $this->getLimitList($ProductModel, $conditions, $orders, 10, 'id, title, title_style, attach_image, sale_price');
        $this->assign("productList", $productList);
    }
    
    /**
     * 验证码
     *
     */
    function verify()
    {
        import('ORG.Util.Image');
        Image::buildImageVerify();
    }
    
//    /**
//    +----------------------------------------------------------
//    * 探针模式
//    +----------------------------------------------------------
//    */
//    public function checkEnv()
//    {
//        load('pointer',THINK_PATH.'/Tpl/Autoindex');//载入探针函数
//        $env_table = check_env();//根据当前函数获取当前环境
//        echo $env_table;
//    }

}
?>