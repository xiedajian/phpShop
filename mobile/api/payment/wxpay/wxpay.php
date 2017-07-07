<?php
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
 */
include_once("lib/log_.php");
include_once("lib/WxPayPubHelper.php");

//使用jsapi接口
$jsApi = new JsApi_pub();
//以log文件形式记录回调信息
$log_ = new Log_();
$log_name="./notify_url.log";//log文件路径

@$pay_sn = $_GET['pay_sn'];
@$pay_amount = $_GET['pay_amount'];

/*
$log_->log_result($log_name,"【get pay_sn】:\n".$pay_sn."\n");
$log_->log_result($log_name,"【get pay_amount】:\n".$pay_amount."\n");

if(!isset($_COOKIE['pay_sn'])||!isset($_COOKIE['pay_amount']))
{
    $expire = time() + 86400; // 设置24小时的有效期
    setcookie ("pay_sn", $pay_sn, $expire); // 设置一个名字为var_name的cookie，并制定了有效期
    setcookie ("pay_amount", $pay_amount, $expire); // 再将过期时间设置进cookie以便你能够知道var_name的过期时间

    $pay_sn = $_COOKIE['pay_sn'];
    $pay_amount =  $_COOKIE['pay_amount'];
}
else
{
    $pay_sn = $_COOKIE['pay_sn'];
    $pay_amount =  $_COOKIE['pay_amount'];
}

$log_->log_result($log_name,"【cookie pay_sn】:\n".$pay_sn."\n");
$log_->log_result($log_name,"【cookie pay_amount】:\n".$pay_amount."\n");

*/


//=========步骤1：网页授权获取用户openid============
//通过code获得openid
if (!isset($_GET['code']))
{
    if(isset($_COOKIE['pay_sn']))
    {
        unset($_COOKIE['pay_sn']);
    }
    if(isset($_COOKIE['pay_amount']))
    {
        unset($_COOKIE['pay_amount']);
    }
    $expire = time() + 120; // 设置2分钟的有效期
    setcookie ("pay_sn", $pay_sn, $expire); // 设置一个名字为var_name的cookie，并制定了有效期
    setcookie ("pay_amount", $pay_amount, $expire); // 再将过期时间设置进cookie以便你能够知道var_name的过期时间

    //触发微信返回code码
    $url = $jsApi->createOauthUrlForCode(WxPayConf_pub::JS_API_CALL_URL);
    Header("Location: $url");
}else
{


    if(isset($_COOKIE['pay_sn']))
    {
        $pay_sn = $_COOKIE['pay_sn'];
        unset($_COOKIE['pay_sn']);
    }
    if(isset($_COOKIE['pay_amount']))
    {
        $pay_amount =  $_COOKIE['pay_amount'];
        unset($_COOKIE['pay_amount']);
    }

    //获取code码，以获取openid
    $code = $_GET['code'];
    $jsApi->setCode($code);
    $openid = $jsApi->getOpenId();

    $log_->log_result($log_name,"【CODE】:\n".$code."\n");
    $log_->log_result($log_name,"【openid】:\n".$openid."\n");
}

//=========步骤2：使用统一支付接口，获取prepay_id============
//使用统一支付接口
$unifiedOrder = new UnifiedOrder_pub();

//设置统一支付接口参数
//设置必填参数
//appid已填,商户无需重复填写
//mch_id已填,商户无需重复填写
//noncestr已填,商户无需重复填写
//spbill_create_ip已填,商户无需重复填写
//sign已填,商户无需重复填写

//自定义订单号，此处仅作举例
$timeStamp = time();
$out_trade_no = WxPayConf_pub::APPID."$timeStamp";

$unifiedOrder->setParameter("openid","$openid");//openid描述
$unifiedOrder->setParameter("body","$pay_sn");//商品描述
//$unifiedOrder->setParameter("body","$pay_sn");//商品描述
$unifiedOrder->setParameter("out_trade_no","$pay_sn");//商户订单号
$wx_pay_amount =  floatval($pay_amount)*100;
$unifiedOrder->setParameter("total_fee","$wx_pay_amount");//总金额

$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址
$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型



//非必填参数，商户可根据实际情况选填
//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
//$unifiedOrder->setParameter("device_info","XXXX");//设备号
//$unifiedOrder->setParameter("attach","XXXX");//附加数据
//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
//$unifiedOrder->setParameter("openid","XXXX");//用户标识
//$unifiedOrder->setParameter("product_id","$pay_sn");//传入交易内部订单号

$prepay_id = $unifiedOrder->getPrepayId();
//=========步骤3：使用jsapi调起支付============
$jsApi->setPrepayId($prepay_id);

$jsApiParameters = $jsApi->getParameters();
//echo $jsApiParameters;
?>

<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <title>微信安全支付</title>

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
    <p style="font-size: 32px;">微信交易号：<?php echo $pay_sn; ?></p>
    <p style="font-size: 32px;">金额：<?php echo $pay_amount; ?>元</p>
    <button style="width:70%; height:100px; background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:32px;" type="button" onclick="history.back()" >返回订单列表</button>
</div>
</body> 
</html>