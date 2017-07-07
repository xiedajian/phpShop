<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

class Index extends Controller
{
    private $xml = null;
    private $wechat_appid    = 'wxa58576997b358e5f';        //微信公众号唯一标识
//    private $wechat_appid = 'wxd0531e197a4c457d';        //微信公众号唯一标识
    private $wechat_appsecret = '126e68694d4cd783978da8770612cc97';  //微信公众号appsecret

    function __construct()
    {
        parent::__construct();
        $this->xml = new \XmlWriter();
    }

    //中转台
    public function index()
    {
        //1.如果是支付宝，进支付宝支付页
        //2.如果是微信，先跳转到授权页面，
        //回调地址获取授权code ，请求换取access_token,获取openid

        $store_id = input('get.store_id');
        if (empty($store_id)) {
            return $this->fetch('error', ['msg' => '店铺信息读取失败']);
        }
        //判断是不是微信
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            preg_match('/MicroMessenger\/([\d\.]+)/i', $_SERVER['HTTP_USER_AGENT'], $match);
            if ($match[1] < "5.0") {
                return $this->fetch('error', ['msg' => '当前微信版本过低,请更新到最新版本']);
            }
            //重定向微信授权
            $this->redirect("https://open.weixin.qq.com/connect/oauth2/authorize?appid=" . $this->wechat_appid . "&redirect_uri=" . urlencode("http://192.168.1.83/index.php/index/index/wechatPayPage?store_id=" . $store_id) . "&response_type=code&scope=snsapi_base&state=123#wechat_redirect");
        }
        //判断是不是支付宝
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
            return $this->fetch('index', ['trade_type' => 'FWC', 'store_id' => $store_id]);
        }
        return $this->fetch('error', ['msg' => '请使用微信或支付宝客户端扫码支付']);
    }


    //微信授权code 回调地址
    public function wechatPayPage()
    {
        $store_id = input('get.store_id');
        $code = input('get.code');
        if (empty($store_id) || empty($code)) {
            return $this->fetch('error', ['msg' => '参数错误,请重新扫码']);
        }
        $data = file_get_contents("https://api.weixin.qq.com/sns/oauth2/access_token?appid=" . $this->wechat_appid . "&secret=" . $this->wechat_appsecret . "&code=" . $code . "&grant_type=authorization_code");
        //判断授权是否成功
        //失败字段{"errcode":40029,"errmsg":"invalid code"}
        //成功字段{ "access_token":"ACCESS_TOKEN","expires_in":7200,"refresh_token":"REFRESH_TOKEN","openid":"OPENID","scope":"SCOPE" }
        $arr = json_decode($data, true);
        if (array_key_exists("access_token", $arr)) {
            $access_token = $arr['access_token'];
//            return 'openid:'.$arr['openid'];
            return $this->fetch('wechat_paypage', ['store_id' => $store_id, 'openid' => $arr['openid']]);
            //模拟提交订单
//            $this->redirect('http://192.168.1.83/index.php/index/index/order?openid='.$arr['openid']);

        }
        return $this->fetch('error', ['msg' => '授权失败,请重新扫码']);
    }



    //富友测试支付页面  （固定的openid）
    public function testPayPage()
    {
        $store_id = input('get.store_id');
        if (empty($store_id)) {
            return $this->fetch('error', ['msg' => '店铺信息读取失败']);
        }
        //判断是不是微信
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            preg_match('/MicroMessenger\/([\d\.]+)/i', $_SERVER['HTTP_USER_AGENT'], $match);
            if ($match[1] < "5.0") {
                return $this->fetch('error', ['msg' => '当前微信版本过低,请更新到最新版本']);
            }
            return $this->fetch('wechat_paypage', ['store_id' => $store_id, 'openid' => 'ovu1GwTV5-c15-PhS4duwOrVKX8M']);
        }
        //判断是不是支付宝
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
            return $this->fetch('index', ['trade_type' => 'FWC', 'store_id' => $store_id]);
        }
