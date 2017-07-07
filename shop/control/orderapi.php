<?php

require_once(BASE_CORE_PATH."/framework/function/aes.php");
require_once(BASE_CORE_PATH."/framework/function/rsa.php");

/**
 * Created by PhpStorm.
 * User: yuanshian
 * Date: 2015/9/10
 * Time: 12:07
 */
class orderapiControl extends BaseHomeControl
{
	/**
	 * 系统订单的列表
	 *
	 */
	public function order_listOp() {
		$model_order = Model('order');

		//搜索
		$condition = array();
		if($_GET['last_order_id']!='')
			$condition['order_id'] = array('GT',$_GET['last_order_id']);
		else if ($_GET['order_ids'] != '' && preg_match('/^(\d+,?)+$/',$_GET['order_ids']) )
			$condition['order_id'] = array('IN',$_GET['order_ids']);
		else
			exit;
		$order_list = $model_order->getOrderList($condition, 20, '*', 'order_id asc','', array('order_common'));//array('order_common','order_goods','store'));

		echo json_encode($order_list);
	}
	/**
	 * 系统订单变更操作记录
	 *
	 */
	public function order_logOp() {
		$model_order = Model('order');

		$logId = "0";
		if ($_GET['last_log_id'] != '')
			$logId = $_GET['last_log_id'];

		$condition = array();
		$condition['log_id'] = array('GT',$logId);

		//搜索
		$order_loglist = $model_order->getOrderLogListByPage($condition,40);

		echo json_encode($order_loglist);
	}
	/**
	 * 获取退款/退货信息列表
	 *
	 */
	public function order_refundOp() {
		$model_order = Model('order');

		$updatetime = "0";
		if ($_GET['last_refund_time'] != '')
			$updatetime = $_GET['last_refund_time'];

		$condition = array();
		$condition['update_time'] = array('GT',$updatetime);

		//搜索
		$order_refundlist = $model_order->getOrderRefundListByPage($condition,40);

		echo json_encode($order_refundlist);
	}

	/**
	 * 物流跟踪
	 */
	public function search_deliverOp(){
		Language::read('member_member_index');
		$lang	= Language::getLangContent();

		$order_sn = $_GET['order_sn'];
		$store_id = $_GET['store_id'];
		if (!is_numeric($order_sn)) showMessage(Language::get('wrong_argument'),'','html','error');
		$model_order	= Model('order');
		$condition['order_sn'] = $order_sn;
		$condition['store_id'] = $store_id;
		$order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
		if (empty($order_info) || $order_info['shipping_code'] == '') {
			showMessage('未找到信息','','html','error');
		}

		//取得配送公司代码
		$express = rkcache('express',true);
		Tpl::output('e_code',$express[$order_info['extend_order_common']['shipping_express_id']]['e_code']);
		Tpl::output('e_name',$express[$order_info['extend_order_common']['shipping_express_id']]['e_name']);
		Tpl::output('e_url',$express[$order_info['extend_order_common']['shipping_express_id']]['e_url']);
		Tpl::output('shipping_code',$order_info['shipping_code']);

		self::profile_menu('search','search');
		Tpl::showpage('store_deliver.detail');
	}

