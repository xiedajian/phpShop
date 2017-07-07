<?php
/*
 * 支付接口
 *
 * */
defined('InShopNC') or exit('Access Invalid!');
if (!@include(BASE_PATH.'/config.php')) exit('config.php isn\'t exists!');

class wap_payControl extends BaseAPPControl{
    public function __construct(){
        
    }
    /**
     * 实物订单支付
     */
    public function payOp() {
        $pay_sn = $_GET['pay_sn'];
        $payment_code=$_GET['payment_code'];

        $model_mb_payment = Model('mb_payment');
        $logic_payment = Logic('payment');

        $condition = array();
        $condition['payment_code'] = $payment_code;
        $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
        if(!$mb_payment_info) {
            output('系统不支持选定的支付方式',1);
        }

        //重新计算所需支付金额
        $result = $logic_payment->getRealOrderInfo($pay_sn);

        if(!$result['state']) {
            output($result['msg'],1);
        }
/*        if($payment_code=='predeposit'){//余额支付
            redirect(WAP_SITE_URL."/tmpl/member/predeposit_pay.html?pay_sn=".$pay_sn);
        }else{
            //第三方API支付
            $this->_api_pay($result['data'], $mb_payment_info);
        }*/
        $this->_api_pay($result['data'], $mb_payment_info,$payment_code);
    }
    /**
     * 第三方在线支付接口
     *
     */
    private function _api_pay($order_pay_info, $mb_payment_info,$payment_code) {

            $inc_file = BASE_PATH.DS.'payment_api'.DS.$payment_code.DS.$payment_code.'.php';
            if(!is_file($inc_file)){
                output('支付接口不存在',1);
            }
            require($inc_file);
            $param = array();
            $param = $mb_payment_info['payment_config'];
            $param['order_sn'] = $order_pay_info['pay_sn'];
            $param['order_amount'] = $order_pay_info['api_pay_amount'];
            $param['order_type'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
            $payment_api = new $payment_code();
            $return = $payment_api->submit($param);
            echo $return;
       
        exit();
    }
}