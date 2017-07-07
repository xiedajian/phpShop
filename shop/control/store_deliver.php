<?php
/**
 * 发货
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');

class store_deliverControl extends BaseSellerControl {
    const EXPORT_SIZE = 100;
	public function __construct() {
		parent::__construct();
		Language::read('member_store_index,deliver');
	}

	/**
	 * 发货列表
	 *
	 */
	public function indexOp() {
	    $model_order = Model('order');
		if (!in_array($_GET['state'],array('deliverno','delivering','delivered'))) $_GET['state'] = 'deliverno';
		$order_state = str_replace(array('deliverno','delivering','delivered'),
		        array(ORDER_STATE_PAY,ORDER_STATE_SEND,ORDER_STATE_SUCCESS),$_GET['state']);
		$condition = array();
        $condition['take_apart'] = 0;
		$condition['store_id'] = $_SESSION['store_id'];
		$condition['order_state'] = $order_state;
		if ($_GET['buyer_name'] != '') {
		    $condition['buyer_name'] = $_GET['buyer_name'];
		}
		if ($_GET['order_sn'] != '') {
		    $condition['order_sn'] = $_GET['order_sn'];
		}
		$if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
		$if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
		$start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
		$end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
		if ($start_unixtime || $end_unixtime) {
		    $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
		}
		$order_list = $model_order->getOrderList($condition,5,'*','order_id desc','',array('order_goods','order_common','member'));
		foreach ($order_list as $key => $order_info) {
		    foreach ($order_info['extend_order_goods'] as $value) {
		        $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
		        $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
		        $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
		        $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
		        if ($value['goods_type'] == 5) {
		            $order_info['zengpin_list'][] = $value;
		        } else {
		            $order_info['goods_list'][] = $value;
		        }
		    }

		    if (empty($order_info['zengpin_list'])) {
		        $order_info['goods_count'] = count($order_info['goods_list']);
		    } else {
		        $order_info['goods_count'] = count($order_info['goods_list']) + 1;
		    }
		    $order_list[$key] = $order_info;
		}
		Tpl::output('order_list',$order_list);
		Tpl::output('show_page',$model_order->showpage());
        Tpl::output('take_apart',$this->_have_take_apart());
		self::profile_menu('deliver',$_GET['state']);
		Tpl::showpage('store_order.deliver');
	}

	/**
	 * 发货
	 */
	public function sendOp(){
        $order_id = intval($_GET['order_id']);
		if ($order_id <= 0){
			showMessage(Language::get('wrong_argument'),'','html','error');
		}

		$model_order = Model('order');
		$condition = array();
		$condition['order_id'] = $order_id;
		$condition['store_id'] = $_SESSION['store_id'];
		$order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
		$if_allow_send = intval($order_info['lock_state']) || !in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND));
		if ($if_allow_send) {
		    showMessage(Language::get('wrong_argument'),'','html','error');
		}
        //--一发货即可提现--
		$store_info=Model('store')->table('store')->where(array('store_id'=>$order_info['store_id']))->find();
		if($store_info['settlement'] == 2){
			if($order_info['seller_money']<=0){
				//未结算
				$refund=Model('refund_return')->table('refund_return')->where(array('order_id'=>$order_info['order_id'],'refund_state'=>3,'seller_state'=>2))->find();
				$seller_money=0;//订单收入金额
				if($refund){
					$seller_money=$order_info['order_amount']-$refund['refund_amount'];
				}else{
					$seller_money=$order_info['order_amount'];
				}
				//取得拥金金额
				$field = 'SUM(ROUND(goods_pay_price*commis_rate/100,2)) as commis_amount';
				$order_goods_condition['order_id'] = $order_id;
				//$order_goods_condition['buyer_id'] = $_SESSION['member_id'];
				$order_goods_info = $model_order->getOrderGoodsInfo($order_goods_condition,$field);
				$commis_rate_totals_array[] = $order_goods_info['commis_amount'];
				$commis_amount_sum=floatval(array_sum($commis_rate_totals_array));
				if($commis_amount_sum>0)
				{
					$seller_money=$seller_money-$commis_amount_sum;
				}
				$order_info['seller_money']=$seller_money;
			}
		}
        //--一发货即可提现--
		if (chksubmit()){
			if($store_info['settlement'] == 2) {
				//检测是否货到付款方式
				$is_offline=($order_info['payment_code']=="offline");
				if ($seller_money > 0 && $is_offline == false) {
					//变更会员预存款
					$model_pd = Model('predeposit');
					$data = array();
					$data['msg'] = "";
					if ($commis_amount_sum > 0) {
						$data['msg'] = $commis_amount_sum;
					}
					$data['member_id'] = $store_info['member_id'];
					$data['member_name'] = $store_info['member_name'];
					$data['amount'] = $seller_money;
					$data['pdr_sn'] = $order_info['order_sn'];
					$model_pd->changePd('seller_money', $data);
				}
			}
		    $logic_order = Logic('order');
		    $_POST['reciver_info'] = $this->_get_reciver_info();
		    $result = $logic_order->changeOrderSend($order_info, 'seller', $_SESSION['member_name'], $_POST);
			if(!$result['state']){
			    showMessage($result['msg'],'','html','error');
			}else {
			    showDialog($result['msg'],$_POST['ref_url'],'succ');
			}
		}
        //--一发货即可提现--

        //--一发货即可提现--


        Tpl::output('order_info',$order_info);
		//取发货地址
		$model_daddress = Model('daddress');
		if ($order_info['extend_order_common']['daddress_id'] > 0 ){
			$daddress_info = $model_daddress->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
		}else{
		    //取默认地址
			$daddress_info = $model_daddress->getAddressList(array('store_id'=>$_SESSION['store_id']),'*','is_default desc',1);
			$daddress_info = $daddress_info[0];

            //写入发货地址编号
            $this->_edit_order_daddress($daddress_info['address_id'], $order_id);
		}
		Tpl::output('daddress_info',$daddress_info);

		$express_list  = rkcache('express',true);
		//如果是自提订单，只保留自提快递公司
		if (isset($order_info['extend_order_common']['reciver_info']['dlyp'])&&$order_info['extend_order_common']['reciver_info']['dlyp']>0) {
		    foreach ($express_list as $k => $v) {
		        if ($v['e_zt_state'] == '0') unset($express_list[$k]);
		    }
		    $my_express_list = array_keys($express_list);
		} else {
		    //快递公司
		    $my_express_list = Model()->table('store_extend')->getfby_store_id($_SESSION['store_id'],'express');
		    if (!empty($my_express_list)){
		        $my_express_list = explode(',',$my_express_list);
		    }
		}
		Tpl::output('my_express_list',$my_express_list);
		Tpl::output('express_list',$express_list);
		Tpl::showpage('store_deliver.send');
	}

	/**
	 * 编辑收货地址
	 * @return boolean
	 */
	public function buyer_address_editOp() {
	    $order_id = intval($_GET['order_id']);
	    if ($order_id <= 0) return false;
	    $model_order = Model('order');
		$condition = array();
		$condition['order_id'] = $order_id;
		$condition['store_id'] = $_SESSION['store_id'];
		$order_common_info = $model_order->getOrderCommonInfo($condition);
        if (!$order_common_info) return false;
        $order_common_info['reciver_info'] = @unserialize($order_common_info['reciver_info']);
		Tpl::output('address_info',$order_common_info);

		Tpl::showpage('store_deliver.buyer_address.edit','null_layout');
	}

    /**
     * 收货地址保存
     */
    public function buyer_address_saveOp() {
        $model_order = Model('order');
        $data = array();
        $data['reciver_name'] = $_POST['reciver_name'];
        $data['reciver_info'] = $this->_get_reciver_info();
        $condition = array();
        $condition['order_id'] = intval($_POST['order_id']);
        $condition['store_id'] = $_SESSION['store_id'];
        $result = $model_order->editOrderCommon($data, $condition);
        if($result) {
            echo 'true';
        } else {
            echo 'flase';
        }
    }

    /**
     * 组合reciver_info
     */
    private function _get_reciver_info() {
        $reciver_info = array(
            'address' => $_POST['reciver_area'] . ' ' . $_POST['reciver_street'],
            'phone' => trim($_POST['reciver_mob_phone'] . ',' . $_POST['reciver_tel_phone'],','),
            'area' => $_POST['reciver_area'],
            'street' => $_POST['reciver_street'],
            'mob_phone' => $_POST['reciver_mob_phone'],
            'tel_phone' => $_POST['reciver_tel_phone'],
            'dlyp' => $_POST['reciver_dlyp']
        );
        return serialize($reciver_info);
    }

	/**
	 * 选择发货地址
	 * @return boolean
	 */
	public function send_address_selectOp() {
	    Language::read('deliver');
	    $address_list = Model('daddress')->getAddressList(array('store_id'=>$_SESSION['store_id']));
	    Tpl::output('address_list',$address_list);
	    Tpl::output('order_id', $_GET['order_id']);
	    Tpl::showpage('store_deliver.daddress.select','null_layout');
	}

    /**
     * 保存发货地址修改
     */
    public function send_address_saveOp() {
        $result = $this->_edit_order_daddress($_POST['daddress_id'], $_POST['order_id']);
        if($result) {
            echo 'true';
        } else {
            echo 'flase';
        }
    }

    /**
     * 修改发货地址
     */
    private function _edit_order_daddress($daddress_id, $order_id) {
        $model_order = Model('order');
        $data = array();
        $data['daddress_id'] = intval($daddress_id);
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        return $model_order->editOrderCommon($data, $condition);
    }

    /**
	 * 物流跟踪
	 */
	public function search_deliverOp(){
		Language::read('member_member_index');
		$lang	= Language::getLangContent();

		$order_sn	= $_GET['order_sn'];
		if (!is_numeric($order_sn)) showMessage(Language::get('wrong_argument'),'','html','error');
		$model_order	= Model('order');
		$condition['order_sn'] = $order_sn;
		$condition['store_id'] = $_SESSION['store_id'];
		$order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
		if (empty($order_info) || $order_info['shipping_code'] == '') {
		    showMessage('未找到信息','','html','error');
		}
		$order_info['state_info'] = orderState($order_info);
		Tpl::output('order_info',$order_info);
		//卖家发货信息
		$daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
		Tpl::output('daddress_info',$daddress_info);

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
	 * 延迟收货
	 */
	public function delay_receiveOp(){
	    $order_id = intval($_GET['order_id']);
	    $model_trade = Model('trade');
	    $model_order = Model('order');
	    $condition = array();
	    $condition['order_id'] = $order_id;
	    $condition['store_id'] = $_SESSION['store_id'];
	    $condition['lock_state'] = 0;
	    $order_info = $model_order->getOrderInfo($condition);

	    //取目前系统最晚收货时间
	    $delay_time = $order_info['delay_time'] + ORDER_AUTO_RECEIVE_DAY * 3600 * 24;
	    if (chksubmit()) {
	        $delay_date = intval($_POST['delay_date']);
	        if (!in_array($delay_date,array(5,10,15))) {
	            showDialog(Language::get('wrong_argument'),'','error',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
	        }
	        $update = $model_order->editOrder(array('delay_time'=>array('exp','delay_time+'.$delay_date*3600*24)),$condition);
	        if ($update) {
	            //新的最晚收货时间
	            $dalay_date = date('Y-m-d H:i:s',$delay_time+$delay_date*3600*24);
	            showDialog("成功将最晚收货期限延迟到了".$dalay_date.'&emsp;','','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();',4);
	        } else {
	            showDialog('延迟失败','','succ',empty($_GET['inajax']) ?'':'CUR_DIALOG.close();');
	        }
        } else {
            $order_info['delay_time'] = $delay_time;
            Tpl::output('order_info',$order_info);
            Tpl::showpage('store_deliver.delay_receive','null_layout');
            exit();
        }
	}

	/**
	 * 从第三方取快递信息
	 *
	 */
	public function get_expressOp(){
        $url = 'http://www.kuaidi100.com/query?type='.$_GET['e_code'].'&postid='.$_GET['shipping_code'].'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
        //import('function.ftp');
        $content = file_get_contents($url);//dfsockopen($url);
        $content = json_decode($content,true);
        if ($content['status'] != 200) exit(json_encode(false));
        $content['data'] = array_reverse($content['data']);
        $output = '';
        if (is_array($content['data'])){
            foreach ($content['data'] as $k=>$v) {
                if ($v['time'] == '') continue;
                $output .= '<li>'.$v['time'].'&nbsp;&nbsp;'.$v['context'].'</li>';
            }
        }
        if ($output == '') exit(json_encode(false));
        if (strtoupper(CHARSET) == 'GBK'){
            $output = Language::getUTF8($output);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }
        echo json_encode($output);
	}

    /**
     * 运单打印
     */
    public function waybill_printOp() {
        $order_id = intval($_GET['order_id']);
        if($order_id <= 0) {
            showMessage(L('param_error'));
        }

        $model_order = Model('order');
        $model_store_waybill = Model('store_waybill');
        $model_waybill = Model('waybill');

        $order_info = $model_order->getOrderInfo(array('order_id' => intval($_GET['order_id'])), array('order_common'));

        $store_waybill_list = $model_store_waybill->getStoreWaybillList(array('store_id' => $order_info['store_id']), 'is_default desc');

        $store_waybill_info = $this->_getCurrentWaybill($store_waybill_list, $_GET['store_waybill_id']);
        if(empty($store_waybill_info)) {
            showMessage('请首先绑定打印模板', urlShop('store_waybill', 'waybill_manage'), '', 'error');
        }

        $waybill_info = $model_waybill->getWaybillInfo(array('waybill_id' => $store_waybill_info['waybill_id']));
        if(empty($waybill_info)) {
            showMessage('请首先绑定打印模板', urlShop('store_waybill', 'waybill_manage'), '', 'error');
        }

        //根据订单内容获取打印数据
        $print_info = $model_waybill->getPrintInfoByOrderInfo($order_info);

        //整理打印模板
        $store_waybill_data = unserialize($store_waybill_info['store_waybill_data']);
        foreach ($waybill_info['waybill_data'] as $key => $value) {
            $waybill_info['waybill_data'][$key]['show'] = $store_waybill_data[$key]['show'];
            $waybill_info['waybill_data'][$key]['content'] = $print_info[$key];
        }

        //使用商家自定义的偏移尺寸
        $waybill_info['waybill_pixel_top'] = $store_waybill_info['waybill_pixel_top'];
        $waybill_info['waybill_pixel_left'] = $store_waybill_info['waybill_pixel_left'];

        Tpl::output('waybill_info', $waybill_info);
        Tpl::output('store_waybill_list', $store_waybill_list);
        Tpl::showpage('waybill.print', 'null_layout');
    }

    /**
     * 获取当前打印模板
     */
    private function _getCurrentWaybill($store_waybill_list, $store_waybill_id) {
        if(empty($store_waybill_list)) {
            return false;
        }

        $store_waybill_id = intval($store_waybill_id);

        $store_waybill_info = null;

        //如果指定模板使用指定的模板，未指定使用默认模板
        if($store_waybill_id > 0) {
            foreach ($store_waybill_list as $key => $value) {
                if($store_waybill_id == $value['store_waybill_id']) {
                    $store_waybill_info = $store_waybill_list[$key];
                    break;
                }
            }
        }

        if(empty($store_waybill_info)) {
            $store_waybill_info = $store_waybill_list[0];
        }

        return $store_waybill_info;
    }

	/**
	 * 用户中心右边，小导航
	 *
	 * @param string	$menu_type	导航类型
	 * @param string 	$menu_key	当前导航的menu_key
	 * @return
	 */
	private function profile_menu($menu_type,$menu_key='') {
		Language::read('member_layout');
		$menu_array		= array();
		switch ($menu_type) {
			case 'deliver':
				$menu_array = array(
				array('menu_key'=>'deliverno',			'menu_name'=>Language::get('nc_member_path_deliverno'),	'menu_url'=>'index.php?act=store_deliver&op=index&state=deliverno'),
				array('menu_key'=>'delivering',			'menu_name'=>Language::get('nc_member_path_delivering'),	'menu_url'=>'index.php?act=store_deliver&op=index&state=delivering'),
				array('menu_key'=>'delivered',		'menu_name'=>Language::get('nc_member_path_delivered'),	'menu_url'=>'index.php?act=store_deliver&op=index&state=delivered'),
				);
                if($this->_have_take_apart()){
                    $menu_array[] = 	array('menu_key'=>'take_apart',		'menu_name'=>'拆单列表',	'menu_url'=>'index.php?act=store_deliver&op=take_apart_list');
                }
				break;
			case 'search':
				$menu_array = array(
				1=>array('menu_key'=>'nodeliver',			'menu_name'=>Language::get('nc_member_path_deliverno'),	'menu_url'=>'index.php?act=store_deliver&op=index&state=nodeliver'),
				2=>array('menu_key'=>'delivering',			'menu_name'=>Language::get('nc_member_path_delivering'),	'menu_url'=>'index.php?act=store_deliver&op=index&state=delivering'),
				3=>array('menu_key'=>'delivered',		'menu_name'=>Language::get('nc_member_path_delivered'),	'menu_url'=>'index.php?act=store_deliver&op=index&state=delivered'),
				4=>array('menu_key'=>'search',		'menu_name'=>Language::get('nc_member_path_deliver_info'),	'menu_url'=>'###'),
				);
				break;
		}
		Tpl::output('member_menu',$menu_array);
		Tpl::output('menu_key',$menu_key);
	}

    //拆单
    public function take_apartOp(){
        if(!$this->_have_take_apart()){
            exit;
        }
        $order_id = intval($_GET['order_id']);
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['order_state'] = 20;//未发货
        $condition['take_apart'] = 0;//未拆单
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if(!$order_info){
            showDialog('订单不存在');
        }
        $express_list  = rkcache('express',true);

        //提交拆单
        if(chksubmit()){
            $all_goods = array();
            foreach($order_info['extend_order_goods'] as $goods){
                $all_goods[$goods['goods_id']] = $goods['goods_num'];
            }
            $model_area = Model('area');
            $area_list = $model_area->getAreas();
            $area_list = $area_list['name'];
            $sub_order = array();//子订单信息
            if(count($_POST['sub_order'])>0){//有拆单
                foreach($_POST['sub_order'] as $k=>$order){
                    if(!$order){
                        continue;
                    }
                    $sub_order[$k]['order_id'] = $order_info['order_id'];
                    $sub_order[$k]['order_sn'] = $order_info['order_sn'];
                    $sub_order[$k]['buyer_id'] = $order_info['buyer_id'];
                    $sub_order[$k]['store_id'] = $order_info['store_id'];
                    if($k+1<10){
                        $sub_sn = '-00'.($k+1);
                    }else if($k+1<10){
                        $sub_sn = '-0'.($k+1);
                    }else{
                        $sub_sn = '-'.($k+1);
                    }
                    $sub_order[$k]['sub_order_sn'] = $order_info['order_sn'].$sub_sn;
                    $sub_order[$k]['other_order_sn'] =  $_POST['other_sn'][$k];
                    $sub_order[$k]['seller'] =  $_POST['seller_name'][$k];
                    $sub_order[$k]['remark'] = $_POST['remark'][$k];//备注
                    $sub_order[$k]['addtime'] = TIMESTAMP;
                    //是否发货
                    if($_POST['express_id'][$k]&&$_POST['shipping_code'][$k]&&$express_list[$_POST['express_id'][$k]]){
                        $sub_order[$k]['express_id'] =  $_POST['express_id'][$k];
                        $sub_order[$k]['shipping_code'] =  $_POST['shipping_code'][$k];
                        $sub_order[$k]['e_name'] =  $express_list[$_POST['express_id'][$k]]['e_name'];
                        $sub_order[$k]['sub_order_state'] =  1;//发货
                        $sub_order[$k]['send_time'] =  TIMESTAMP;
                    }else{
                        $sub_order[$k]['express_id'] =  0;
                        $sub_order[$k]['shipping_code'] =  '';
                        $sub_order[$k]['e_name'] =  '';
                        $sub_order[$k]['sub_order_state'] =  0;
                        $sub_order[$k]['send_time'] =  0;
                    }
                    //收货人信息
                    //if($_POST['rec_name'][$k]&&$_POST['rec_mobile'][$k]&&$_POST['addr'][$k]){
                        $rec_array = array();
                        $area = explode(',', $_POST['area'][$k]);
                        if(count($area)==3){
                            $privince = $area_list[$area[0]];
                            $city = $area_list[$area[1]];
                            $qu = $area_list[$area[2]];
                            if($privince&&$city&&$qu){
                                $rec_array['province_id'] = $area[0];
                                $rec_array['city_id'] = $area[1];
                                $rec_array['qx_id'] = $area[2];
                                $rec_array['area_info'] = $privince.' '.$city.' '.$qu;
                               // $rec_array['address'] = $_POST['addr'][$k];
                               // $rec_array['postcode'] = $_POST['postcode'][$k];
                               // $rec_array['reciver_name'] = $_POST['rec_name'][$k];
                                //$rec_array['phone'] = $_POST['rec_mobile'][$k];
                            }
                        }
                        //if(empty($rec_array)){
                       //     showDialog('收货信息不完整！');
                        //}
                        $rec_array['address'] = $_POST['addr'][$k];
                        $rec_array['postcode'] = $_POST['postcode'][$k];
                        $rec_array['reciver_name'] = $_POST['rec_name'][$k];
                        $rec_array['phone'] = $_POST['rec_mobile'][$k];
                        $sub_order[$k]['reciver_info'] = serialize($rec_array);
                    //}else{
                    //    showDialog('收货人信息不完整！');
                    //}
                    //买家信息
                    //if($_POST['buyer_name'][$k]&&$_POST['buyer_id_card'][$k]){
                        $sub_order[$k]['buyer_info'] = serialize( array('buyer_name'=>$_POST['buyer_name'][$k],'buyer_id_card'=>$_POST['buyer_id_card'][$k]));
                    //}else{
                    //    showDialog('买家信息不完整！');
                   // }
                    //拆单商品信息
                    $id_num = explode(',',$order);
                    if($id_num){
                        $sub_goods =array();
                        foreach($id_num as $val){
                            $arr = explode('=',$val);
                            $arr[0] = intval($arr[0]);
                            $arr[1] = intval($arr[1]);
                            if($all_goods[$arr[0]]&&$all_goods[$arr[0]]>=$arr[1]&&$arr[1]>0){
                                $all_goods[$arr[0]]-=$arr[1];
                                $sub_goods[ $arr[0]]= $arr[1];
                            }else{
                                showDialog('商品拆单有误！');
                            }
                        }
                    }
                    if(empty($sub_goods)){
                        showDialog('商品拆单有误！');
                    }
                    $sub_order[$k]['goods_info'] = serialize($sub_goods);
                }
            }
            //退款
            $refund_goods =array();
            if($_POST['refund_order']){
                $refund = explode(',',$_POST['refund_order']);
                foreach($refund as $val){
                    $arr = explode('=',$val);
                    $arr[0] = intval($arr[0]);
                    $arr[1] = intval($arr[1]);
                    if($all_goods[$arr[0]]&&$all_goods[$arr[0]]>=$arr[1]&&$arr[1]>0){
                        $all_goods[$arr[0]]-=$arr[1];
                        $refund_goods[ $arr[0]]= $arr[1];
                    }else{
                        showDialog('商品拆单有误！');
                    }
                }
            }
            //拆单商品数量验证
            foreach($all_goods as $val){
                if($val!=0){
                    showDialog('拆单错误！');
                }
            }
            //退款
            $order_index = count($sub_order);
            if(count($refund_goods)>0){
                $sub_order[$order_index]['order_id'] = $order_info['order_id'];
                $sub_order[$order_index]['order_sn'] = $order_info['order_sn'];
                $sub_order[$order_index]['buyer_id'] = $order_info['buyer_id'];
                $sub_order[$order_index]['store_id'] = $order_info['store_id'];
                if($order_index+1<10){
                    $sub_sn = '-00'.($order_index+1);
                }else if($order_index+1<100){
                    $sub_sn = '-0'.($order_index+1);
                }else{
                    $sub_sn = '-'.($order_index+1);
                }
                $sub_order[$order_index]['sub_order_sn'] = $order_info['order_sn'].$sub_sn;
                $sub_order[$order_index]['other_order_sn'] = '';
                $sub_order[$order_index]['seller'] =  '';
                $sub_order[$order_index]['remark'] = '';
                $sub_order[$order_index]['addtime'] = TIMESTAMP;
                $sub_order[$order_index]['express_id'] =  0;
                $sub_order[$order_index]['shipping_code'] =  '';
                $sub_order[$order_index]['e_name'] =  '';
                $sub_order[$order_index]['sub_order_state'] =  2;
                $sub_order[$order_index]['send_time'] =  0;

                $sub_order[$order_index]['reciver_info'] = serialize(null);
                $sub_order[$order_index]['buyer_info'] = serialize(null);;
                $sub_order[$order_index]['goods_info'] = serialize($refund_goods);
            }
            $model_order = Model('order');
            if($model_order->addSubOrderList($sub_order,$refund_goods,$order_info)){
                showDialog('拆单成功！','index.php?act=store_deliver&op=take_apart_list','succ','',3);
            }else{
                showDialog('拆单失败','','error','',3);
            }
        }
        //提交拆单


        $order_goods = array();
        foreach ($order_info['extend_order_goods'] as $k=>$value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            $order_goods[$value['goods_id']] = $value;
        }
        $order_info['extend_order_goods'] = $order_goods;
        //如果是自提订单，只保留自提快递公司
        if (isset($order_info['extend_order_common']['reciver_info']['dlyp'])&&$order_info['extend_order_common']['reciver_info']['dlyp']>0) {
            foreach ($express_list as $k => $v) {
                if ($v['e_zt_state'] == '0') unset($express_list[$k]);
            }
            $my_express_list = array_keys($express_list);
        } else {
            //快递公司
            $my_express_list = Model()->table('store_extend')->getfby_store_id($_SESSION['store_id'],'express');
            if (!empty($my_express_list)){
                $my_express_list = explode(',',$my_express_list);
            }

        }
        Tpl::output('my_express_list',$my_express_list);
        Tpl::output('express_list',$express_list);
        Tpl::output('order_info',$order_info);
        Tpl::showpage('take_apart');
    }
    //编辑拆单
    public function take_apart_editOp(){
        if(!$this->_have_take_apart()){
            exit;
        }
        $order_id = intval($_GET['order_id']);
        if(chksubmit()){
            $order_id = intval($_POST['order_id']);
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if(!$order_info){
            showDialog('订单不存在');
        }
        $order_all_goods = array();//订单商品
        foreach ($order_info['extend_order_goods'] as $k=>$value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            $order_all_goods[$value['goods_id']] = $value;
        }
        $sub_order_list = $model_order->getSubOrderList(array('order_id'=>$order_id));
        $sub_order_normal = array();
        $sub_order_send = array();
        $sub_order_refund = array();
        $express_list  = rkcache('express',true);

        //save
        if(chksubmit()){
            $model_area = Model('area');
            $area_list = $model_area->getAreas();
            $area_list = $area_list['name'];
            $used_sub_num = array();//子单编号
            $goods_all = array();//子单可编辑商品
            foreach($sub_order_list as $order){
                if($order['sub_order_state']==1){
                    $sub_order_send[$order['sub_order_id']] = $order;
                }else if($order['sub_order_state']==2){
                    $sub_order_refund[$order['sub_order_id']] = $order;
                }else{
                    $sub_order_normal[$order['sub_order_id']] = $order;
                }
                //未发货，可编辑商品
                if($order['sub_order_state']==0){
                    $goods = unserialize($order['goods_info']);
                    foreach($goods as $k=>$v){
                        if($goods_all[$k]){
                            $goods_all[$k] +=$v;
                        }else{
                            $goods_all[$k] = $v;
                        }
                    }
                }else{//已使用的编号
                    $n = strrpos($order['sub_order_sn'],'-');
                    $sn = substr($order['sub_order_sn'],$n+1);
                    $used_sub_num[intval($sn)] = 1;
                }
            }

            $sub_order = array();//新子单
            $old_order = array();//发货修改
            $order_index = 0;
            $sn = 1;
            if(count($_POST['sub_order'])>0){
                foreach($_POST['sub_order'] as $order){
                    if(!$order){
                        $order_index++;
                        continue;
                    }
                    if(strpos($order,'=')===false){//发货的
                        if($sub_order_send[$order]){//修改
                            $old_order[$order_index]['sub_order_id'] = $sub_order_send[$order]['sub_order_id'];
                            $old_order[$order_index]['order_id'] = $order_info['order_id'];
                            $old_order[$order_index]['order_sn'] = $order_info['order_sn'];
                            $old_order[$order_index]['buyer_id'] = $order_info['buyer_id'];
                            $old_order[$order_index]['store_id'] = $order_info['store_id'];
                            $old_order[$order_index]['sub_order_sn'] = $sub_order_send[$order]['sub_order_sn'];
                            $old_order[$order_index]['other_order_sn'] =  $_POST['other_sn'][$order_index];
                            $old_order[$order_index]['seller'] =  $_POST['seller_name'][$order_index];
                            $old_order[$order_index]['remark'] = $_POST['remark'][$order_index];//备注
                            $old_order[$order_index]['addtime'] = $sub_order_send[$order]['addtime'];
                            //发货信息
                            if($_POST['express_id'][$order_index]&&$_POST['shipping_code'][$order_index]&&$express_list[$_POST['express_id'][$order_index]]){
                                $old_order[$order_index]['express_id'] =  $_POST['express_id'][$order_index];
                                $old_order[$order_index]['shipping_code'] =  $_POST['shipping_code'][$order_index];
                                $old_order[$order_index]['e_name'] =  $express_list[$_POST['express_id'][$order_index]]['e_name'];
                                $old_order[$order_index]['sub_order_state'] =  1;//发货
                                $old_order[$order_index]['send_time'] =  $sub_order_send[$order]['send_time'];
                            }else{
                                showDialog('已发货的订单物流信息不能为空！');
                            }
                            //收货人信息
                            //if($_POST['rec_name'][$order_index]&&$_POST['rec_mobile'][$order_index]&&$_POST['addr'][$order_index]){
                                $rec_array = array();
                                $area = explode(',', $_POST['area'][$order_index]);
                                if(count($area)==3){
                                    $privince = $area_list[$area[0]];
                                    $city = $area_list[$area[1]];
                                    $qu = $area_list[$area[2]];
                                    if($privince&&$city&&$qu){
                                        $rec_array['province_id'] = $area[0];
                                        $rec_array['city_id'] = $area[1];
                                        $rec_array['qx_id'] = $area[2];
                                        $rec_array['area_info'] = $privince.' '.$city.' '.$qu;
                                        //$rec_array['address'] = $_POST['addr'][$order_index];
                                        //$rec_array['postcode'] = $_POST['postcode'][$order_index];
                                        //$rec_array['reciver_name'] = $_POST['rec_name'][$order_index];
                                        //$rec_array['phone'] = $_POST['rec_mobile'][$order_index];
                                    }
                                }
                               // if(empty($rec_array)){
                               //     showDialog('已发货的订单收货信息不能为空！');
                               // }
                                $rec_array['address'] = $_POST['addr'][$order_index];
                                $rec_array['postcode'] = $_POST['postcode'][$order_index];
                                $rec_array['reciver_name'] = $_POST['rec_name'][$order_index];
                                $rec_array['phone'] = $_POST['rec_mobile'][$order_index];
                                $old_order[$order_index]['reciver_info'] = serialize($rec_array);
                            //}else{
                            //    showDialog('已发货的订单收货信息不能为空！');
                            //}
                            //买家信息
                            //if($_POST['buyer_name'][$order_index]&&$_POST['buyer_id_card'][$order_index]){
                                $old_order[$order_index]['buyer_info'] = serialize( array('buyer_name'=>$_POST['buyer_name'][$order_index],'buyer_id_card'=>$_POST['buyer_id_card'][$order_index]));
                            //}else{
                             //   showDialog('已发货的订单买家信息不能为空！');
                           // }
                            $old_order[$order_index]['goods_info'] = $sub_order_send[$order]['goods_info'];
                            $order_index++;continue;
                        }else{
                            showDialog('参数错误！');
                        }
                    }
                    //新增子单
                    $sub_order[$order_index]['order_id'] = $order_info['order_id'];
                    $sub_order[$order_index]['order_sn'] = $order_info['order_sn'];
                    $sub_order[$order_index]['buyer_id'] = $order_info['buyer_id'];
                    $sub_order[$order_index]['store_id'] = $order_info['store_id'];
                    while($used_sub_num[$sn]){
                        $sn++;
                    }
                    if($sn<10){
                        $sub_sn = '-00'.($sn);
                    }else if($sn<100){
                        $sub_sn = '-0'.($sn);
                    }else{
                        $sub_sn = '-'.($sn);
                    }
                    $sn++;
                    $sub_order[$order_index]['sub_order_sn'] = $order_info['order_sn'].$sub_sn;
                    $sub_order[$order_index]['other_order_sn'] =  $_POST['other_sn'][$order_index];
                    $sub_order[$order_index]['seller'] =  $_POST['seller_name'][$order_index];
                    $sub_order[$order_index]['remark'] = $_POST['remark'][$order_index];//备注
                    $sub_order[$order_index]['addtime'] = TIMESTAMP;
                    //是否发货
                    if($_POST['express_id'][$order_index]&&$_POST['shipping_code'][$order_index]&&$express_list[$_POST['express_id'][$order_index]]){
                        $sub_order[$order_index]['express_id'] =  $_POST['express_id'][$order_index];
                        $sub_order[$order_index]['shipping_code'] =  $_POST['shipping_code'][$order_index];
                        $sub_order[$order_index]['e_name'] =  $express_list[$_POST['express_id'][$order_index]]['e_name'];
                        $sub_order[$order_index]['sub_order_state'] =  1;//发货
                        $sub_order[$order_index]['send_time'] =  TIMESTAMP;
                    }else{
                        $sub_order[$order_index]['express_id'] =  0;
                        $sub_order[$order_index]['shipping_code'] =  '';
                        $sub_order[$order_index]['e_name'] =  '';
                        $sub_order[$order_index]['sub_order_state'] =  0;
                        $sub_order[$order_index]['send_time'] =  0;
                    }
                    //收货人信息
                    //if($_POST['rec_name'][$order_index]&&$_POST['rec_mobile'][$order_index]&&$_POST['addr'][$order_index]){
                        $rec_array = array();
                        $area = explode(',', $_POST['area'][$order_index]);//var_dump( $_POST['area'][$order_index]);
                        if(count($area)==3){
                            $privince = $area_list[$area[0]];
                            $city = $area_list[$area[1]];
                            $qu = $area_list[$area[2]];
                            if($privince&&$city&&$qu){
                                $rec_array['province_id'] = $area[0];
                                $rec_array['city_id'] = $area[1];
                                $rec_array['qx_id'] = $area[2];
                                $rec_array['area_info'] = $privince.' '.$city.' '.$qu;
                                //$rec_array['address'] = $_POST['addr'][$order_index];
                                //$rec_array['postcode'] = $_POST['postcode'][$order_index];
                                //$rec_array['reciver_name'] = $_POST['rec_name'][$order_index];
                                //$rec_array['phone'] = $_POST['rec_mobile'][$order_index];
                            }
                        }
                        //if(empty($rec_array)){
                         //   showDialog('收货信息不完整！');
                       // }
                        $rec_array['address'] = $_POST['addr'][$order_index];
                        $rec_array['postcode'] = $_POST['postcode'][$order_index];
                        $rec_array['reciver_name'] = $_POST['rec_name'][$order_index];
                        $rec_array['phone'] = $_POST['rec_mobile'][$order_index];
                        $sub_order[$order_index]['reciver_info'] = serialize($rec_array);
                    //}else{
                     //   showDialog('收货人信息不完整！');
                    //}
                    //买家信息
                    //if($_POST['buyer_name'][$order_index]&&$_POST['buyer_id_card'][$order_index]){
                        $sub_order[$order_index]['buyer_info'] = serialize( array('buyer_name'=>$_POST['buyer_name'][$order_index],'buyer_id_card'=>$_POST['buyer_id_card'][$order_index]));
                    //}else{
                     //   showDialog('买家信息不完整！');
                   // }
                    //拆单商品信息
                    $id_num = explode(',',$order);
                    if($id_num){
                        $sub_goods =array();
                        foreach($id_num as $val){
                            $arr = explode('=',$val);
                            $arr[0] = intval($arr[0]);
                            $arr[1] = intval($arr[1]);
                            if($goods_all[$arr[0]]&&$goods_all[$arr[0]]>=$arr[1]&&$arr[1]>0){
                                $goods_all[$arr[0]]-=$arr[1];
                                $sub_goods[ $arr[0]]= $arr[1];
                            }else{
                                showDialog('商品拆单有误！'.$arr[0]);
                            }
                        }
                    }
                    if(empty($sub_goods)){
                        showDialog('商品拆单有误！');
                    }
                    $sub_order[$order_index]['goods_info'] = serialize($sub_goods);
                    $order_index++;
                }
            }
            //退款
            $refund_goods =array();
            if($_POST['refund_order']){
                $refund = explode(',',$_POST['refund_order']);
                foreach($refund as $val){
                    $arr = explode('=',$val);
                    $arr[0] = intval($arr[0]);
                    $arr[1] = intval($arr[1]);
                    if($goods_all[$arr[0]]&&$goods_all[$arr[0]]>=$arr[1]&&$arr[1]>0){
                        $goods_all[$arr[0]]-=$arr[1];
                        $refund_goods[ $arr[0]]= $arr[1];
                    }else{
                        showDialog('商品拆单有误！');
                    }
                }
            }
            //拆单商品数量验证
            foreach($goods_all as $val){
                if($val!=0){
                    showDialog('拆单错误！');
                }
            }
            //退款
            $order_index++;
            if(count($refund_goods)>0){
                $sub_order[$order_index]['order_id'] = $order_info['order_id'];
                $sub_order[$order_index]['order_sn'] = $order_info['order_sn'];
                $sub_order[$order_index]['buyer_id'] = $order_info['buyer_id'];
                $sub_order[$order_index]['store_id'] = $order_info['store_id'];
                while($used_sub_num[$sn]){
                    $sn++;
                }
                if($sn<10){
                    $sub_sn = '-00'.($sn);
                }else if($sn<100){
                    $sub_sn = '-0'.($sn);
                }else{
                    $sub_sn = '-'.($sn);
                }
                $sn++;
                $sub_order[$order_index]['sub_order_sn'] = $order_info['order_sn'].$sub_sn;
                $sub_order[$order_index]['other_order_sn'] = '';
                $sub_order[$order_index]['seller'] =  '';
                $sub_order[$order_index]['remark'] = '';
                $sub_order[$order_index]['addtime'] = TIMESTAMP;
                $sub_order[$order_index]['express_id'] =  0;
                $sub_order[$order_index]['shipping_code'] =  '';
                $sub_order[$order_index]['e_name'] =  '';
                $sub_order[$order_index]['sub_order_state'] =  2;
                $sub_order[$order_index]['send_time'] =  0;

                $sub_order[$order_index]['reciver_info'] = serialize(null);
                $sub_order[$order_index]['buyer_info'] = serialize(null);;
                $sub_order[$order_index]['goods_info'] = serialize($refund_goods);
            }
            if($model_order->editSubOrderList($sub_order,$old_order,array('order_id'=>$order_info['order_id'],'sub_order_state'=>0),$refund_goods,$order_info)){
               showDialog('编辑成功','','succ','',3);
            }else{
               showDialog('编辑失败','','error','',3);
            }exit;
        }
        //--save

        //整理子单
        foreach($sub_order_list as $sub){
            $sub['goods_info'] = unserialize($sub['goods_info']);
            $sub['buyer_info'] = unserialize($sub['buyer_info']);
            $sub['reciver_info'] = unserialize($sub['reciver_info']);
            $goods_list = array();
            foreach($sub['goods_info'] as $k=>$v){
                $goods_list[$k]= $this->_clone_goods($order_all_goods[$k],$v);
            }
            $sub['goods_info']=$goods_list;
            if($sub['sub_order_state']==1){
                $sub_order_send[] = $sub;
            }else if($sub['sub_order_state']==2){
                $sub_order_refund[] = $sub;
            }else{
                $sub_order[] = $sub;
            }
        }
        //如果是自提订单，只保留自提快递公司
        if (isset($order_info['extend_order_common']['reciver_info']['dlyp'])&&$order_info['extend_order_common']['reciver_info']['dlyp']>0) {
            foreach ($express_list as $k => $v) {
                if ($v['e_zt_state'] == '0') unset($express_list[$k]);
            }
            $my_express_list = array_keys($express_list);
        } else {
            //快递公司
            $my_express_list = Model()->table('store_extend')->getfby_store_id($_SESSION['store_id'],'express');
            if (!empty($my_express_list)){
                $my_express_list = explode(',',$my_express_list);
            }
        }
        Tpl::output('my_express_list',$my_express_list);
        Tpl::output('express_list',$express_list);
        Tpl::output('sub_order',$sub_order);
        Tpl::output('sub_order_send',$sub_order_send);
        Tpl::output('sub_order_refund',$sub_order_refund);
        Tpl::output('order_info',$order_info);
        Tpl::showpage('take_apart.edit');
    }
    private function _clone_goods($goods,$num){
        $ret = array();
        $ret['goods_num'] = $num;
        $ret['goods_id'] = $goods['goods_id'];
        $ret['goods_name'] = $goods['goods_name'];
        $ret['image_240_url'] = $goods['image_240_url'];
        return $ret;
    }

    //拆单管理
    public function take_apart_listOp(){
        if(!$this->_have_take_apart()){
            exit;
        }
        $condition = array();
        if ($_GET['buyer_name'] != '') {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        if ($_GET['order_sn'] != '') {
            $condition['order_sn'] = $_GET['order_sn'];
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        $condition['take_apart'] = 1;//拆单

        $pagesize = 5;
        $model_order = Model('order');
        if(intval($_GET['sub_order_state'])>0){
            $condition['order.store_id'] =  $_SESSION['store_id'];
            $condition['sub_order_state'] = intval($_GET['sub_order_state'])-1;
            $order_ids = $model_order->table('order,sub_order')->join('inner')->on('sub_order.order_id=order.order_id')->field('distinct(order.order_id) as order_id')->where($condition)->page($pagesize)->limit('')->select();
            $count = $model_order->table('order,sub_order')->join('inner')->on('sub_order.order_id=order.order_id')->field('count(distinct(order.order_id)) as num')->where($condition)->page($pagesize)->limit('')->find();
            $count = $count['num'];
            pagecmd('settotalnum',$count);
            foreach($order_ids as $val){
                $ids[] = $val['order_id'];
            }
            $condition['order_id'] = array('in',implode(',',$ids));
            Tpl::output('show_page',$model_order->showpage());
            unset( $condition['order.store_id']);
            unset( $condition['sub_order_state']);
            $order_list = $model_order->getOrderList($condition,'','*','order_id desc','',array('order_goods','order_common','member'));
        }else{
            $condition['store_id'] = $_SESSION['store_id'];
            $order_list = $model_order->getOrderList($condition,$pagesize,'*','order_id desc','',array('order_goods','order_common','member'));
        }


        if(!intval($_GET['sub_order_state'])){Tpl::output('show_page',$model_order->showpage());}
        $order_ids = array();
        $buyer_ids = array();
        foreach($order_list as $k=> $order){
            $buyer_ids[] = $order['buyer_id'];
            $order_ids[]=$order['order_id'];
            $order_goods = array();
            foreach($order['extend_order_goods'] as $value){
                $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
                $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
                $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
                $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
                if ($value['goods_type'] == 5) {
                    $order_list[$k]['zengpin_list'][] = $value;//赠品
                } else {
                    $order_goods[$value['goods_id']] = $value;
                }
            }
            $order_list[$k]['extend_order_goods'] = $order_goods;
        }
        //买家店铺
        $model_member = Model('member');
        $list = $model_member->getMemberJoinOrgList(array('member_id'=>array('in',implode(',',$buyer_ids))),'member_id,org_name,member_mobile');
        $store_list = array();
        foreach($list as $val){
            $store_list[$val['member_id']] = $val;
        }
        //订单变更日志
        $log_list	= $model_order->getOrderLogList(array('order_id'=>array('in',implode(',',$order_ids))));
        $order_log = array();
        foreach($log_list as $val){
            $order_log[$val['order_id']][] = $val;
        }

        $sub_order_list = array();
        if(count($order_ids)>0){
            $condition = array();
            $condition['order_id']=array('in',implode(',',$order_ids));
            //if(intval($_GET['sub_order_state'])>0){
            //    $condition['sub_order_state'] = intval($_GET['sub_order_state']) -1;
            //}
            $list = $model_order->getSubOrderList($condition);
            foreach($list as $order){
                $order['goods_info'] = unserialize($order['goods_info']);
                $order['buyer_info'] = unserialize($order['buyer_info']);
                $order['reciver_info'] = unserialize($order['reciver_info']);
                $sub_order_list[$order['order_id']][] = $order;
            }
        }
        self::profile_menu('deliver','take_apart');
        Tpl::output('sub_order_list',$sub_order_list);
        Tpl::output('order_list',$order_list);
        Tpl::output('store_list',$store_list);
        Tpl::output('order_log',$order_log);

        Tpl::showpage('take_apart.list');
    }
    //子单退款
    public function sub_order_refundOp(){
        if(!$this->_have_take_apart()){
            exit;
        }
        $order_id = intval($_GET['order_id']);
        $sub_id = intval($_GET['sub_id']);
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['take_apart'] = 1;//拆单
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if(empty($order_info)){
            showMessage('订单不存在！');
        }
        $condition = array();
        $condition['sub_order_id'] =$sub_id;
        $condition['sub_order_state'] = 0;
        $sub_order = $model_order->getSubOrderInfo($condition);
        $goods_info = unserialize($sub_order['goods_info']);
        if($goods_info){
            $model_refund=Model('refund_return');
            if($model_refund->addRefundGoodsList($goods_info,$order_info)){
                $model_order->editSubOrder(array('sub_order_id'=>$sub_id,'sub_order_state'=>2));
                showMessage('退款成功，等待后台确认。');
            }
        }
        showMessage('退款失败');
    }
    //子单编辑发货
    public function sub_order_editOp(){
        if(!$this->_have_take_apart()){
            exit;
        }
        $order_id = intval($_GET['order_id']);
        $sub_id = intval($_GET['sub_id']);
        if(chksubmit()){
            $order_id = intval($_POST['order_id']);
            $sub_id = intval($_POST['sub_id']);
        }
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['take_apart'] = 1;//拆单
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if(empty($order_info)){
            showMessage('订单不存在！');
        }
        $sub_condition = array('sub_order_id'=>$sub_id,'order_id'=>$order_id,'sub_order_state'=>array('neq',2));
        if(chksubmit()){
            $express_list  = rkcache('express',true);
            $model_area = Model('area');
            $area_list = $model_area->getAreas();
            $area_list = $area_list['name'];
            $data = array();
            $sub_order = $model_order->getSubOrderInfo($sub_condition);
            if(empty($sub_order)){
                showMessage('子订单不存在！');
            }
            if($sub_order['express_id']>0){
                if(!$_POST['shipping_code']||!$_POST['express_id']){
                    showMessage('已发货的订单物流不能为空！');
                }
            }
            if($_POST['shipping_code']&&$_POST['express_id']){
                $data['sub_order_state'] = 1;
                $data['shipping_code'] = $_POST['shipping_code'];
                $data['express_id'] = $_POST['express_id'];
                $data['e_name'] = $express_list[$_POST['express_id']]['e_name'];
                if($sub_order['express_id']==0)
                $data['send_time'] = TIMESTAMP;
            }

            if($_POST['seller_name']){
                $data['seller'] = $_POST['seller_name'];
            }
            if($_POST['other_sn']){
                $data['other_order_sn'] = $_POST['other_sn'];
            }
            if($_POST['remark']){
                $data['remark'] = $_POST['remark'];
            }
            $buyer_info['buyer_name'] = $_POST['buyer_name'];
            $buyer_info['buyer_id_card'] = $_POST['buyer_id_card'];

            $area = explode(',',$_POST['area']);
            $rec_array['province_id'] = $area[0];
            $rec_array['city_id'] = $area[1];
            $rec_array['qx_id'] = $area[2];
            $privince = $area_list[$area[0]];
            $city = $area_list[$area[1]];
            $qu = $area_list[$area[2]];
            $rec_array['area_info'] = $privince.' '.$city.' '.$qu;
            $rec_array['address'] = $_POST['addr'];
            $rec_array['postcode'] = $_POST['postcode'];
            $rec_array['reciver_name'] = $_POST['rec_name'];
            $rec_array['phone'] = $_POST['rec_mobile'];

            $data['buyer_info'] = serialize($buyer_info);
            $data['reciver_info'] = serialize($rec_array);
            $data['sub_order_id'] = $sub_id;
            $data['order_id'] = $order_id;
            if($model_order->editSubOrder($data)){
                showDialog('修改成功！',htmlspecialchars_decode($_POST['ref']),'succ','',3);
            }else{
                showDialog('修改失败');
            }
        }

        $order_goods = array();
        foreach ($order_info['extend_order_goods'] as $k=>$value) {
            $value['image_60_url'] = cthumb($value['goods_image'], 60, $value['store_id']);
            $value['image_240_url'] = cthumb($value['goods_image'], 240, $value['store_id']);
            $value['goods_type_cn'] = orderGoodsType($value['goods_type']);
            $value['goods_url'] = urlShop('goods','index',array('goods_id'=>$value['goods_id']));
            $order_goods[$value['goods_id']] = $value;
        }
        $sub_order = $model_order->getSubOrderInfo($sub_condition);
        $sub_order['goods_info'] = unserialize($sub_order['goods_info']);
        $sub_order['buyer_info'] = unserialize($sub_order['buyer_info']);
        $sub_order['reciver_info'] = unserialize($sub_order['reciver_info']);
        $express_list  = rkcache('express',true);
        //如果是自提订单，只保留自提快递公司
        if (isset($order_info['extend_order_common']['reciver_info']['dlyp'])&&$order_info['extend_order_common']['reciver_info']['dlyp']>0) {
            foreach ($express_list as $k => $v) {
                if ($v['e_zt_state'] == '0') unset($express_list[$k]);
            }
            $my_express_list = array_keys($express_list);
        } else {
            //快递公司
            $my_express_list = Model()->table('store_extend')->getfby_store_id($_SESSION['store_id'],'express');
            if (!empty($my_express_list)){
                $my_express_list = explode(',',$my_express_list);
            }
        }
        Tpl::output('my_express_list',$my_express_list);
        Tpl::output('express_list',$express_list);
        Tpl::output('order_info',$order_info);
        Tpl::output('sub_order',$sub_order);
        Tpl::output('order_goods',$order_goods);
        Tpl::output('ref',getReferer());
        Tpl::showpage('take_apart.subedit');
    }
    /**
     * 子单物流跟踪
     */
    public function sub_search_deliverOp(){
        if(!$this->_have_take_apart()){
            exit;
        }
        Language::read('member_member_index');
        $lang	= Language::getLangContent();

        $order_id = intval($_GET['order_id']);
        $sub_id = intval($_GET['sub_id']);
        $model_order = Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['take_apart'] = 1;//拆单
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));

        if (empty($order_info)) {
            showMessage('未找到信息','','html','error');
        }
        $sub_condition = array('sub_order_id'=>$sub_id,'order_id'=>$order_id,'sub_order_state'=>array('neq',2));
        $sub_order = $model_order->getSubOrderInfo($sub_condition);
        $sub_order['goods_info'] = unserialize($sub_order['goods_info']);
        $sub_order['buyer_info'] = unserialize($sub_order['buyer_info']);
        $sub_order['reciver_info'] = unserialize($sub_order['reciver_info']);
        //替换商品
        $order_goods = array();
        foreach($order_info['extend_order_goods'] as $val){
            $order_goods[$val['goods_id']]= $val;
        }
        $sub_order_goods = array();
        foreach($sub_order['goods_info'] as $k=>$v){
            $goods = $order_goods[$k];
            $goods['goods_num'] = $v;
            $sub_order_goods[] = $goods;
        }
        $order_info['extend_order_goods']  = $sub_order_goods;
        $order_info['state_info'] = orderState($order_info);
        $order_info['shipping_code'] = $sub_order['shipping_code'];
        Tpl::output('order_info',$order_info);
        Tpl::output('sub_order',$sub_order);

        //取得配送公司代码
        $express = rkcache('express',true);
        Tpl::output('e_code',$express[$sub_order['express_id']]['e_code']);
        Tpl::output('e_name',$express[$sub_order['express_id']]['e_name']);
        Tpl::output('shipping_code',$sub_order['shipping_code']);
        Tpl::output('is_sub_order',true);
        self::profile_menu('search','search');
        Tpl::showpage('store_deliver.detail');
    }

    public function edit_sub_remarkOp(){
        $sub_id = intval($_POST['sub_id']);
        $remark = $_POST['remark'];
        $model_order = Model('order');
        $data = array();
        $data['remark'] = $remark;
        echo  $model_order->editSubOrderInfo($data,array('sub_order_id'=>$sub_id,'store_id'=>$_SESSION['store_id']));
    }
    /**
     * 导出
     *
     */
    public function export_step1Op(){
        if(!$this->_have_take_apart()){
            exit;
        }
        $lang	= Language::getLangContent();
        $condition = array();
        if ($_GET['buyer_name'] != '') {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        if ($_GET['order_sn'] != '') {
            $condition['order_sn'] = $_GET['order_sn'];
        }
        $if_start_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_date']);
        $if_end_date = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_date']);
        $start_unixtime = $if_start_date ? strtotime($_GET['query_start_date']) : null;
        $end_unixtime = $if_end_date ? strtotime($_GET['query_end_date']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        $condition['store_id'] = $_SESSION['store_id'];
        $condition['take_apart'] = 1;//拆单


        $model_order = Model('order');
        if (!is_numeric($_GET['curpage'])){
            $count = $model_order->getOrderCount($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){	//显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=order&op=index');
                Tpl::showpage('export.excel');
            }else{	//如果数量小，直接下载
                $order_list = $model_order->getOrderList($condition,self::EXPORT_SIZE, '*','add_time desc','',array('order_goods','member'));
                $this->createExcel($order_list);
            }
        }else{	//下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $order_list = $model_order->getOrderList($condition,self::EXPORT_SIZE, '*',  'add_time desc',"{$limit1},{$limit2}",array('order_goods','member'));
            $this->createExcel($order_list);
        }
    }
    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        $model_area = Model('area');
        $area_list = $model_area->getAreas();
        $area_list = $area_list['name'];

        $buyer_ids = array();
        $order_ids = array();
        $all_goods = array();
        foreach($data as $val){
            $buyer_ids[] = $val['buyer_id'];
            $order_ids[] = $val['order_id'];
            foreach($val['extend_order_goods'] as $goods){
                $all_goods[$goods['goods_id']] = $goods;
            }
        }
        $model_order = Model('order');
        $list = $model_order->getSubOrderList(array('order_id'=>array('in',implode(',',$order_ids))));
        $sub_order_list = array();

        foreach($list as $k=> $order){
            $order['goods_info'] = unserialize($order['goods_info']);
            $order['buyer_info'] = unserialize($order['buyer_info']);
            $order['reciver_info'] = unserialize($order['reciver_info']);
            $sub_order_list[$order['order_id']][] = $order;
        }
        //买家店铺
        $model_member = Model('member');
        $list = $model_member->getMemberJoinOrgList(array('member_id'=>array('in',implode(',',$buyer_ids))),'member_id,org_name');
        $store_list = array();
        foreach($list as $val){
            $store_list[$val['member_id']] = $val;
        }



        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        //会员帐号、真实名称、注册手机号、注册时间、邮箱、可用余额、冻结余额、认证状态、店铺名称、门店数、法人、联系人姓名、联系人电话、联系人QQ、认证申请时间、认证时间、登录次数、最后登录时间
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'订单编号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'子单编号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'子单状态');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'外部订单编号');

        $excel_data[0][] = array('styleid'=>'s_title','data'=>'供应商');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品单价');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品数量');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'物流公司');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'物流单号');

        $excel_data[0][] = array('styleid'=>'s_title','data'=>'买家店铺');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'买家姓名');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'买家账号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'身份证姓名');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'身份证号');

        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收货人手机');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'省');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'市');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'区/县');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'详细地址');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'邮编');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'备注');
        //data
        foreach ((array)$data as $k=>$order){
            foreach($sub_order_list[$order['order_id']] as $sub_order){
                foreach($sub_order['goods_info'] as $goods_id=>$goods_num){
                    $goods = $all_goods[$goods_id];
                    $buyer_store = $store_list[$order['buyer_id']];
                    $tmp = array();
                    $tmp[] = array('data'=>$order['order_sn']);
                    $tmp[] = array('data'=>$sub_order['sub_order_sn']);
                    $tmp[] = array('data'=>$this->_getSubOrderState($sub_order['sub_order_state']));
                    $tmp[] = array('data'=>$sub_order['other_order_sn']);
                    $tmp[] = array('data'=>$sub_order['seller']);
                    $tmp[] = array('data'=>$goods['goods_name']);
                    $tmp[] = array('data'=>$goods['goods_price']);
                    $tmp[] = array('data'=>$goods_num);
                    $tmp[] = array('data'=>$sub_order['e_name']);
                    $tmp[] = array('data'=>$sub_order['shipping_code']);

                    $tmp[] = array('data'=>$buyer_store['org_name']);
                    $tmp[] = array('data'=>$order['buyer_name']);
                    $tmp[] = array('data'=>$order['buyer_name']);
                    $tmp[] = array('data'=>$sub_order['buyer_info']['buyer_name']);
                    $tmp[] = array('data'=>$sub_order['buyer_info']['buyer_id_card']);

                    $tmp[] = array('data'=>$sub_order['reciver_info']['reciver_name']);
                    $tmp[] = array('data'=>$sub_order['reciver_info']['phone']);
                    $tmp[] = array('data'=>$area_list[$sub_order['reciver_info']['province_id']]);
                    $tmp[] = array('data'=>$area_list[$sub_order['reciver_info']['city_id']]);
                    $tmp[] = array('data'=>$area_list[$sub_order['reciver_info']['qx_id']]);
                    $tmp[] = array('data'=>$sub_order['reciver_info']['address']);
                    $tmp[] = array('data'=>$sub_order['reciver_info']['postcode']);

                    $tmp[] = array('data'=>$sub_order['remark']);
                    $excel_data[] = $tmp;
                }
            }
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('订单',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('订单',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));

    }
    private function _getSubOrderState($state){
        if($state==1){
            return '已发货';
        }else if($state==2){
            return '已退款';
        }else {
            return '未发货';
        }
    }

    private function _have_take_apart(){
        return $_SESSION['store_name']=='直邮宝';
    }
}
