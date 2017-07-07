<?php


/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2016/6/29
 * Time: 17:09
 */

/**
 *
 * 富友支付接口
 */

class fuiou
{
    protected $debug = false;//是否测试
    protected $MchntCd;//商户号
    protected $key;//秘钥
    protected $api;//接口地址

    public function __construct()
    {
        if($this->debug){
            //测试配置
            $this->MchntCd = '0002900F0096235';
            $this->key = '5old71wihg2tqjug9kkpxnhx9hiujoqj';
            $this->api = array(
                'saveOrder' =>  'http://www-1.fuiou.com:18670/mobile_pay/findPay/createOrder.pay',
                'queryCard' =>  'http://www-1.fuiou.com:18670/mobile_pay/findPay/cardBinQuery.pay',
                'pay01'   =>  'http://www-1.fuiou.com:18670/mobile_pay/timb/timb01.pay',
                'pay02'   =>  'http://www-1.fuiou.com:18670/mobile_pay/timb/timb02.pay'
            );
        }else{
            $fuiou_paymemt = Model()->table('mb_payment')->where(array('payment_code'=>'fuiou','payment_state'=>1))->find();
            $fuiou_paymemt_config = unserialize($fuiou_paymemt['payment_config']);
            $this->MchntCd = $fuiou_paymemt_config['fuiou_account'];
            $this->key = $fuiou_paymemt_config['fuiou_key'];
            $this->api = array(
                'saveOrder' =>  'https://mpay.fuiou.com:16128/findPay/createOrder.pay',
                'queryCard' =>  'https://mpay.fuiou.com:16128/findPay/cardBinQuery.pay',
                'pay01'   =>  'https://mpay.fuiou.com:16128/timb/timb01.pay',
                'pay02'   =>  'https://mpay.fuiou.com:16128/timb/timb02.pay'
            );
        }
    }

    /**
     * 生成签名
     * @param $value
     * @return string
     */
    public function sign($value){
        return md5($this->MchntCd.'|'.$value.'|'.$this->key);
    }

    /**
     * 验证签名
     * @param $value
     * @param $sign
     * @return bool
     */
    public function check_sign($value,$sign){
        return $sign&&strtolower($sign)==strtolower(md5($value.'|'.$this->key));
    }

    /**
     * xml转数组
     * @param $xml
     * @return array|mixed
     */
    protected function _xml2array($xml){
        $arr = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        return $arr==null?array():$arr;
    }

    /**
     * 下单
     * @param $money
     * @return array|bool|mixed|string
     */
    public function saveOrder($money){
        $money = ceil($money*100);
        if($money==0){
            return false;
        }

        $param = "?FM=<FM><MchntCd>{$this->MchntCd}</MchntCd><Amt>{$money}</Amt><Rmk1></Rmk1><Rmk2></Rmk2><Rmk3></Rmk3><Sign>{$this->sign($money)}</Sign></FM>";
        $api = $this->api['saveOrder'].$param;
        $result = file_get_contents($api);
        $result = $this->_xml2array($result);

        $result['error'] = 1;
        if($result['Rcd'] == '0000'){
            //签名验证
            if($this->check_sign($result['Rcd'].'|'.$result['OrderId'],$result['Sign'])){
                $result['error'] = 0;
            }
        }
        return $result;
    }

    /**
     * 查询银行卡号
     * @param $cardNo
     * @return array|mixed|string
     */
    public function queryCard($cardNo){
        $statusMsg = array(
            '0000'    =>  '成功',
            '1014'    =>  '无效卡号',
            '5505'    =>  '不支持的银行卡',
            '100001'  =>  '不支持的卡类型',
        );
        $cardType = array(
            '01' =>  '借记卡',
            '02' =>  '信用卡',
            '03' =>  '准贷记卡',
            '04' =>  '富友卡',
            '05' =>  '非法卡号',
        );
        $param = "?FM=<FM><MchntCd>{$this->MchntCd}</MchntCd><Ono>{$cardNo}</Ono><Sign>{$this->sign($cardNo)}</Sign></FM>";
        $api = $this->api['queryCard'].$param;
        $result = file_get_contents($api);
        $result = $this->_xml2array($result);

        $result['error'] = 1;
        $result['errorMsg'] = $statusMsg[$result['Rcd']];
        if(empty($result['errorMsg'])){$result['errorMsg'] = '无效卡号';}
        if($result['Rcd'] == '0000'){
            if($this->check_sign($result['Rcd'],$result['Sign'])){
                $result['error'] = 0;
                $result['cardType'] = $cardType[$result['Ctp']];
            }
        }
        return $result;
    }

    /**
     * 支付参数
     * @param $order_no
     * @param $fu_order_id
     * @param $cardInfo
     * @return array
     */
    public function pay($order_no,$fu_order_id,$cardInfo){
        $ret = array();
        $ret['url'] = $this->api['pay'.$cardInfo['type']];//提交地址
        $ret['mchntCd'] = $this->MchntCd;
        $ret['orderid'] = $fu_order_id;
        $ret['ono'] = $cardInfo['bank_card_no'];
        $ret['backurl'] = MOBILE_SITE_URL ."/api/payment/fuiou/notify_url.php";
        $ret['reurl'] = WAP_SITE_URL ."/tmpl/member/order_pay.html?order_id=".$order_no;
        $ret['homeurl'] = MOBILE_SITE_URL ."/api/payment/fuiou/return_url.php";
        $ret['name'] = $cardInfo['name'];
        $ret['sfz'] = $cardInfo['idcard'];
        $ret['md5'] = $this->sign($fu_order_id);
        return $ret;
    }


    public function getReturnInfo(){
        if($_REQUEST['rcd'] == '0000'){
            if($this->check_sign($_REQUEST['rcd'].'|'.$_REQUEST['orderid'],$_REQUEST['md5'])){
                return array(
                    'out_trade_no'  =>  $_REQUEST['orderid'],
                );
            }
        }
        return false;
    }

    public function getNotifyInfo(){
        if($_REQUEST['rcd'] == '0000'){
            if($this->check_sign($_REQUEST['rcd'].'|'.$_REQUEST['orderid'],$_REQUEST['md5'])){
                return array(
                    'out_trade_no'  =>  $_REQUEST['orderid'],
                );
            }
        }
        return false;
    }

}