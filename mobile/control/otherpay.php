<?php
class otherpayControl{
	
	public function  indexOp(){
		$sn=$_GET["sn"];
		if(empty($sn)){
			output_error('参数错误');
		}
		$sn=decrypt($sn);
		$sn_result=explode(',',$sn);
	    if(empty($sn_result)|| count($sn_result)==1){
	    	output_error('参数错误');
	    }
	    $sender_name="";
	    $order_ids=array();
	    foreach($sn_result as $i=>$v){
	    	if($i==0){
	    		$sender_name=$v;
	    	}else{
	    		$order_ids[]=$v;
	    	}
	    }
	    $model_order=Model("order");
	    $order_list=$model_order->getOrderList(array("order_id"=>array("in",$order_ids),"order_state"=>ORDER_STATE_NEW));
	    if(count($order_list)!=count($order_ids)){
	    	output_error('订单已调整变化');
	    }
	    $pay_amount=0;
	    $pay_sn=$order_list[0]["pay_sn"];
	    $member_id=$order_list[0]["buyer_id"];
	    foreach ($order_list as $k=>$val){
	    	$pay_amount+=$val['order_amount'] - $val['rcb_amount'] - $val['pd_amount'];
	    	if($pay_sn!=$val["pay_sn"]){
	    		output_error('订单已调整变化');
	    	}
	    	if($member_id!=$val["buyer_id"]){
	    		output_error('订单已调整变化');
	    	}
	    }
	    $order_goods_list=$model_order->getOrderGoodsList(array("order_id"=>array("in",$order_ids)));
	    $goods_ids=array();
	    foreach ($order_goods_list as $value){
	    	$goods_ids[]=$value["goods_id"];
	    }
	    $goods_list=Model("goods")->getGoodsList(array("goods_id"=>array("in",$goods_ids)),"goods_id,goods_commonid,goods_spec");
	    if(count($goods_list)!=count($goods_ids)){
	    	output_error('商品获取失败');
	    }
	    $goods_list_key_val=array();
	    foreach ($goods_list as $v){
	    	$goods_list_key_val[$v['goods_id']]=$v;
	    }
	    $remark_iamge="";
	    $remark_text="ta在拼单网ipvp.cn采购了";
	    foreach ($order_goods_list as $i=>$value){
	    	$order_goods_list[$i]["goods_commonid"]=$goods_list_key_val[$value['goods_id']]["goods_commonid"];
	    	$s_array = unserialize($goods_list_key_val[$value["goods_id"]]["goods_spec"]);
	    	$tmp_spec_val_array=array();
	    	if(!empty($s_array)&& is_array($s_array)){
	    		foreach ($s_array as $k => $v) {
	    			$tmp_spec_val_array[]=$v;
	    		}
	    		$order_goods_list[$i]["goods_spec_name"]=implode(' ',$tmp_spec_val_array);
	    	}else{
	    		$order_goods_list[$i]["goods_spec_name"]=$value["goods_name"];
	    	}
	    	$order_goods_list[$i]["goods_image"]=cthumb($value["goods_image"],"240",$value["store_id"]);
	    	if($i==0){
	    		$remark_iamge=$order_goods_list[$i]["goods_image"];
	    	}
	    }
	    $common_order_list=array();
	    
	    foreach ($order_goods_list as $i=>$value){
	    	if(!isset($common_order_list[$value["goods_commonid"]])){
	    		$common_order_list[$value["goods_commonid"]]["common_name"]=str_replace($value["goods_spec_name"],"",$value["goods_name"]);
	    		if($i==0){
	    			$remark_text.=$common_order_list[$value["goods_commonid"]]["common_name"];
	    		}
	    	}
	    	unset($value["goods_name"]);
	    	$common_order_list[$value["goods_commonid"]]["order_goods"][]=$value;
	    }
	    $common_count=count($common_order_list);
	    if($common_count>1){
	    	$remark_text.="等".$common_count."款商品,".count($goods_ids)."种规格";
	    }else{
	    	$remark_text.="共".count($goods_ids)."种规格";
	    }
	    
	    
	    $payment_list = Model('mb_payment')->getMbPaymentOpenList();
	    $payment_array = array();
	    if(!empty($payment_list)) {
	    	foreach ($payment_list as $value) {
	    		$payment_array[] = array('payment_code' => $value['payment_code'],'payment_name' =>$value['payment_name']);
	    	}
	    }
	    output_data(array("pay_sn"=>encrypt($pay_sn.','.$member_id),"sender_name"=>$sender_name,"pay_amount"=>number_format($pay_amount,2),"remark_image"=>$remark_iamge,"remark_text"=>$remark_text,"common_order_list"=>$common_order_list,"payment_list"=>$payment_array));
	}
	
	public function payOp(){
		$sn=$_GET["sn"];
		if(empty($sn)){
			$this->_payerror("参数错误");
		}
		$sn=decrypt($sn);
		$sn_result=explode(',',$sn);
		if(empty($sn_result)|| count($sn_result)==1){
			$this->_payerror('参数错误');
		}
		$pay_sn =$sn_result[0];
		$member_id=$sn_result[1];
		
		$model_mb_payment = Model('mb_payment');
		$logic_payment = Logic('payment');
		
		$condition = array();
		$condition['payment_code'] =isset($_GET['payment_code']) && trim($_GET['payment_code']) != '' ? trim($_GET['payment_code']) :'alipay';
		$mb_payment_info = $model_mb_payment->getMbPaymentOpenInfo($condition);
		if(!$mb_payment_info) {
			$this->_payerror('系统不支持选定的支付方式');
		}
		//重新计算所需支付金额
		$result = $logic_payment->getRealOrderInfo($pay_sn,$member_id);
		
		if(!$result['state']) {
			$this->_payerror("支付错误");
		}
		//第三方API支付
		$this->_api_pay($result['data'], $mb_payment_info,$condition['payment_code']);
	}
	private function _api_pay($order_pay_info, $mb_payment_info,$payment_code) {
		if($payment_code== 'wxpay') {
			setcookie("pay_sn",$order_pay_info['pay_sn']);
			setcookie("pay_amount",$order_pay_info['api_pay_amount']);
			$wxpayurl='http://ipvp.cn/mobile/api/payment/wxpay_v3/wx_jspay.php';
			redirect($wxpayurl);
		} else {
			$inc_file = BASE_PATH.DS.'api'.DS.'payment'.DS.$payment_code.DS.$payment_code.'.php';
			if(!is_file($inc_file)){
				output_error('支付接口不存在');
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
		}
		exit();
	}
	private function _payerror($text){
		header("Content-type: text/html; charset=utf-8");
		exit($text);
	}
	//获取短域名(百度短域名)
	public function get_tingurlOp(){
		require_once(BASE_DATA_PATH .'/api/utils/tingurl.php');
		header("Content-type: text/html; charset=utf-8");
		exit(get_tingurl($_POST["url"]));
	}
	
	public function get_wx_signpackageOp(){
		require_once(BASE_DATA_PATH .'/api/utils/wx_jssdk.php');
		$jssdk = new WX_JSSDK();
		$signPackage = $jssdk->getSignPackage($_GET["url"]);
		exit(json_encode($signPackage));
	}
}