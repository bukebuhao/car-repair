<?php 
/**
 * 
 * Order(订单)
 *
 * @package      	companycms
 * @author          zxz (QQ:396774497)
 * @copyright     	Copyright (c) 2011-2013  (http://www.tengzhiinfo.com)
 * @license         http://www.tengzhiinfo.com/license.txt
 * @version        	$Id: OrderModel.class.php v1.0 2011-7-28 Administrator tengzhiwangluoruanjian $

 */
 
import("AdvModel");
class OrderModel extends AdvModel 
{
	protected $_validate = array(
        array('realname', 'require', '姓名必填', 0, '', Model:: MODEL_BOTH),
        array('telephone', 'require', '电话号码必填', 0, '', Model:: MODEL_BOTH),
        array('address', 'require', '收货地址必填', 0, '', Model:: MODEL_BOTH),
        array('introduction', 'require', '订单说明必填', 0, '', Model:: MODEL_BOTH),
    );

    protected $_auto = array(
        array('realname', 'dHtml', Model:: MODEL_BOTH, 'function'),
        array('brithday', 'strtotime', Model:: MODEL_BOTH, 'function'),
        array('telephone', 'dHtml', Model:: MODEL_BOTH, 'function'),
        array('address', 'dHtml', Model:: MODEL_BOTH, 'function'),
        array('zipcode', 'dHtml', Model:: MODEL_BOTH, 'function'),
        array('fax', 'dHtml', Model:: MODEL_BOTH, 'function'),
        array('ip', 'get_client_ip', Model:: MODEL_INSERT, 'function'),
        array('create_time', 'time', Model:: MODEL_INSERT, 'function'),
        array('update_time', 'time', Model:: MODEL_UPDATE, 'function'),
    );
}