//        return $this->fetch('error', ['msg' => '请使用微信或支付宝客户端扫码支付']);
        return $this->fetch('wechat_paypage', ['store_id' => $store_id, 'openid' => 'ovu1GwTV5-c15-PhS4duwOrVKX8M']);
    }

    /**
     * 微信生成订单
     * @param $order_amt
     * @return code=>200成功 code=>300失败
     * */
    public function order()
    {
        //接受ajx的金额
//        $order_amt = input('post.order_amt/d');     //单位 分
//        $trade_type = (input('post.trade_type')=="JSAPI")? "JSAPI" : ((input('post.trade_type')=="FWC")?"FWC":"");     //支付方式
//        $store_id = input('post.store_id/d');     //店铺id
//        $openid = input('post.openid');     //微信下单openid


        //模拟接受
        $order_amt = 100;
        $trade_type = "JSAPI";
        $store_id = 38;     //店铺id
//        $openid = input('get.openid');     //微信下单openid
        $openid = 'ovu1GwTV5-c15-PhS4duwOrVKX8M';     //微信下单openid

//        echo "openid : ".$openid;
//        echo '<br>-------------------<br><br>';

        if (empty($order_amt) || empty($trade_type) || empty($store_id) || empty($openid)) {
            return json(['data' => array(), 'code' => 300, 'msg' => '参数错误']);
        }

        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            //trigger_error("对不起，请开启curl功能模块！", E_USER_ERROR);
            return json(['data' => array(), 'code' => 500, 'msg' => '对不起，请开启curl功能模块']);
        }

        //生成随机唯一订单   写入表
        $order_sn = 'ipvp' . date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $insertdata = ['order_sn' => $order_sn, 'store_id' => $store_id];
//        if(!Db::table('Order')->insert($insertdata)){
//            return json(['data'=>array(),'code'=>300,'msg'=>'订单号生成失败，请稍后重试']);
//        }
        //待提交数据
        $data = array();
        //必填
        $data['version'] = '1.0';
        $data['ins_cd'] = '08A9999999';               //机构号,接入机构在富友的唯一代码  20
        $data['mchnt_cd'] = '0002900F0370542';                  //商户号, 富友分配给二级商户的商户号  15
        $data['term_id'] = $this->getRandomString(8);                  //终端号(随机8字节数字字母组合)   8
        $data['random_str'] = $this->getRandomString(32);                  //随机字符串   32
        $data['goods_des'] = '商品描述';                  //商品描述    128
        $data['mchnt_order_no'] = $order_sn;                  //商户订单号, 商户系统内部的订单号  30
        $data['order_amt'] = $order_amt;                  //总金额, 订单总金额，单位为分   16
        $data['term_ip'] = $this->getIp();                  //终端IP    16
        $data['txn_begin_ts'] = date('YmdHis', time());                  //交易起始时间, 订单生成时间，格式为yyyyMMddHHmmss  14
        $data['notify_url'] = 'http://www.ipvp.cn';                  //富友异步通知回调地址 (必须为直接可访问的url，不能携带参数)  256
        $data['trade_type'] = $trade_type;                  //支付方式 JSAPI--公众号支付、FWC--支付宝服务窗   16
        $data['sub_openid'] = $openid;                  //子商户用户标识，（JSAPI时必传）   128
        $data['sub_appid'] = $this->wechat_appid;                  //子商户公众号id   32
        //选填
        $data['curr_type'] = "CNY";
        $data['goods_detail']="goods_detail";
        $data['goods_tag']="goods_tag";
        $data['product_id']="product_id";
        $data['addn_inf']="addn_inf";
        $data['limit_pay']="";
        $data['openid']="";

        //拼装过的需要签名的字符串串
        $sign2="addn_inf=".$data['addn_inf']."&curr_type=".$data['curr_type']."&goods_des=".$data['goods_des']."&goods_detail=".$data['goods_detail']."&goods_tag=".$data['goods_tag']."&ins_cd=".$data['ins_cd']."&limit_pay=".$data['limit_pay']."&mchnt_cd=".$data['mchnt_cd']."&mchnt_order_no=".$data['mchnt_order_no']."&notify_url=".$data['notify_url']."&openid=".$data['openid']."&order_amt=".$data['order_amt']."&product_id=".$data['product_id']."&random_str=".$data['random_str']."&sub_appid=".$data['sub_appid']."&sub_openid=".$data['sub_openid']."&term_id=".$data['term_id']."&term_ip=".$data['term_ip']."&trade_type=".$data['trade_type']."&txn_begin_ts=".$data['txn_begin_ts']."&version=".$data['version'];
        $sign = $this->arr_sort($data);

