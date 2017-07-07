<?php
/**
 * 网银在线自动对账文件
 *
 * 
 
 */
error_reporting(7);
$_GET['act']	= 'payment';
$_GET['op']		= 'notify';
$_GET['payment_code'] = 'fuiou';

$order_type_array = array(
    1   =>   'real_order',
    2   =>   'vr_order',
    3   =>   'pd_order',
);
//订单类型附加在订单号后面一位
$order_id = $_REQUEST['order_id'];
$len = strlen($order_id);
$order_type = $order_type_array[substr($order_id,$len-1,1)];
$order_id = substr($order_id,0,$len-1);

//赋值，方便后面合并使用支付宝验证方法
$_POST['out_trade_no'] = $order_id;//订单号
$_POST['extra_common_param'] = $order_type;
$_POST['trade_no'] = $_REQUEST['fy_ssn'];//外部单号



require_once(dirname(__FILE__).'/../../../index.php');
?>