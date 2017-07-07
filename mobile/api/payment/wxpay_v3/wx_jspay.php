<?php 
ini_set('date.timezone','Asia/Shanghai');
$pay_sn=$_COOKIE['pay_sn'];
unset($_COOKIE['pay_sn']);
$pay_amount=$_COOKIE['pay_amount'];
unset($_COOKIE['pay_amount']);
//error_reporting(E_ERROR);
require_once "lib/WxPay.Api.php";
require_once "WxPay.JsApiPay.php";
require_once 'log.php';

//初始化日志
$logHandler= new CLogFileHandler("logs/".date('Y-m-d').'.log');
$log = Log::Init($logHandler, 15);

//①、获取用户openid
$tools = new JsApiPay();
$openId = $tools->GetOpenid();

//②、统一下单
$input = new WxPayUnifiedOrder();
$input->SetBody($pay_sn);
$input->SetAttach('');
$input->SetOut_trade_no($pay_sn);//WxPayConfig::MCHID.date("YmdHis")
$input->SetTotal_fee(floatval($pay_amount)*100);
$input->SetTime_start(date("YmdHis"));
$input->SetTime_expire(date("YmdHis", time() + 600));
$input->SetGoods_tag("");
$input->SetNotify_url("http://ipvp.cn/mobile/api/payment/wxpay_v3/notify.php");
$input->SetTrade_type("JSAPI");
$input->SetOpenid($openId);
$order = WxPayApi::unifiedOrder($input);
$jsApiParameters = $tools->GetJsApiParameters($order);

//③、在支持成功回调通知中处理成功之后的事宜，见 notify.php
/**
 * 注意：
 * 1、当你的回调地址不可访问的时候，回调通知会失败，可以通过查询订单来确认支付是否成功
 * 2、jsapi支付时需要填入用户openid，WxPay.JsApiPay.php中有获取openid流程 （文档可以参考微信公众平台“网页授权接口”，
 * 参考http://mp.weixin.qq.com/wiki/17/c0f37d5704f0b64713d5d2c37b468d75.html）
 */
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>拼单网-微信支付</title>
    <script type="text/javascript">
	//调用微信JS api 支付
	function jsApiCall()
	{
		WeixinJSBridge.invoke(
			'getBrandWCPayRequest',
			<?php echo $jsApiParameters; ?>,
			function(res){
				WeixinJSBridge.log(res.err_msg);
				//alert(res.err_code+res.err_desc+res.err_msg);
			}
		);
	}

	function callpay()
	{
		if (typeof WeixinJSBridge == "undefined"){
		    if( document.addEventListener ){
		        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
		    }else if (document.attachEvent){
		        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
		        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
		    }
		}else{
		    jsApiCall();
		}
	}
	</script>
</head>
<body onload="callpay();">
</br></br></br></br>
<div align="center">
    <p style="font-size: 1.5em;">交易号：<?php echo $pay_sn; ?></p>
    <p style="font-size: 1.5em;">金额：<?php echo $pay_amount; ?>元</p>
    <button style="width:70%; height:60px; background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:1.4em;border-radius:5px;" type="button" onclick="history.back()" >返回订单列表</button>
</div>
</body> 
</html>