//        echo "sign-string: ".$sign2;
//        echo "sign-string: ".$sign;
//        echo '<br>-------------------<br><br>';

        //RSAwithMD5+base64加密后得到的sign
        $data['sign'] = $this->sign($sign);   //签名  32

//        echo "sign-RSA: ".$data['sign'];
//        echo '<br>-------------------<br><br>';


        //完整的xml格式
        $a = "<?xml version=\"1.0\" encoding=\"GBK\" standalone=\"yes\"?><xml>" . $this->toXml($data) . "</xml>";

//        echo "xml报文 : ".$a;
//        echo '<br>-------------------<br><br>';

        //中文要经过两次urlencode()
        $b = "req=" . urlencode(urlencode($a));
//        $b = "req=" . urlencode($a);

//        echo "req : ".$b;
//        echo '<br>-------------------<br><br>';
        //通过curl的post方式发送接口请求
        $url = "http://116.239.4.195:28164/wxPreCreate";

        //返回的xml字符串
        $resultXml = URLdecode($this->SendDataByCurl($url, $b));

        //将xml转化成对象
        $ob = simplexml_load_string($resultXml);

        $res_arr = json_decode(json_encode($ob),true);
        //输出结果
//        var_dump($ob);
//        var_dump($res_arr);

        if($ob->result_code=='000000'){
            return json(['data'=>$res_arr,'code'=>200,'msg'=>'创建订单成功']);
        }else{
            return json(['data'=>$res_arr,'code'=>300,'msg'=>'创建订单失败:'.$res_arr['result_msg']]);
        }

    }


    //192.168.1.254 SqlServer数据库连接测试
    public function dbLinkTest()
    {
        if ($data = Db::table('order')->select()) {
            print_r($data);
        }

//        $indata = ['order_sn' => '23214234234', 'store_id' => 39];
//        if(!Db::table('Order')->insert($indata)){
//            echo '插入失败';
//        }else{
//            echo '插入成功';
//        }

    }

    //富友api测试
    public function fuyouApiDemo()
    {
//        $xml = new A2Xml();
        $data = array();
        $data['ins_cd'] = "08A9999999";
        $data['mchnt_cd'] = "0002900F0370542";
        $data['goods_des'] = "描述";
        $data['order_type'] = "WECHAT";
        $data['order_amt'] = "2000";
        $data['notify_url'] = "http://test.modernmasters.com/index.php/Supplier/User/myResources.html";
        $data['addn_inf'] = "";
        $data['curr_type'] = "CNY";
        $data['term_id'] = "342343";
        $data['goods_detail'] = "";
        $data['goods_tag'] = "";
        $data['version'] = "1";
        $data['random_str'] = time();
        $data['mchnt_order_no'] = time();
        $data['term_ip'] = "117.29.110.187";
        $data['txn_begin_ts'] = date('YmdHis', time());

//拼装过的需要签名的字符串串
        $sign = "addn_inf=" . $data['addn_inf'] . "&curr_type=" . $data['curr_type'] . "&goods_des=" . $data['goods_des'] . "&goods_detail=" . $data['goods_detail'] . "&goods_tag=" . $data['goods_tag'] . "&ins_cd=" . $data['ins_cd'] . "&mchnt_cd=" . $data['mchnt_cd'] . "&mchnt_order_no=" . $data['mchnt_order_no'] . "&notify_url=" . $data['notify_url'] . "&order_amt=" . $data['order_amt'] . "&order_type=" . $data['order_type'] . "&random_str=" . $data['random_str'] . "&term_id=" . $data['term_id'] . "&term_ip=" . $data['term_ip'] . "&txn_begin_ts=" . $data['txn_begin_ts'] . "&version=" . $data['version'];


//RSAwithMD5+base64加密后得到的sign
        $data['sign'] = $this->sign($sign);

//完整的xml格式
        $a = "<?xml version=\"1.0\" encoding=\"GBK\" standalone=\"yes\"?><xml>" . $this->toXml($data) . "</xml>";

//经过两次urlencode()之后的字符串
        $b = "req=" . urlencode(urlencode($a));

//通过curl的post方式发送接口请求
        $url = "http://116.239.4.195:28164/preCreate";

//返回的xml字符串
        $resultXml = URLdecode($this->SendDataByCurl($url, $b));

//将xml转化成对象
        $ob = simplexml_load_string($resultXml);

//输出结果
        var_dump($ob);
    }

    //生成随机数字字母组合
    protected function getRandomString($len, $chars = null)
    {
        if (is_null($chars)) {
//            $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
            $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        }
        mt_srand(10000000 * (double)microtime());
        for ($i = 0, $str = '', $lc = strlen($chars) - 1; $i < $len; $i++) {
            $str .= $chars[mt_rand(0, $lc)];
        }
        return $str;
    }

    //生成随机唯一订单号  写入订单表
    public function build_order_no()
    {
        $order_sn = 'ipvp' . date('YmdHis') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
        $indata = ['order_sn' => '23214234234', 'store_id' => 39];
        Db::table('Order')->insert($indata);
    }

    //获取ip
    protected function getIp()
    {
        $ip = $_SERVER["REMOTE_ADDR"];
        return $ip;
    }

    //数组转签名
    protected function arr_sort($arr)
    {
        $ret = array();
        $sign = "";
        foreach ($arr as $key => $value) {
            $kv = $key . '=' . $value;
            array_push($ret, $kv);
        }
        sort($ret);
        $sign = implode("&", $ret);
        return $sign;
    }

    //数组转xml
    protected function toXml($data, $eIsArray = FALSE)
    {
        if (!$eIsArray) {
            $this->xml->openMemory();
        }
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $this->xml->startElement($key);
                $this->toXml($value, TRUE);
                $this->xml->endElement();
                continue;
            }
            $this->xml->writeElement($key, $value);
        }
        if (!$eIsArray) {
            $this->xml->endElement();
            return $this->xml->outputMemory(true);
        }
    }

    //签名加密流程
    protected function sign($data)
    {
        //读取密钥文件
        $pem = file_get_contents(dirname(__FILE__) . '/keypem.pem');
        //获取私钥
        $pkeyid = openssl_pkey_get_private($pem);
        //MD5WithRSA私钥加密
        openssl_sign($data, $sign, $pkeyid, OPENSSL_ALGO_MD5);
        openssl_free_key($pkeyid);
        //返回base64加密之后的数据
        $t = base64_encode($sign);
        //解密-1:error验证错误 1:correct验证成功 0:incorrect验证失败
        // $pubkey = openssl_pkey_get_public($pem);
        // $ok = openssl_verify($data,base64_decode($t),$pubkey,OPENSSL_ALGO_MD5);
        // var_dump($ok);
        return $t;
//        return $sign;
    }

    //通过curl模拟post的请求；
    protected function SendDataByCurl($url, $data)
    {
        //对空格进行转义
        $url = str_replace(' ', '+', $url);
        $ch = curl_init();
        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, "$url");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_TIMEOUT, 3); //定义超时3秒钟
        // POST数据
        curl_setopt($ch, CURLOPT_POST, 1);
        // 把post的变量加上
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);  //所需传的数组用http_bulid_query()函数处理一下，就ok了
        //执行并获取url地址的内容
        $output = curl_exec($ch);
        $errorCode = curl_errno($ch);
        //释放curl句柄
        curl_close($ch);
        if (0 !== $errorCode) {
            return false;
        }
        return $output;
    }


}