	/**
	 * 从第三方取快递信息
	 *
	 */
	public function get_expressOp(){
		$url = 'http://www.kuaidi100.com/query?type='.$_GET['e_code'].'&postid='.$_GET['shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
		import('function.ftp');
		$content = dfsockopen($url);
		$content = json_decode($content,true);
		if ($content['status'] != 200) exit(json_encode(false));
		$content['data'] = array_reverse($content['data']);
		$output = '';
		if (is_array($content['data'])){
			foreach ($content['data'] as $k=>$v) {//
				if ($v['time'] == '') continue;
				$output .= $v['time'].'  '.$v['context']."\r\n";
			}
		}
		if ($output == '') exit(json_encode(false));
		if (strtoupper(CHARSET) == 'GBK'){
			$output = Language::getUTF8($output);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
		}
		//echo json_encode($output);
		echo $output;
	}

	/*
	 * 获取订单的详细信息
	 * */
	public function get_orderinfoOp()
	{
		$order_id = intval($_GET['order_id']);
		if($order_id <= 0 ){
			showMessage(L('miss_order_number'));
		}
		$model_order	= Model('order');
		$order_info	= $model_order->getOrderInfo(array('order_id'=>$order_id),array('order_goods','order_common','store'));

		//商品分类
		$goods_ids = array();
		foreach($order_info['extend_order_goods'] as $goods){
			$goods_ids[] = $goods['goods_id'];
		}
		$goods_model = Model('goods');
		$common_ids = $goods_model->getGoodsList(array('goods_id'=>array('in',$goods_ids)),'goods_id,goods_commonid');
		foreach($common_ids as $common){
			$common_goods[$common['goods_id']] = $common['goods_commonid'];
		}
		$result = array();
		//订单变更日志
		$log_list	= $model_order->getOrderLogList(array('order_id'=>$order_info['order_id']));
		$result['order_log'] = $log_list;

		//退款退货信息
		$model_refund = Model('refund_return');
		$condition = array();
		$condition['order_id'] = $order_info['order_id'];
		$condition['seller_state'] = 2;
		$condition['admin_time'] = array('gt',0);
		$return_list = $model_refund->getReturnList($condition);
		$result['return_list']=$return_list;

		//退款信息
		$refund_list = $model_refund->getRefundList($condition);
		$result['refund_list']=$refund_list;

		//卖家发货信息
		if (!empty($order_info['extend_order_common']['daddress_id'])) {
			$daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
			$result['daddress_info']=$daddress_info;
		}

		if($order_info["order_state"]==ORDER_STATE_SUCCESS){//订单交易成功  获取返现信息
			$pindan_rebates=Model("pindan")->getPindanRebatesList(array("order_id"=>$order_id,"rebates_state"=>1),"goods_id,total_rebates");
			if(!empty($pindan_rebates)){
				$goods_2_rebates=array();
				$order_total_rebates=0;
				foreach ($pindan_rebates as $v){
					$goods_2_rebates[$v["goods_id"]]=$v["total_rebates"];
					$order_total_rebates+=$v["total_rebates"];
				}
				$result['order_total_rebates']=$order_total_rebates;
				$result['goods_2_rebates']=$goods_2_rebates;
			}
		}

		$result["extend_common"]=$order_info['extend_order_common'];
		$result["order_goods"]=$order_info['extend_order_goods'];

		unset($order_info['extend_order_common']);
		unset($order_info['extend_order_goods']);
		$result['order_info']=$order_info;

		echo json_encode($result);
	}

	/*
	 * 生成AES256加密算法的KEY
	 * */
	public function generatekeyOp()
	{
		$key = aes::generateKey();
		echo $key;
	}

	/*
	 * 测试加密方法
	 * */
	public function encryptOp()
	{
		//$pfxfile = BASE_DATA_PATH."/ipvp_server.pfx";
		//$pfxpwd = 'P@ssw0rd'; //私钥密码
		$data = "Hello World!";

		$data = aes::encrypt_cbc($data,'GUuPjobO]tjzA&U_','GUuPjobO]tjzA&U_');
		echo "加密后的密文数据：$data";

		$data = aes::decrypt_cbc($data,'GUuPjobO]tjzA&U_','GUuPjobO]tjzA&U1');
		echo "解密后的原文数据：$data";
	}
	/*
	 * 发送短信
	 * */
    public function fxislwOp()
    {
        $code = intval($_GET['SendCode']);
        $mobile = $_GET['mobile'];
        
        $sms = new Sms();
		if($sms->send($mobile,"【拼单网客满分】验证码：".$code."，您正在使用会员宝的账户短信验证，验证码请勿泄露给他人")){
            echo "TRUE";
        }else{
            echo "FALSE";
        }
    }
	/*
	 * 发送短信
	 * */
    public function fxislwsdffOp()
    {
        $statdate = intval($_GET['statdate']);
        $url = $_GET['url'];
        $mobile = $_GET['mobile'];
		$turnover = $_GET['turnover'];
		$profit = $_GET['profit'];
        
        $sms = new Sms();
		$old_msg = '【拼单网数据魔方】亲爱的会员，你的门店经营数据报告('.$statdate.')已更新，点击链接：'.$url.'登录查看哦！';
		$msg = "【拼单网数据魔方】尊敬的会员，您的昨日经营数据已更新，门店昨日总营业额收入：{$turnover}元，总利润：{$profit}元，详情请点击：{$url}登录查看";
		if($sms->send($mobile, $msg)){
            echo "TRUE";
        }else{
            echo "FALSE";
        }
    }
    
   /*
    * 会员宝短信接口
    * tpl 模版号：1-验证码短信
    * */
    public function hybsendOp(){
    	$mobile=$_GET['mobile'];
    	$code=$_GET['code'];
    	$tpl=$_GET['tpl'];
    	$sms=new Sms();
    	if($tpl=='1' && !empty($mobile) && !empty($code)){
    		$time=date('Y-m-d h:i:s',time());
    		$msg="【拼单网客满分】您于 {$time} 申请找回密码操作，短信验证码为 {$code}。";
    	}else{
    		echo '404';
    		exit;
    	}
    	if($sms->send($mobile, $msg)){
            echo "TRUE";
        }else{
            echo "FALSE";
        }
    }
    
    /*
     * 会员宝短信测试接口只当作测试使用
     * */
    public function testOp(){
    	$mobile=$_GET['mobile'];
    	$code=$_GET['code'];
    	$tpl=$_GET['tpl'];
    	$sms=new Sms();
    	if($tpl=='1'  && !empty($mobile) && !empty($code)){
    		$time=date('Y-m-d h:i:s',time());
    		$msg="【拼单网客满分】您于 {$time} 申请找回密码操作，短信验证码为 {$code}。";
    	}else{
    		echo '404';
    		exit;
    	}
    	
    	echo 'TRUE';
    }

	public function umallfxOp(){
		$mobile=$_GET['mobile'];
		$code=$_GET['code'];
		$sms = new Sms();
		$msg = "【拼单网直邮宝】您正在申请分销，验证码：{$code}，验证码请勿泄露给他人。";
		if($sms->send($mobile, $msg)){
			echo "TRUE";
		}else{
			echo "FALSE";
		}
	}
}