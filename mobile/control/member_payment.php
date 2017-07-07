<?php
/**
 * 支付
 *
 *
 *
 *
 * by 33hao.com 好商城V3 运营版
 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class member_paymentControl extends mobileMemberControl {

    private $payment_code = 'alipay';

	public function __construct() {
		parent::__construct();
        $this->payment_code = isset($_GET['payment_code']) && trim($_GET['payment_code']) != '' ? trim($_GET['payment_code']) :'alipay';
	}

    /**
     * 实物订单支付
     */
    public function payOp() {
	    $pay_sn = $_GET['pay_sn'];

        $model_mb_payment = Model('mb_payment');
        $logic_payment = Logic('payment');

        $condition = array();
        $condition['payment_code'] = $this->payment_code;
        $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
        if(!$mb_payment_info) {
            output_error('系统不支持选定的支付方式');
        }

        //重新计算所需支付金额
        $result = $logic_payment->getRealOrderInfo($pay_sn, $this->member_info['member_id']);

        if(!$result['state']) {
            output_error($result['msg']);
        }
        if($this->payment_code=='predeposit'){//余额支付
        	redirect(WAP_SITE_URL."/tmpl/member/predeposit_pay.html?pay_sn=".$pay_sn);
        }else{
           //第三方API支付
           $this->_api_pay($result['data'], $mb_payment_info);
        }
    }

    /**
     * 虚拟订单支付
     */
    public function vr_payOp() {
        $order_sn = $_GET['pay_sn'];
    
        $model_mb_payment = Model('mb_payment');
        $logic_payment = Logic('payment');
    
        $condition = array();
        $condition['payment_code'] = $this->payment_code;
        $mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
        if(!$mb_payment_info) {
            output_error('系统不支持选定的支付方式');
        }
    
        //重新计算所需支付金额
        $result = $logic_payment->getVrOrderInfo($order_sn, $this->member_info['member_id']);
    
        if(!$result['state']) {
            output_error($result['msg']);
        }
    
        //第三方API支付
        $this->_api_pay($result['data'], $mb_payment_info);
    }

	/**
	 * 第三方在线支付接口
	 *
	 */
	private function _api_pay($order_pay_info, $mb_payment_info) {
		if($this->payment_code == 'wxpay') {
			setcookie("pay_sn",$order_pay_info['pay_sn']);
			setcookie("pay_amount",$order_pay_info['api_pay_amount']);
			$wxpayurl='http://ipvp.cn/mobile/api/payment/wxpay_v3/wx_jspay.php';
    		redirect($wxpayurl);
    	} else {
    		$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';
    		if(!is_file($inc_file)){
    			output_error('支付接口不存在');
    		}
			require($inc_file);
			$param = array();
			$param = $mb_payment_info['payment_config'];
			$param['order_sn'] = $order_pay_info['pay_sn'];
			$param['order_amount'] = $order_pay_info['api_pay_amount'];
			$param['order_type'] = ($order_pay_info['order_type'] == 'real_order' ? 'r' : 'v');
			$payment_api = new $this->payment_code();
			$return = $payment_api->submit($param);
			echo $return;
    	}
    	exit();
	}

    /**
     * 可用支付参数列表
     */
    public function payment_listOp() {
        $model_mb_payment = Model('mb_payment');

        $payment_list = $model_mb_payment->getMbPaymentOpenList();

        $payment_array = array();
        if(!empty($payment_list)) {
            foreach ($payment_list as $value) {
                $payment_array[] = $value['payment_code'];
            }
        }

        output_data(array('payment_list' => $payment_array));
    }
    //余额支付
    public function predeposit_payOp(){
    	$pay_sn=$_POST["pay_sn"];
    	$paypwd=$_POST["paypwd"];

    	if(md5($paypwd)!=$this->member_info['member_paypwd']){
    		output_error('支付密码错误');
    	}
    	$logic_payment = Logic('payment');
    	//重新计算所需支付金额
    	$result = $logic_payment->getRealOrderInfo($pay_sn, $this->member_info['member_id']);
    	if($this->member_info["available_predeposit"]<$result['data']["api_pay_amount"]){
    		output_error('余额不足');
    	}
    	$order_sns=array();
    	foreach ($result['data']["order_list"] as $order){
    		$order_sns[]=$order["order_sn"];
    	}
    	//减少余额
    	$pddata=array();
    	$pddata["member_id"]=$this->member_info['member_id'];
    	$pddata["member_name"]=$this->member_info["member_name"];
    	$pddata['amount']=$result['data']["api_pay_amount"];
    	$pddata['order_sn']=implode("|",$order_sns);
    	Model("predeposit")->changePd("order_pay",$pddata);
    	$logic_payment->updateRealOrder($pay_sn,'predeposit', $result['data']["order_list"],$pay_sn);
    	output_data("ok");
    }
}
