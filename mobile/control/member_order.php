<?php
/**
 * 我的订单
 *
 *
 *
 *

 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class member_orderControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 取消订单
     */
    public function order_cancelOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = $_POST['order_id'];
        $reason = $_POST['reason'];
        if (!$reason) {
            output_error('请选择取消原因');
        }
        $condition = array();
        $condition['order_id'] = array('in',$order_id);
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_list	= $model_order->getNormalOrderList($condition);
        foreach($order_list as $order_info){
            $if_allow = $model_order->getOrderOperateState('buyer_cancel',$order_info);
            if($if_allow){
                $result = $logic_order->changeOrderStateCancel($order_info,'buyer', $this->member_info['member_name'], $reason);
            }
        }
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 订单确认收货
     */
    public function order_receiveOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = intval($_POST['order_id']);

        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info	= $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('receive',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }

        $result = $logic_order->changeOrderStateReceive($order_info,'buyer', $this->member_info['member_name']);
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data('1');
        }
    }

    /**
     * 物流跟踪
     */
    public function search_deliverOp(){
        $order_id	= intval($_POST['order_id']);
        if ($order_id <= 0) {
            output_error('订单不存在');
        }
        $model_order	= Model('order');
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_common','order_goods'));
        if (empty($order_info) || !in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS))) {
            output_error('订单不存在');
        }
        foreach ($order_info['extend_order_goods'] as $kk => $goods_info) {
            $order_info['extend_order_goods'][$kk]['goods_image_url'] = cthumb($goods_info['goods_image'], 240, $order_info['store_id']);
        }
        $express = rkcache('express',true);
        $e_code = $express[$order_info['extend_order_common']['shipping_express_id']]['e_code'];
        $e_name = $express[$order_info['extend_order_common']['shipping_express_id']]['e_name'];
        $deliver_info = $this->_get_express($e_code, $order_info['shipping_code']);

        $weekarray=array("日","一","二","三","四","五","六");
        output_data(array(
                'express_name' => $e_name,
                'shipping_code' => $order_info['shipping_code'],
                'deliver_info' => $deliver_info,
                'order_info'=>$order_info,
                'today'=>date('Y-m-d',time()),
                'xq'=>"星期".$weekarray[date("w")]
            )
        );
    }
    public function ajax_deliveryOp(){
        $express_id = intval($_GET['express_id']);
        $shipping_code = $_GET['shipping_code'];
        if(!$express_id||!$shipping_code){
            output_error('参数错误');
        }
        $express = rkcache('express',true);
        $e_code = $express[$express_id]['e_code'];
        $e_name = $express[$express_id]['e_name'];
        $deliver_info = $this->_get_express($e_code, $shipping_code);
        $deliver_info['e_name'] = $e_name;
        output_data(array('deliver_info'=>$deliver_info));
    }

    /**
     * 从第三方取快递信息
     *
     */
    public function _get_express($e_code, $shipping_code){
        //$url = BASE_SITE_URL.'http://www.kuaidi100.com/query?type='.$e_code.'&postid='.$shipping_code.'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
        //import('function.ftp');
        //$content = dfsockopen($url);
        //$content = json_decode($content,true);
        $url = 'http://www.kuaidi100.com/query?type='.$e_code.'&postid='.$shipping_code.'&id=1&valicode=&temp='.random(4).'&sessionid=&tmp='.random(4);
        $content = file_get_contents($url);
        $content = json_decode($content,true);
        /*
        if ($content['status'] != 200) {return;
            output_error('物流信息查询失败');
        }
        $content['data'] = array_reverse($content['data']);
        $output = array();
        if (is_array($content['data'])){
            foreach ($content['data'] as $k=>$v) {
                if ($v['time'] == '') continue;
                $output[]= $v['time'].'&nbsp;&nbsp;'.$v['context'];
            }
        }
        if (empty($output)) exit(json_encode(false));*/
        if (strtoupper(CHARSET) == 'GBK'){
            $output = Language::getUTF8($content);//网站GBK使用编码时,转换为UTF-8,防止json输出汉字问题
        }else{
            $output = $content;
        }
        return $output;
    }

    /**采购记录**/
    public function purchase_recordOp() {
        $where = array('order_state'=>array('in',array(ORDER_STATE_NEW,ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS,ORDER_STATE_CANCEL)));
        $list = $this->_order_list($where);

        output_data($list['data'], mobile_page($list['page_count']));
    }
    /**待付款订单
    public function order_listOp() {
        $model_order = Model('order');
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_state'] = array('in', array(ORDER_STATE_NEW));
        $order_list_array = $model_order->getNormalOrderList($condition, $this->page, '*', 'order_id desc','', array('order_goods'));

        $order_group_list = array();
        $order_pay_sn_array = array();
        foreach ($order_list_array as $value) {
            //显示取消订单
            $value['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$value);
            //显示收货
            $value['if_receive'] = $model_order->getOrderOperateState('receive',$value);
            //显示锁定中
            $value['if_lock'] = $model_order->getOrderOperateState('lock',$value);
            //显示物流跟踪
            $value['if_deliver'] = $model_order->getOrderOperateState('deliver',$value);

            //商品图
            foreach ($value['extend_order_goods'] as $k => $goods_info) {
                $value['extend_order_goods'][$k]['goods_image_url'] = cthumb($goods_info['goods_image'], 240, $value['store_id']);
            }

            $order_group_list[$value['pay_sn']]['order_list'][] = $value;

            //如果有在线支付且未付款的订单则显示合并付款链接
            if ($value['order_state'] == ORDER_STATE_NEW) {
                $order_group_list[$value['pay_sn']]['pay_amount'] += $value['order_amount'] - $value['rcb_amount'] - $value['pd_amount'];

            }
            $order_group_list[$value['pay_sn']]['add_time'] = $value['add_time'];

            //记录一下pay_sn，后面需要查询支付单表
            $order_pay_sn_array[] = $value['pay_sn'];
        }

        $new_order_group_list = array();
        foreach ($order_group_list as $key => $value) {
            $value['pay_sn'] = strval($key);
            if(isset($value['pay_amount'])&&$value['pay_amount']>0){
                $pay_order_ids=array();
                foreach ($value['order_list'] as $o){
                    $pay_order_ids[]=$o["order_id"];
                }
                $order_ids_str=implode(",",$pay_order_ids);
                $value['otherpay_sn']=encrypt((empty($this->member_info["member_truename"])?$this->member_info["member_name"]:$this->member_info["member_truename"]).",".$order_ids_str);
            }
            $new_order_group_list[] = $value;
        }

        $page_count = $model_order->gettotalpage();

        $array_data = array('order_group_list' => $new_order_group_list);
        if(isset($_GET['getpayment'])&&$_GET['getpayment']=="true"){
            $model_mb_payment = Model('mb_payment');

            $payment_list = $model_mb_payment->getMbPaymentOpenList();
            $payment_array = array();
            if(!empty($payment_list)) {
                foreach ($payment_list as $value) {
                    $payment_array[] = array('payment_code' => $value['payment_code'],'payment_name' =>$value['payment_name']);
                }
            }
            $array_data['payment_list'] = $payment_array;
        }
        $array_data["buyer_name"]=empty($this->member_info['member_truename'])?$this->member_info['member_name']:$this->member_info['member_truename'];
        //output_data(array('order_group_list' => $array_data), mobile_page($page_count));
        output_data($array_data, mobile_page($page_count));
    }**/
    /**待付款**/
    public function order_payOp(){
        $where = array('order_state'=>ORDER_STATE_NEW);
        $list = $this->_order_list($where);

        output_data($list['data'], mobile_page($list['page_count']));
    }
    /**待发货订单**/
    public function order_wait_sendOp() {
        $where = array('order_state'=>ORDER_STATE_PAY);
        $list = $this->_order_list($where);
        $order_list = $list['data']['order_list'];
        foreach($order_list as $k=>$order){
            if($order['refund_list'][0]){
                $order_list[$k]['view_refund_id'] = $order['refund_list'][0]['refund_id'];
            }
        }
        output_data(array('order_list' => $order_list), mobile_page($list['page_count']));
    }
    /**待收货订单**/
    public function order_wait_recOp() {
        $where = array('order_state'=>ORDER_STATE_SEND);
        $list = $this->_order_list($where);
        output_data($list['data'], mobile_page($list['page_count']));
    }
    /**交易成功**/
    public function order_succOp(){
        $where = array('order_state'=>ORDER_STATE_SUCCESS);
        $list = $this->_order_list($where);
        output_data($list['data'], mobile_page($list['page_count']));
    }
    /**交易关闭**/
    public function order_closeOp(){
        $where = array('order_state'=>ORDER_STATE_CANCEL);
        $list = $this->_order_list($where);
        output_data($list['data'], mobile_page($list['page_count']));
    }
    /*交易退款*/
    public function order_wait_refundOp(){
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['refund_state']= array('lt',3);
        $model_refund = Model('refund_return');
        $list = $model_refund->getRefundReturnList($condition,$this->page,'distinct(order_id) as order_id');
        $count = $model_refund->table('refund_return')->field('distinct(order_id)')->where($condition)->select();
        $count = count($count);
        $page_count = intval($count/$this->page)+1;if($count==0){$page_count=0;}
        $order_ids = array();
        foreach($list as $v){
            $order_ids[] = $v['order_id'];
        }
        $list = $this->_order_list(array('order_id'=>array('in',implode(',',$order_ids))));

        $order_list = $list['data']['order_list'];
        foreach($order_list as $k=>$order){
            if($order['refund_list'][0]&&count($order['refund_list'])==1){
                $order_list[$k]['view_refund_id'] = $order['refund_list'][0]['refund_id'];
            }
        }
        output_data(array('order_list' => $order_list), mobile_page($page_count));
    }



    /**订单详情**/
    public function order_detailOp(){
        $order_id	= $_POST['order_id'];
        $model_order	= Model('order');
        $model_goods = Model('goods');
        $condition['order_id'] = array('in',$order_id);
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_list = $model_order->getNormalOrderList($condition, '', '*', 'order_id desc','', array('order_goods','order_common'));
        $model_refund_return = Model('refund_return');

        $order_list = $model_refund_return->getGoodsRefundList($order_list,1);

        if (empty($order_list)) {
            output_error('订单不存在');
        }
        $output = array();
        $goods_ids = array();
        foreach($order_list as $k=>$order){
            foreach ($order['extend_order_goods'] as $kk => $goods_info) {
                $goods_ids[] = $goods_info['goods_id'];
            }
            if($order['order_state']!=ORDER_STATE_CANCEL){
                $output['pay_info']['order_amount'] += $order['order_amount'];
                $output['pay_info']['order_pay'] += $order['rcb_amount'] + $order['pd_amount'];
                $output['pay_info']['pay_amount'] += $order['order_amount'] - $order['rcb_amount'] - $order['pd_amount'];
                $output['pay_info']['shipping_fee'] += $order['shipping_fee'];
            }

        }

        $goods_info_list =  $model_goods->getGoodsList(array('goods_id'=>array('in',$goods_ids)),'goods_id,goods_commonid');
        $goods_kv = array();
        $goods_common_ids = array();
        foreach($goods_info_list as $val){
            $goods_kv[$val['goods_id']] = $val['goods_commonid'];
            $goods_common_ids[] = $val['goods_commonid'];
        }
        //主商品信息
        $goods_common_list = $model_goods->getGoodsCommonList(array('goods_commonid'=>array('in',$goods_common_ids)),'*',0);
        $goods_common_arr =array();
        foreach($goods_common_list as $val){
            $val['goods_image_url'] = cthumb($val['goods_image'], 240, $val['store_id']);
            $goods_common_arr[$val['goods_commonid']] = $val;
        }

        //整理订单商品数据
        $last_order = array();
        $order_ids = array();
        foreach($order_list as $order){
            $order_ids[] = $order['order_id'];
            $order_goods = array();
            foreach ($order['extend_order_goods'] as $kk => $goods_info) {
                $goods_info['goods_image_url'] = cthumb($goods_info['goods_image'], 240, $order['store_id']);
                $goods_info['goods_spec_name'] = str_replace($goods_common_arr[$goods_kv[$goods_info['goods_id']]]['goods_name'],'', $goods_info['goods_name']);
                $order_goods[$goods_kv[$goods_info['goods_id']]][] = $goods_info;
            }
            $order['goods_list'] = $order_goods;
            //取消订单
            $order['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$order);
            //显示付款
            $order['if_pay'] = $order['if_cancel'];
            //显示收货
            $order['if_receive'] = $model_order->getOrderOperateState('receive',$order);
            //显示物流跟踪
            $order['if_deliver'] = $model_order->getOrderOperateState('deliver',$order);
            //显示锁定中,退货退款
            $order['if_lock'] = $model_order->getOrderOperateState('lock',$order);
            //显示退款取消订单
            $order['if_refund_cancel'] = $model_order->getOrderOperateState('refund_cancel',$order);

            $output['order_list'][]=$order;
            if(!$last_order){
                $last_order = ($order['order_state'] == ORDER_STATE_NEW?$order:'');
            }
        }
        if(!$last_order){
            $last_order = end($output['order_list']);
        }

        //订单支付信息
        if( $last_order['order_state'] == ORDER_STATE_NEW){
            $class = 'dfk';
        }else   if( $last_order['order_state'] == ORDER_STATE_PAY){
            $class = 'dfh';
        }else   if( $last_order['order_state'] == ORDER_STATE_SEND){
            $class = 'dsh';
        }else   if( $last_order['order_state'] == ORDER_STATE_SUCCESS){
            $class = 'succ';
        }else   if( $last_order['order_state'] == ORDER_STATE_CANCEL){
            $class = 'fail';
        }
        $output['pay_info']['state_class'] =$class;
        $output['pay_info']['order_ids_str'] = implode(',',$order_ids);
        $output['pay_info']['pay_sn'] = $last_order['pay_sn'];
        $output['pay_info']['order_id'] = $last_order['order_id'];
        $output['pay_info']['add_time'] = $last_order['add_time'];
        $output['pay_info']['payment_time'] = $last_order['payment_time'];
        $output['pay_info']['extend_order_common'] = $last_order['extend_order_common'];
        $output['pay_info']['order_state'] = $last_order['order_state'];
        $output['pay_info']['if_new_order'] = $last_order['order_state']==ORDER_STATE_NEW;
        $output['pay_info']['state_desc'] = $last_order['state_desc'];
        $output['pay_info']['payment_name'] = preg_replace('/支付$/','',$last_order['payment_name'],1);
        $output['pay_info']['if_cancel'] = $last_order['if_cancel'];
        $output['pay_info']['if_pay'] = $last_order['if_pay'];
        $output['pay_info']['if_receive'] = $last_order['if_receive'];
        $output['pay_info']['if_deliver'] = $last_order['if_deliver'];
        $output['pay_info']['if_refund_cancel'] = $last_order['if_refund_cancel'];
        $output['pay_info']["buyer_name"]=empty($this->member_info['member_truename'])?$this->member_info['member_name']:$this->member_info['member_truename'];
        $output['pay_info']['otherpay_sn'] = encrypt((empty($this->member_info["member_truename"])?$this->member_info["member_name"]:$this->member_info["member_truename"]).",".$output['pay_info']['order_ids_str']);
        $output['pay_info']['if_take_apart'] = $last_order['take_apart'];
        $output['common_goods_list'] = $goods_common_arr;
        $output['view_refund_id'] = $last_order['refund_list'][0]['refund_id'];
        output_data($output);
    }
    /**买家删除订单**/
    public function order_delOp() {
        $model_order = Model('order');
        $logic_order = Logic('order');
        $order_id = intval($_POST['order_id']);
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info	= $model_order->getOrderInfo($condition);
        $if_allow = $model_order->getOrderOperateState('delete',$order_info);
        if (!$if_allow) {
            output_error('无权操作');
        }
        $result = $logic_order->changeOrderStateRecycle($order_info,'buyer','delete');
        if(!$result['state']) {
            output_error($result['msg']);
        } else {
            output_data(array('msg'=>'删除成功！'));
        }
    }
    /**订单**/
    private function _order_list($condition =array()){
        $output =array();
        $model_order = Model('order');
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_list_array = $model_order->getNormalOrderList($condition, $this->page, '*', 'order_id desc','', array('order_goods','order_common'));
        $model_refund_return = Model('refund_return');
        $order_list_array = $model_refund_return->getGoodsRefundList($order_list_array,1);
        $pay_list = array();
        foreach ($order_list_array as $k=>$value) {
            //取消订单
            $value['if_cancel'] = $model_order->getOrderOperateState('buyer_cancel',$value);
            //显示付款
            $value['if_pay'] =$order_list_array[$k]['if_cancel'];
            //显示收货
            $value['if_receive'] = $model_order->getOrderOperateState('receive',$value);
            //显示物流跟踪
            $value['if_deliver'] = $model_order->getOrderOperateState('deliver',$value);
            //显示退款取消订单
            $value['if_refund_cancel'] = $model_order->getOrderOperateState('refund_cancel',$value);
            //显示锁定中
            $value['if_lock'] = $model_order->getOrderOperateState('lock',$value);
            //商品图
            $goods_ids = array();
            $goods_count = array();
            foreach ($value['extend_order_goods'] as $kk => $goods_info) {
                $value['extend_order_goods'][$kk]['goods_image_url'] = cthumb($goods_info['goods_image'], 240, $value['store_id']);
                $goods_ids[] = $goods_info['goods_id'];
                $goods_count[] = $goods_info['goods_num'];
            }
            $value['goods_ids'] = implode(',',$goods_ids);
            $value['goods_count'] = implode(',',$goods_count);
            //待付款
            if($value['order_state']==ORDER_STATE_NEW){
                if($pay_list[$value['pay_sn']]['extend_order_goods']){
                    $pay_list[$value['pay_sn']]['extend_order_goods'] =array_merge($pay_list[$value['pay_sn']]['extend_order_goods'],$value['extend_order_goods']);
                }else{
                    $pay_list[$value['pay_sn']]['extend_order_goods'] = $value['extend_order_goods'];
                }
                $pay_list[$value['pay_sn']]['pay_amount'] += $value['order_amount'] - $value['rcb_amount'] - $value['pd_amount'];
                $pay_list[$value['pay_sn']]['order_amount'] += $value['order_amount'];
                $pay_list[$value['pay_sn']]['order_ids'][] = $value['order_id'];
                $pay_list[$value['pay_sn']]['order_list'][] = $value;
                unset($order_list_array[$k]);
            }else{
                $output[] =$value;
            }
        }
        if($pay_list){
            foreach($pay_list as $k=>$val){
                $val['order_pay'] = true;
                $val['pay_sn'] = $k;
                $val['store_name'] = $val['order_list'][0]['store_name'];
                if(count($val['order_list'])>1){
                    $val['store_name'] .= '...';
                }
                $val['add_time'] = $val['order_list'][0]['add_time'];
                $val['state_desc'] = $val['order_list'][0]['state_desc'];
                $val['order_ids_str'] = implode(',',$val['order_ids']);
                $val["buyer_name"]=empty($this->member_info['member_truename'])?$this->member_info['member_name']:$this->member_info['member_truename'];
                if(isset($val['pay_amount'])&&$val['pay_amount']>0){
                    $val['otherpay_sn'] = encrypt($val["buyer_name"].",".$val['order_ids_str']);
                }
                $output[] = $val;
            }
            function compare($x,$y){
                if($x['add_time'] == $y['add_time'])
                    return 0;
                elseif($x['add_time'] < $y['add_time'])
                    return 1;
                else
                    return -1;
            }
            usort($output,"compare");
        }
        $page_count = $model_order->gettotalpage();
        $array_data = array('order_list' => $output);
        return array('data'=>$array_data,'page_count'=>$page_count);
    }
    public function sub_order_detailOp(){
        $order_id = intval($_GET['order_id']);
        $model_order	= Model('order');
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['take_apart'] = 1;
        $condition['buyer_id'] = $this->member_info['member_id'];
        $order_info = $model_order->getOrderInfo($condition,array('order_goods'));
        if(!$order_info){
            output_error('订单不存在！');
        }
        $goods_list = array();
        foreach($order_info['extend_order_goods'] as $val){
            $val['img_url_240']= cthumb($val['goods_image'], 240, $val['store_id']);
            $goods_list[$val['goods_id']] = $val;
        }
        $condition = array();
        $condition['order_id'] = $order_id;
        $sub_order = $model_order->getSubOrderList($condition);
        foreach($sub_order as $k=>$val){
            $val['goods_info'] = (array)unserialize($val['goods_info']);
            $val['buyer_info'] = (array)unserialize($val['buyer_info']);
            $val['reciver_info'] = (array)unserialize($val['reciver_info']);
            $sub_order[$k] = $val;
        }
        output_data(array('goods_list'=>$goods_list,'sub_order_list'=>$sub_order));
    }

    /**订单付款页面**/
    public function payOp(){
        $order_ids = $_POST['order_id'];
        $model_order = Model('order');
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_id'] = array('in',$order_ids);
        $order_list_array = $model_order->getNormalOrderList($condition, $this->page, '*', 'order_id desc','', array('order_goods','order_common'));
        if(!$order_list_array){
            output_error('订单不存在');
        }
        $pay_info =array();
        foreach($order_list_array as $order){
            if($order['order_state']==ORDER_STATE_NEW){
                $pay_info['order_amount'] += $order['order_amount'];
                $pay_info['order_pay'] += $order['rcb_amount'] + $order['pd_amount'];
                $pay_info['pay_amount'] += $order['order_amount'] - $order['rcb_amount'] - $order['pd_amount'];
                $pay_info['pay_sn'] = $order['pay_sn'];
            }

        }
        $model_mb_payment = Model('mb_payment');
        $payment_list = $model_mb_payment->getMbPaymentOpenList();
        $payment_array = array();
        //ygy
        //银行卡列表
        $model_bank_card = Model('bank_card');
        $bank_card_list = array();

        if(!empty($payment_list)) {
            foreach ($payment_list as $value) {
                if($value['payment_code']=='predeposit'){
                    if($this->member_info['available_predeposit']<$pay_info['pay_amount']){//余额不够支付
                        continue;
                    }else{
                        $payment_array[] = array('payment_code' => $value['payment_code'],'payment_name' =>$value['payment_name'],'pd_amount'=>$this->member_info['available_predeposit']);
                    }
                }else{
                    $payment_array[] = array('payment_code' => $value['payment_code'],'payment_name' =>$value['payment_name']);
                }
                //ygy
                if($value['payment_code']=='fuiou'){
                    $bank_card_list = $model_bank_card->getList($this->member_info['member_id']);
                    foreach($bank_card_list as &$val){
                        $val['bank_card_no_short'] = substr($val['bank_card_no'],-4,4);
                    }
                }
            }
        }
        output_data(array('pay_way_list'=>$payment_array,'pay_info'=>$pay_info,'bank_card_list'=>$bank_card_list));
    }

    /**线下转账**/
    public function transferOp(){
        $order_ids = $_POST['order_id'];
        $model_order = Model('order');
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_id'] = array('in',$order_ids);
        $order_list_array = $model_order->getNormalOrderList($condition, $this->page, '*', 'order_id desc','', array('order_goods','order_common'));
        if(!$order_list_array){
            output_error('订单不存在');
        }
        $model_mb_payment = Model('mb_payment');
        $payment_info = $model_mb_payment->getMbPaymentOpenInfo(array('payment_code'=>'transfer'));

        $pay_info =array();
        foreach($order_list_array as $order){
            if($order['order_state']==ORDER_STATE_NEW){
                $pay_info['order_amount'] += $order['order_amount'];
                $pay_info['order_pay'] += $order['rcb_amount'] + $order['pd_amount'];
                $pay_info['pay_amount'] += $order['order_amount'] - $order['rcb_amount'] - $order['pd_amount'];
                $pay_info['pay_sn'] = $order['pay_sn'];
            }
        }
        output_data(array('payment_info'=>$payment_info,'pay_info'=>$pay_info));
    }


    /**
     * 富友支付
     */
    public function fuiou_payOp(){
        $card_id = intval($_GET['id']);
        $order_ids = $_GET['order_id'];
        $order_condition = array();
        $order_condition['order_id'] = array('in',$order_ids);

        $order_info = Model('order')->table('order')->where($order_condition)->find();
        $pay_sn = $order_info['pay_sn'];
        $logic_payment = Logic('payment');
        //重新计算所需支付金额
        $result = $logic_payment->getRealOrderInfo($pay_sn, $this->member_info['member_id']);
        $bank_card_info = Model('bank_card')->table('bank_card')->where(array('member_id'=>$this->member_info['member_id'],'id'=>$card_id))->find();

        include_once dirname(__FILE__).'/../api/payment/fuiou/fuiou.php';
        $payClass = new fuiou();
        $payInfo = $payClass->saveOrder($result['data']["api_pay_amount"]);
        if($payInfo['error'] == 0){
            $update = array();
            $update['payment_code'] = 'fuiou';
            $update['third_id'] = $payInfo['OrderId'];
            if( Model('order')->table('order')->where($order_condition)->update($update)){
                $fu_order_id =  $payInfo['OrderId'];
            }else{
                json_return(1013, '支付失败，请重新支付');
            }
        }else{
            json_return(1013, '支付失败，请重新支付');
        }
        $ret = $payClass->pay($order_ids,$fu_order_id,$bank_card_info);
        echo json_encode($ret);
    }

}
