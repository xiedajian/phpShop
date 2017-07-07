<?php
/**
 * 交易管理
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');
class orderControl extends SystemControl{
    /**
     * 每次导出订单数量
     * @var int
     */
	const EXPORT_SIZE = 1000;

	public function __construct(){
		parent::__construct();
		Language::read('trade');
	}

	public function indexOp(){
	    $model_order = Model('order');
        $condition	= array();
        if($_GET['order_sn']) {
        	$condition['order_sn'] = $_GET['order_sn'];
        }
        if($_GET['store_name']) {
            $condition['store_name'] = array("like",'%'.$_GET['store_name'].'%');
        }
        if(in_array($_GET['order_state'],array('0','10','20','30','40'))){
        	$condition['order_state'] = $_GET['order_state'];
        }else{
             $condition['order_state']=array("in",array(10,20,30,40));
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        if($_GET['member_name']) {
        	$condition['member_name'] = array("like",'%'.$_GET['member_name'].'%');
        }
        if ($_GET['search_field_value'] != '') {
        	switch ($_GET['search_field_name']){
        		case 'member_org_name':
        			$condition['member_org_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
        			break;
        		case 'member_truename':
        			$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
        			break;
        			//zmr>v30
        		case 'member_mobile':
        			$condition['member_mobile'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
        			break;
        		
        	}
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        //付款时间
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['payment_time_start']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['payment_time_end']);
        $start_unixtime = $if_start_time ? strtotime($_GET['payment_time_start']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['payment_time_end']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['payment_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        
        $order_list	= $model_order->getOrderListm($condition,30,"*","order_id desc","");
        $member_ids=array();
        foreach ($order_list as $order_id => $order_info) {
            //显示取消订单
            $order_list[$order_id]['if_cancel'] = $model_order->getOrderOperateState('system_cancel',$order_info);
            //显示收到货款
            $order_list[$order_id]['if_system_receive_pay'] = $model_order->getOrderOperateState('system_receive_pay',$order_info);
            $member_ids[]=$order_info["buyer_id"];
        }
        $member_array=Model("member")->getMemberList(array("member_id"=>array("in",$member_ids)),"member_id,member_truename,member_mobile");
        $member_array_key_value=array();
        foreach ($member_array as $member){
        	$member_array_key_value[$member["member_id"]]=$member;
        }
        foreach ($order_list as $order_id => $order_info) {
        	$order_list[$order_id]["member_truename"]=$member_array_key_value[$order_info["buyer_id"]]["member_truename"];
        	$order_list[$order_id]["member_mobile"]=$member_array_key_value[$order_info["buyer_id"]]["member_mobile"];
        }
        //显示支付接口列表(搜索)
        $payment_list = Model('payment')->getPaymentOpenList();
        Tpl::output('payment_list',$payment_list);
        Tpl::output('search_field_name',trim($_GET['search_field_name']));
        Tpl::output('search_field_value',trim($_GET['search_field_value']));
        Tpl::output('order_list',$order_list);
        Tpl::output('show_page',$model_order->showpage());
        Tpl::showpage('order.index');
	}

	/**
	 * 平台订单状态操作
	 *
	 */
	public function change_stateOp() {
        $order_id = intval($_GET['order_id']);
        if($order_id <= 0){
            showMessage(L('miss_order_number'),$_POST['ref_url'],'html','error');
        }
        $model_order = Model('order');

        //获取订单详细
        $condition = array();
        $condition['order_id'] = $order_id;
        $order_info	= $model_order->getOrderInfo($condition);

        if ($_GET['state_type'] == 'cancel') {
            $result = $this->_order_cancel($order_info);
        } elseif ($_GET['state_type'] == 'receive_pay') {
            $result = $this->_order_receive_pay($order_info,$_POST);
        }
        if (!$result['state']) {
            showMessage($result['msg'],$_POST['ref_url'],'html','error');
        } else {
            showMessage($result['msg'],$_POST['ref_url']);
        }
	}

	/**
	 * 系统取消订单
	 */
	private function _order_cancel($order_info) {
	    $order_id = $order_info['order_id'];
	    $model_order = Model('order');
	    $logic_order = Logic('order');
	    $if_allow = $model_order->getOrderOperateState('system_cancel',$order_info);
	    if (!$if_allow) {
	        return callback(false,'无权操作');
	    }
	    $result =  $logic_order->changeOrderStateCancel($order_info,'system', $this->admin_info['name']);
        if ($result['state']) {
            $this->log(L('order_log_cancel').','.L('order_number').':'.$order_info['order_sn'],1);
        }
        return $result;
	}

	/**
	 * 系统收到货款
	 * @throws Exception
	 */
	private function _order_receive_pay($order_info, $post) {
	    $order_id = $order_info['order_id'];
	    $model_order = Model('order');
	    $logic_order = Logic('order');
	    $if_allow = $model_order->getOrderOperateState('system_receive_pay',$order_info);
	    if (!$if_allow) {
	        return callback(false,'无权操作');
	    }

	    if (!chksubmit()) {
	        Tpl::output('order_info',$order_info);
	        //显示支付接口列表
	        $payment_list = Model('payment')->getPaymentOpenList();
	        //去掉预存款和货到付款
	        foreach ($payment_list as $key => $value){
	            if ($value['payment_code'] == 'predeposit' || $value['payment_code'] == 'offline') {
	               unset($payment_list[$key]);
	            }
	        }
	        Tpl::output('payment_list',$payment_list);
	        Tpl::showpage('order.receive_pay');
	        exit();
	    }
	    Log::record($_POST["payment_time"]);
	    
	    $order_list	= $model_order->getOrderList(array('pay_sn'=>$order_info['pay_sn'],'order_state'=>ORDER_STATE_NEW));
	    $result = $logic_order->changeOrderReceivePay($order_list,'system',$this->admin_info['name'],$post);
        if ($result['state']) {
            $this->log('将订单改为已收款状态,'.L('order_number').':'.$order_info['order_sn'],1);
        }
	    return $result;
	}

	/**
	 * 查看订单
	 *
	 */
	public function show_orderOp(){
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
        $goods_group = array();
        foreach($order_info['extend_order_goods'] as $goods){
            $goods_group[$common_goods[$goods['goods_id']]][] = $goods;
            $goods_group[$common_goods[$goods['goods_id']]]['total_price'] += $goods['goods_pay_price'];
            $goods_group[$common_goods[$goods['goods_id']]]['total_num'] += $goods['goods_num'];

        }
        Tpl::output('goods_group',$goods_group);

        //订单变更日志
		$log_list	= $model_order->getOrderLogList(array('order_id'=>$order_info['order_id']));
		Tpl::output('order_log',$log_list);

		//退款退货信息
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['order_id'] = $order_info['order_id'];
        $condition['seller_state'] = 2;
        $condition['admin_time'] = array('gt',0);
        $return_list = $model_refund->getReturnList($condition);
        Tpl::output('return_list',$return_list);

        //退款信息
        $refund_list = $model_refund->getRefundList($condition);
        Tpl::output('refund_list',$refund_list);

		//卖家发货信息
		if (!empty($order_info['extend_order_common']['daddress_id'])) {
		    $daddress_info = Model('daddress')->getAddressInfo(array('address_id'=>$order_info['extend_order_common']['daddress_id']));
		    Tpl::output('daddress_info',$daddress_info);
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
				Tpl::output('order_total_rebates',$order_total_rebates);
				Tpl::output('goods_2_rebates',$goods_2_rebates);
			}
		}
		
		Tpl::output('order_info',$order_info);
        Tpl::showpage('order.view');
	}

	/**
	 * 导出
	 *
	 */
	public function export_step1Op(){
		$lang	= Language::getLangContent();

	    $model_order = Model('order');
        $condition	= array();
        if($_GET['order_sn']) {
        	$condition['order_sn'] = $_GET['order_sn'];
        }
        if($_GET['store_name']) {
            $condition['store_name'] = $_GET['store_name'];
        }
        if(in_array($_GET['order_state'],array('0','10','20','30','40'))){
        	$condition['order_state'] = $_GET['order_state'];
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        //付款时间
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['payment_time_start']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['payment_time_end']);
        $start_unixtime = $if_start_time ? strtotime($_GET['payment_time_start']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['payment_time_end']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['payment_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

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
				$data = $model_order->getOrderList($condition,'','*','order_id desc',self::EXPORT_SIZE);
				$this->createExcel($data);
			}
		}else{	//下载
			$limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
			$limit2 = self::EXPORT_SIZE;
			$data = $model_order->getOrderList($condition,'','*','order_id desc',"{$limit1},{$limit2}");
			$this->createExcel($data);
		}
	}

	/**
	 * 生成excel
	 *
	 * @param array $data
	 */
	private function createExcel($data = array()){
		Language::read('export');
		import('libraries.excel');
		$excel_obj = new Excel();
		$excel_data = array();
		//设置样式
		$excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
		//header
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_no'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_store'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyer'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_xtimd'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_count'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_yfei'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_paytype'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_state'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_storeid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyerid'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_bemail'));
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'余额支付金额');
		$excel_data[0][] = array('styleid'=>'s_title','data'=>'充值卡支付金额');
		//data
		foreach ((array)$data as $k=>$v){
			$tmp = array();
			$tmp[] = array('data'=>'NC'.$v['order_sn']);
			$tmp[] = array('data'=>$v['store_name']);
			$tmp[] = array('data'=>$v['buyer_name']);
			$tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['order_amount']));
			$tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['shipping_fee']));
			$tmp[] = array('data'=>orderPaymentName($v['payment_code']));
			$tmp[] = array('data'=>orderState($v));
			$tmp[] = array('data'=>$v['store_id']);
			$tmp[] = array('data'=>$v['buyer_id']);
			$tmp[] = array('data'=>$v['buyer_email']);
			$tmp[] = array('data'=>$v['pd_amount']);
			$tmp[] = array('data'=>$v['rcb_amount']);
			$excel_data[] = $tmp;
		}
		$excel_data = $excel_obj->charset($excel_data,CHARSET);
		$excel_obj->addArray($excel_data);
		$excel_obj->addWorksheet($excel_obj->charset(L('exp_od_order'),CHARSET));
		$excel_obj->generateXML($excel_obj->charset(L('exp_od_order'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
	}

    //添加备注
    public function edit_remarkOp(){
        if (chksubmit()) {
            $order_id = intval($_POST['order_id']);
            $remark = $_POST['remark'];
            $model_order = Model('order');
            if($model_order->editOrder(array('remark'=>$remark),array('order_id'=>$order_id))){
                $order_info =$model_order->getOrderInfo(array('order_id'=>$order_id));
                $log = array();
                $log['order_id'] =$order_id;
                $log['log_msg'] ='备注：'.$remark;
                $log['log_role'] ='admin';
                $log['log_user'] =$this->admin_info['name'];
                $log['log_orderstate'] =$order_info['order_state'];
                $model_order->addOrderLog($log);
            }
            if($_POST['ajax']){
                $log['log_role'] = str_replace(array('buyer','seller','system','admin'),array('买家','商家','系统','管理员'), $log['log_role']);
                echo $log['log_role'].' '.$log['log_user'].'  于  '.date('Y-m-d H:i:s',TIMESTAMP).'  '.$log['log_msg'] ;exit;
            }
            showDialog(L('nc_common_op_succ'), 'reload', 'succ');
        }else{
            Tpl::showpage('order.remark', 'null_layout');
        }
    }

    /**导出订单包括商品**/
    public function export_detail_step1Op(){
        $lang	= Language::getLangContent();

        $model_order = Model('order');
        $condition	= array();
        if($_GET['order_sn']) {
            $condition['order_sn'] = $_GET['order_sn'];
        }
        if($_GET['store_name']) {
            $condition['store_name'] = $_GET['store_name'];
        }
        if(in_array($_GET['order_state'],array('0','10','20','30','40'))){
            $condition['order_state'] = $_GET['order_state'];
        }else{
            $condition['order_state']=array("in",array(10,20,30,40));
        }
        if($_GET['payment_code']) {
            $condition['payment_code'] = $_GET['payment_code'];
        }
        if($_GET['buyer_name']) {
            $condition['buyer_name'] = $_GET['buyer_name'];
        }
        if ($_GET['search_field_value'] != '') {
            switch ($_GET['search_field_name']){
                case 'member_org_name':
                    $condition['member_org_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
                    break;
                case 'member_truename':
                    $condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
                    break;
                //zmr>v30
                case 'member_mobile':
                    $condition['member_mobile'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
                    break;

            }
        }
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_start_time']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['query_end_time']);
        $start_unixtime = $if_start_time ? strtotime($_GET['query_start_time']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['query_end_time']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['add_time'] = array('time',array($start_unixtime,$end_unixtime));
        }
        //付款时间
        $if_start_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['payment_time_start']);
        $if_end_time = preg_match('/^20\d{2}-\d{2}-\d{2}$/',$_GET['payment_time_end']);
        $start_unixtime = $if_start_time ? strtotime($_GET['payment_time_start']) : null;
        $end_unixtime = $if_end_time ? strtotime($_GET['payment_time_end']): null;
        if ($start_unixtime || $end_unixtime) {
            $condition['payment_time'] = array('time',array($start_unixtime,$end_unixtime));
        }

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
                $data = $model_order->getOrderListm($condition,'','*','order_id desc',self::EXPORT_SIZE,array('order_common'));
                $this->createDetailExcel($data);
            }
        }else{	//下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $data = $model_order->getOrderListm($condition,'','*','order_id desc',"{$limit1},{$limit2}",array('order_common'));
            $this->createDetailExcel($data);
        }
    }
    /**生成execl**/
    private function createDetailExcel($data = array()){//var_dump($data[2078]['extend_order_common']);exit;

        $model_area = Model('area');
        $area_list = $model_area->getAreas();
        $area_list = $area_list['name'];
        //商品分类
        $model_goods_class = Model('goods_class');
        $gc_list = $model_goods_class->getGoodsClassListAll();
        foreach($gc_list as $val){
            $goods_class_list[$val['gc_id']] = $val['gc_name'];
        }

        $order_ids = array();
        $buyer_ids = array();
        foreach($data as $val){
            $order_ids[] = $val['order_id'];
            $buyer_ids[] = $val['buyer_id'];
        }
        //订单商品信息
        $model_order = Model('order');
        $list = $model_order->getOrderGoodsList(array('order_id'=>array('in',$order_ids)),'*','1000000');
        $goods_list = array();
        //区分商品规格，读取主商品名
        $common_goods_ids = array();
        foreach($list as $val){
            $goods_list[$val['order_id']][] = $val;
            $common_goods_ids[] = $val['goods_id'];
        }
        $goods_common_list = Model('goods')->getGoodsCommonName($common_goods_ids);


        $goods_pay_type_list = array(
            0=>'正常价',
            1=>'促销价',
            2=>'VIP价',
            3=>'样品价'
        );

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
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_no'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'供应商');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyer'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'买家店铺');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_xtimd'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_count'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_yfei'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_paytype'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_state'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_storeid'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_buyerid'));
        $excel_data[0][] = array('styleid'=>'s_title','data'=>L('exp_od_bemail'));

        $excel_data[0][] = array('styleid'=>'s_title','data'=>'收件人');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'联系电话');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'省');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'市');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'地区');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'详细地址');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'买家留言');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'备注');

        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品规格');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品id');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品价格');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品数量');

        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品实际成交价');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品实际成交价类型');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'付款时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'佣金比例');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'商品分类');


        //data
        foreach ((array)$data as $k=>$v){
            if($goods_list[$v['order_id']]){
                foreach($goods_list[$v['order_id']] as $index =>$goods_info){
                    $tmp = array();
                    $tmp[] = array('data'=>'NC'.$v['order_sn']);
                    $tmp[] = array('data'=>$v['store_name']);
                    $tmp[] = array('data'=>$v['buyer_name']);
                    $tmp[] = array('data'=>$store_list[$v['buyer_id']]['org_name']);
                    $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
                    $tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['order_amount']));
                    $tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['shipping_fee']));
                    $tmp[] = array('data'=>orderPaymentName($v['payment_code']));
                    $tmp[] = array('data'=>orderState($v));
                    $tmp[] = array('data'=>$v['store_id']);
                    $tmp[] = array('data'=>$v['buyer_id']);
                    $tmp[] = array('data'=>$v['buyer_email']);

                    $tmp[] = array('data'=>$v['extend_order_common']['reciver_name']);
                    $tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['phone']?$v['extend_order_common']['reciver_info']['phone']:$v['extend_order_common']['reciver_info']['mob_phone']);
                    $tmp[] = array('data'=>$area_list[$v['extend_order_common']['reciver_province_id']]);
                    $tmp[] = array('data'=>$area_list[$v['extend_order_common']['reciver_city_id']]);
                    $tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['area']);
                    $tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['street']);
                    $tmp[] = array('data'=>$v['extend_order_common']['order_message']?$v['extend_order_common']['order_message']:'');
                    $tmp[] = array('data'=>$v['remark']?$v['remark']:'');

                    //$tmp[] = array('data'=>$goods_info['goods_name']);
                    $tmp[] = array('data'=>$goods_common_list[$goods_info['goods_id']]);
                    $tmp[] = array('data'=>str_replace($goods_common_list[$goods_info['goods_id']],'',$goods_info['goods_name']));

                    $tmp[] = array('data'=>$goods_info['goods_id']);
                    $tmp[] = array('data'=>$goods_info['goods_price']);
                    $tmp[] = array('data'=>$goods_info['goods_num']);
                    $tmp[] = array('data'=>$goods_info['goods_pay_price']);
                    $tmp[] = array('data'=>$goods_pay_type_list[$goods_info['goods_pay_type']]);
                    $tmp[] = array('data'=>$v['payment_time']>0?date('Y-m-d H:i:s',$v['payment_time']):'未付款');
                    $tmp[] = array('data'=>$goods_info['commis_rate']);
                    $tmp[] = array('data'=>$goods_class_list[$goods_info['gc_id']]);
                    $excel_data[] = $tmp;
                }
            }else{
                $tmp = array();
                $tmp[] = array('data'=>'NC'.$v['order_sn']);
                $tmp[] = array('data'=>$v['store_name']);
                $tmp[] = array('data'=>$v['buyer_name']);
                $tmp[] = array('data'=>$store_list[$v['buyer_id']]['org_name']);
                $tmp[] = array('data'=>date('Y-m-d H:i:s',$v['add_time']));
                $tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['order_amount']));
                $tmp[] = array('format'=>'Number','data'=>ncPriceFormat($v['shipping_fee']));
                $tmp[] = array('data'=>orderPaymentName($v['payment_code']));
                $tmp[] = array('data'=>orderState($v));
                $tmp[] = array('data'=>$v['store_id']);
                $tmp[] = array('data'=>$v['buyer_id']);
                $tmp[] = array('data'=>$v['buyer_email']);

                $tmp[] = array('data'=>$v['extend_order_common']['reciver_name']);
                $tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['phone']?$v['extend_order_common']['reciver_info']['phone']:$v['extend_order_common']['reciver_info']['mob_phone']);
                $tmp[] = array('data'=>$v['extend_order_common']['reciver_info']['address']);
                $tmp[] = array('data'=>$v['extend_order_common']['order_message']?$v['extend_order_common']['order_message']:'');
                $tmp[] = array('data'=>$v['remark']?$v['remark']:'');

                $tmp[] = array('data'=>'');
                $tmp[] = array('data'=>'');
                $tmp[] = array('data'=>'');
                $tmp[] = array('data'=>'');
                $tmp[] = array('data'=>'');
                $tmp[] = array('data'=>'');
                $tmp[] = array('data'=>'');
                $tmp[] = array('data'=>$v['payment_time']>0?date('Y-m-d H:i:s',$v['payment_time']):'未付款');
                $tmp[] = array('data'=>'');
                $tmp[] = array('data'=>'');
                $excel_data[] = $tmp;
            }

        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset(L('exp_od_order'),CHARSET));
        $excel_obj->generateXML($excel_obj->charset(L('exp_od_order'),CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));
    }


    public function delivery_viewOp(){
        $express_id = intval($_GET['express_id']);
        if($express_id){
            $express_list = rkcache('express',true);
            $e_code = $express_list[$express_id]['e_code'];
        }
        $shipping_code = $_GET['shipping_code'];
        $url = 'http://www.kuaidi100.com/query?type='.$e_code.'&postid='.$shipping_code.'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
        $content = file_get_contents($url);
        $content = json_decode($content,true);
        if (strtoupper(CHARSET) == 'GBK'){
            $output = Language::getUTF8($content);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }else{
            $output = $content;
        }
        Tpl::output('info',$output);
        Tpl::showpage('delivery','null_layout');
    }
}
