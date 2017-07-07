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
 * B2C支付
 */
/*
测试商户号和秘钥：
0001000F0040992
vau6p7ldawpezyaugc0kopdrrwm4gkpu

测试银行：平安银行
测试环境商户号：FUIOU
测试数据：
借记卡测试帐户：
         一帐通账号：hanfei01
密码：qweqwe123
交易密码：888888
手机动态码：111111
测试卡： 622909115001762912  622908117744142015 支付密码： 121212
注意：请用小额测试（尽量不超过1K），以免超限或余额不足，影响其他商户测试，谢谢！

测试银行：光大银行 直接返回成功。
 */

class fuiou
{
    protected $debug = false;//是否测试
    protected $MchntCd;//商户号
    protected $key;//秘钥
    protected $api;//接口地址

    private $order;
    /**
     * 支付状态
     * @var unknown
     */
    private $pay_result;

    public function __construct($payment_info,$order_info)
    {
        $this->order = $order_info;
        if($this->debug){
            //测试配置
            $this->MchntCd = '0001000F0040992';
            $this->key = 'vau6p7ldawpezyaugc0kopdrrwm4gkpu';
            $this->api = array(
                'saveOrder' =>  'http://www-1.fuiou.com:8888/wg1_run/PayBCGate.do',
            );
        }else{
            $this->MchntCd = $payment_info['payment_config']['fuiou_account'];
            $this->key = $payment_info['payment_config']['fuiou_key'];
            $this->api = array(
                'saveOrder' =>  'https://pay.fuiou.com/PayBCGate.do',
            );
        }
    }

    /**
     * B2C支付
     */
    public function submit(){
        $this->order['api_pay_amount'] = $this->order['api_pay_amount']*100;//单位为分

        $order_type_array = array(
            'real_order'  =>  1,
            'vr_order'  =>  2,
            'pd_order'  =>  3,
        );
        $order_type = $order_type_array[$this->order['order_type']];
        if(empty($order_type)){
            $order_type = 0;
        }
        //订单类型附加在订单号后面一位
        $param = array();
        $param['mchnt_cd'] = $this->MchntCd;
        $param['order_id'] = $this->order['pay_sn'].$order_type;
        $param['order_amt'] = $this->order['api_pay_amount'];
        $param['order_pay_type'] = 'B2C';//个人网银支付
        $param['page_notify_url'] = SHOP_SITE_URL."/api/payment/fuiou/return_url.php";//页面跳转url
        $param['back_notify_url'] = SHOP_SITE_URL."/api/payment/fuiou/notify_url.php";//后台通知url
        $param['iss_ins_cd'] = '0000000000';//银行代码（其他银行）
        $param['customs_flag'] = 1;//是否报关
        //报关
        $param['customs_code'] = '';//
        $param['ic_name'] = '';//
        $param['ic_number'] = '';
        $param['payer_ecomme_rce_id'] = '';
        $param['payer_tel'] = '';//
        $param['order_type'] = '';

        $param['rem'] = '';//备注
        $param['reserved1'] = '';
        $param['reserved2'] = '';
        $param['ver'] = '1.0.0';//版本号

        $md5 = $this->sign(implode('|',$param));
        $param['md5'] = $md5;

        $html = '<html><head></head><body>';
        $html .= '<form method="post" name="E_FORM" action="'.$this->api['saveOrder'].'">';
        foreach ($param as $key => $val){
            $html .= "<input type='hidden' name='$key' value='$val' />";
        }
        $html .= '</form><script type="text/javascript">document.E_FORM.submit();</script>';
        $html .= '</body></html>';
        echo $html;
        exit;

    }

    /**
     * 生成签名
     * @param $value
     * @return string
     */
    public function sign($value){
        return md5($value.'|'.$this->key);
    }

    /**
     * 返回地址验证(同步)
     *
     * @param
     * @return boolean
     */
    public function return_verify(){
        $data = array();
        $data['mchnt_cd'] = $_POST['mchnt_cd'];
        $data['order_id'] = $_POST['order_id'];
        $data['order_date'] = $_POST['order_date'];
        $data['order_amt'] = $_POST['order_amt'];
        $data['order_st'] = $_POST['order_st'];
        $data['order_pay_code'] = $_POST['order_pay_code'];
        $data['order_pay_error'] = $_POST['order_pay_error'];
        $data['resv1'] = $_POST['resv1'];
        $data['fy_ssn'] = $_POST['fy_ssn'];

        $sign = $this->sign(implode('|',$data));
        $md5 = $_POST['md5'];
        if($sign == $md5 && $md5){
            if($data['order_st'] == '11'){
                //已支付
                $this->pay_result = true;
            }
            return true;
        }
        return false;
    }

    /**
     * 取得订单支付状态，成功或失败
     *
     * @param array $param
     * @return array
     */
    public function getPayResult($param){
        return $this->pay_result;
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
     * 手机端下单
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
        $ret['ono'] = $cardInfo['bank_id'];
        $ret['backurl'] = option("config.wap_site_url") ."/fuiou_back.php";//option("config.wap_site_url") ."/paynotice.php";
        $ret['reurl'] = option("config.wap_site_url") ."/pay.php?id=". option("config.orderid_prefix") .$order_no;
        $ret['homeurl'] = option("config.wap_site_url") ."/fuiou_back.php";
        $ret['name'] = $cardInfo['name'];
        $ret['sfz'] = $cardInfo['cardid'];
        $ret['md5'] = $this->sign($fu_order_id);
        return $ret;
    }


}