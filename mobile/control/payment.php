<?php
/**
 * 支付回调
 *
 *
 *
 *
 * by 33hao.com 好商城V3 运营版
 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class paymentControl extends mobileHomeControl{

    private $payment_code;

	public function __construct() {
		parent::__construct();

        $this->payment_code = $_GET['payment_code'];
	}

    public function returnopenidOp(){
        $payment_api = $this->_get_payment_api();
        if($this->payment_code != 'wxpay'){
            output_error('支付参数异常');
            die;
        }

        $payment_api->getopenid();

    }

    /**
     * 支付回调
     */
    public function returnOp() {
        $pay_type = $_GET['payment_code'];
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getReturnInfo($payment_config);

        if($callback_info) {
            if($pay_type == 'fuiou'){
                $third_id =  $callback_info['out_trade_no'];
                $order = Model('order')->table('order')->where(array('third_id'=>$third_id))->find();
                $callback_info = array(
                    'out_trade_no'  =>    $order['pay_sn'],
                    'trade_no'  =>    $third_id,
                );
            }
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
                Tpl::output('result', 'success');
                Tpl::output('message', '支付成功');
            } else {
                Tpl::output('result', 'fail');
                Tpl::output('message', '支付失败');
			}
        } else {
			//验证失败
            Tpl::output('result', 'fail');
            Tpl::output('message', '支付失败');
		}

        Tpl::showpage('payment_message');
    }

    /**
     * 支付提醒
     */
    public function notifyOp() {
        $pay_type = $_GET['payment_code'];
        // 恢复框架编码的post值
        $_POST['notify_data'] = html_entity_decode($_POST['notify_data']);

        $payment_api = $this->_get_payment_api();

        $payment_config = $this->_get_payment_config();

        $callback_info = $payment_api->getNotifyInfo($payment_config);

        if($callback_info) {
            if($pay_type == 'fuiou'){
                $third_id =  $callback_info['out_trade_no'];
                $order = Model('order')->table('order')->where(array('third_id'=>$third_id))->find();
                $callback_info = array(
                    'out_trade_no'  =>    $order['pay_sn'],
                    'trade_no'  =>    $third_id,
                );
            }
            //验证成功
            $result = $this->_update_order($callback_info['out_trade_no'], $callback_info['trade_no']);
            if($result['state']) {
                if($this->payment_code == 'wxpay'){
                    echo $callback_info['returnXml'];
                    die;
                }else{
                    echo 'success';die;
                }

            }
		}

        //验证失败

        if($this->payment_code == 'wxpay'){
            echo '<xml><return_code><!--[CDATA[FAIL]]--></return_code></xml>';
            die;
        }else{
            echo "fail";die;
        }
    }

    /**
     * 获取支付接口实例
     */
    private function _get_payment_api() {
        $inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$this->payment_code.DS.$this->payment_code.'.php';

        if(is_file($inc_file)) {
            require($inc_file);
        }

        $payment_api = new $this->payment_code();

        return $payment_api;
    }

    /**
     * 获取支付接口信息
     */
    private function _get_payment_config() {
        $model_mb_payment = Model('mb_payment');

        //读取接口配置信息
        $condition = array();
        $condition['payment_code'] = $this->payment_code;
        $payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
        
        return $payment_info['payment_config'];
    }

    /**
     * 更新订单状态
     */
    public function _update_order($out_trade_no, $trade_no) {
        $model_order = Model('order');
        $logic_payment = Logic('payment');

        $tmp = explode('|', $out_trade_no);
        $out_trade_no = $tmp[0];
        if (!empty($tmp[1])) {
            $order_type = $tmp[1];
        } else {
            $order_pay_info = Model('order')->getOrderPayInfo(array('pay_sn'=> $out_trade_no));
            if(empty($order_pay_info)){
                $order_type = 'v';
            } else {
                $order_type = 'r';
            }
        }

        if ($order_type == 'r') {
            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            if (intval($result['data']['api_pay_state'])) {
                return array('state'=>true);
            }
            $order_list = $result['data']['order_list'];
            $result = $logic_payment->updateRealOrder($out_trade_no, $this->payment_code, $order_list, $trade_no);

        } elseif ($order_type == 'v') {
        	$result = $logic_payment->getVrOrderInfo($out_trade_no);
	        if ($result['data']['order_state'] != ORDER_STATE_NEW) {
	            return array('state'=>true);
	        }
	        $result = $logic_payment->updateVrOrder($out_trade_no, $this->payment_code, $result['data'], $trade_no);
        }

        return $result;
    }
	
	/**
     * 支付回调
     */
    public function wxpayreturnOp() {
        unset($_GET['act']);
        unset($_GET['op']);
        unset($_GET['payment_code']);

        $result_code= $_GET['result_code'] ;
        $out_trade_no=$_GET['out_trade_no'] ;
        $transaction_id=$_GET['transaction_id'] ;

        if($result_code == "FAIL"){
            Tpl::output('result', 'fail');
            Tpl::output('message', '支付失败');
        }
        else {
            //验证成功
            $result = $this->_update_order($out_trade_no, $transaction_id);
            if ($result['state']) {
                Tpl::output('result', 'success');
                Tpl::output('message', '支付成功');
            } else {
                Tpl::output('result', 'fail');
                Tpl::output('message', '支付失败');
            }
        }

        Tpl::showpage('payment_message');
    }
	
    /**
     * 微信支付提醒
     */
    public function wxpaynotifyOp() {
    	require_once BASE_PATH."/api/payment/wxpay_v3/lib/WxPay.Api.php";
    	require_once BASE_PATH."/api/payment/wxpay_v3/lib/WxPay.Notify.php";
    	$GLOBALS['_this']=$this;
    	$notify=new WxPayNotify();
    	$notify->Handle(function ($data,&$msg){
    		if(!array_key_exists("transaction_id", $data)){
    			$msg = "输入参数不正确";
    			return false;
    		}
    		//查询订单，判断订单真实性
    		$input = new WxPayOrderQuery();
    		$input->SetTransaction_id($data['transaction_id']);
    		$result = WxPayApi::orderQuery($input);
    		if(array_key_exists("return_code", $result)
    				&& array_key_exists("result_code", $result)
    				&& $result["return_code"] == "SUCCESS"
    				&& $result["result_code"] == "SUCCESS")
    		{
    			$out_trade_no=$data['out_trade_no'].'|r';
    			$transaction_id=$data['transaction_id'] ;
    			$update_result=$GLOBALS['_this']->_update_order($out_trade_no, $transaction_id);
    			if ($update_result['state']) {
    			     return true;
    			}else{
    				return false;
    			}
    		}else{
    			return false;
    		}
    		
    	},false);

    }

}
