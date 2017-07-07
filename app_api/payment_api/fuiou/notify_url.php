<?php
/* *
 * 功能：支付宝服务器异步通知页面
 */




$_GET['act'] = 'payment';
$_GET['op']	= 'notify';
$_GET['payment_code']	= 'fuiou';
require_once(dirname(__FILE__).'/../../../index.php');
?>
