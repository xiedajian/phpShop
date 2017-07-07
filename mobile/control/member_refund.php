<?php
/**
 * 退货退款
 *
 *
 *
 *

 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class member_refundControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 添加订单商品部分退款
     *
     */
    public function add_refundOp(){
        $model_refund = Model('refund_return');
        $condition = array();
        $reason_list = $model_refund->getReasonList($condition);//退款退货原因
        $order_id = intval($_GET['order_id']);
        $goods_id = intval($_GET['goods_id']);//订单商品表编号
        if(chksubmit()){
            $order_id = intval($_POST['order_id']);
            $goods_id = intval($_POST['goods_id']);//订单商品表编号
        }
        if ($order_id < 1 || $goods_id < 1) {//参数验证
            output_error('参数错误');
        }
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_id'] = $order_id;
        $order = $model_refund->getRightOrderList($condition, $goods_id);
        if(empty($order)){
            output_error('订单不存在');
        }
        $order_amount = $order['order_amount'];//订单金额
        $order_refund_amount = $order['refund_amount'];//订单退款金额
        $goods_list = $order['goods_list'];
        $goods = $goods_list[0];
        $goods_pay_price = $goods['goods_pay_price'];//商品实际成交价
        if ($order_amount < ($goods_pay_price + $order_refund_amount)) {
            $goods_pay_price = $order_amount - $order_refund_amount;
            $goods['goods_pay_price'] = $goods_pay_price;
        }
        $goods_id = $goods['rec_id'];
        $condition = array();
        $condition['buyer_id'] = $order['buyer_id'];
        $condition['order_id'] = $order['order_id'];
        $condition['order_goods_id'] = $goods_id;
        $condition['seller_state'] = array('lt','3');
        $refund_list = $model_refund->getRefundReturnList($condition);
        $refund = array();
        if (!empty($refund_list) && is_array($refund_list)) {
            $refund = $refund_list[0];
        }
        $refund_state = $model_refund->getRefundState($order);//根据订单状态判断是否可以退款退货
        if ($refund['refund_id'] > 0 || $refund_state != 1) {//检查订单状态,防止页面刷新不及时造成数据错误
            output_error(Language::get('wrong_argument'));
        }
        if (chksubmit() && $goods_id > 0){
            $refund_array = array();
            $refund_amount = floatval($_POST['refund_amount']);//退款金额
            if (($refund_amount < 0) || ($refund_amount > $goods_pay_price)) {
                $refund_amount = $goods_pay_price;
            }
            $goods_num = intval($_POST['goods_num']);//退货数量
            if (($goods_num < 0) || ($goods_num > $goods['goods_num'])) {
                $goods_num = 1;
            }
            $refund_array['reason_info'] = '';
            $reason_id = intval($_POST['reason_id']);//退货退款原因
            $refund_array['reason_id'] = $reason_id;
            $reason_array = array();
            $reason_array['reason_info'] = '其他';
            $reason_list[0] = $reason_array;
            if (!empty($reason_list[$reason_id])) {
                $reason_array = $reason_list[$reason_id];
                $refund_array['reason_info'] = $reason_array['reason_info'];
            }

            $pic_array = array();
            $pic_array['buyer'] = array(
                1=>$_POST['one_img'],
                2=>$_POST['two_img'],
                3=>$_POST['three_img'],
            );
            $info = serialize($pic_array);
            $refund_array['pic_info'] = $info;

            $model_trade = Model('trade');
            $order_shipped = $model_trade->getOrderState('order_shipped');//订单状态30:已发货
            if ($order['order_state'] == $order_shipped) {
                $refund_array['order_lock'] = '2';//锁定类型:1为不用锁定,2为需要锁定
            }
            $refund_array['refund_type'] = $_POST['refund_type'];//类型:1为退款,2为退货
            $show_url = 'index.php?act=member_return&op=index';
            $refund_array['return_type'] = '2';//退货类型:1为不用退货,2为需要退货
            if ($refund_array['refund_type'] != '2') {
                $refund_array['refund_type'] = '1';
                $refund_array['return_type'] = '1';
                $show_url = 'index.php?act=member_refund&op=index';
            }
            $refund_array['seller_state'] = '1';//状态:1为待审核,2为同意,3为不同意
            $refund_array['refund_amount'] = ncPriceFormat($refund_amount);
            $refund_array['goods_num'] = $goods_num;
            $refund_array['buyer_message'] = $_POST['buyer_message'];
            $refund_array['add_time'] = time();
            $state = $model_refund->addRefundReturn($refund_array,$order,$goods);
            if ($state) {
                if ($order['order_state'] == $order_shipped) {
                    $model_refund->editOrderLock($order_id);
                }
                output_data(array('msg'=>'提交成功','url'=>$show_url));
            } else {
                output_error(array('msg'=>'提交失败'));
            }
        }
        output_data(array('goods'=>$goods,'reason_list'=>$reason_list));
    }


    /**
     * 添加全部退款即取消订单
     *
     */
    public function add_refund_allOp(){
        $model_order = Model('order');
        $model_trade = Model('trade');
        $model_refund = Model('refund_return');
        $order_id = intval($_GET['order_id']);
        if (chksubmit()) {
            $order_id = intval($_POST['order_id']);
        }
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['order_id'] = $order_id;
        $order = $model_refund->getRightOrderList($condition);
        $order_amount = $order['order_amount'];//订单金额
        $condition = array();
        $condition['buyer_id'] = $order['buyer_id'];
        $condition['order_id'] = $order['order_id'];
        $condition['goods_id'] = '0';
        $condition['seller_state'] = array('lt','3');
        $refund_list = $model_refund->getRefundReturnList($condition);
        $refund = array();
        if (!empty($refund_list) && is_array($refund_list)) {
            $refund = $refund_list[0];
        }
        $order_paid = $model_trade->getOrderState('order_paid');//订单状态20:已付款
        $payment_code = $order['payment_code'];//支付方式
        if ($refund['refund_id'] > 0 || $order['order_state'] != $order_paid || $payment_code == 'offline') {//检查订单状态,防止页面刷新不及时造成数据错误
            output_error('参数错误');
        }
        $reason_list = $model_refund->getReasonList(array());//退款退货原因
        if (chksubmit()) {
            $reason_id = intval($_POST['reason_id']);
            if(empty($reason_list[$reason_id])){
                output_error('参数错误');
            }
            $refund_array = array();
            $refund_array['refund_type'] = '1';//类型:1为退款,2为退货
            $refund_array['seller_state'] = '1';//状态:1为待审核,2为同意,3为不同意
            $refund_array['order_lock'] = '2';//锁定类型:1为不用锁定,2为需要锁定
            $refund_array['goods_id'] = '0';
            $refund_array['order_goods_id'] = '0';
            $refund_array['reason_id'] = $reason_id;
            $refund_array['reason_info'] =$reason_list[$reason_id]['reason_info']; //'取消订单，全部退款';
            $refund_array['goods_name'] = '订单商品全部退款';
            $refund_array['refund_amount'] = ncPriceFormat($order_amount);
            $refund_array['buyer_message'] = $_POST['buyer_message'];
            $refund_array['add_time'] = time();

            $pic_array = array();
            $pic_array['buyer'] = array(
                1=>$_POST['one_img'],
                2=>$_POST['two_img'],
                3=>$_POST['three_img'],
            );
            $info = serialize($pic_array);
            $refund_array['pic_info'] = $info;
            $state = $model_refund->addRefundReturn($refund_array,$order);
            if ($state) {
                $model_refund->editOrderLock($order_id);
                output_data(array('msg'=>'提交成功'));
            } else {
               output_error('提交失败');
            }
        }
        output_data(array('order'=>$order,'reason_list'=>$reason_list));
    }

    /**
     * 退款退货查看
     *
     */
    public function viewOp(){
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['refund_id'] = intval($_GET['refund_id']);
        $refund = $model_refund->getRefundReturnInfo($condition);
        if(empty($refund)){
            output_error('记录不存在');
        }
        $model_order = Model('order');
        $order_info = $model_order->getOrderInfo(array('order_id'=>$refund['order_id']),array('order_goods'));
        foreach($order_info['extend_order_goods'] as $k=>$val){
            $val['goods_image_url'] = cthumb($val['goods_image'], 240, $val['store_id']);
            $order_info['extend_order_goods'][$k] = $val;
        }
        if(!empty($refund['pic_info'])) {
            $refund['pic_info'] = unserialize($refund['pic_info']);
            $refund['dir'] =  $dir =UPLOAD_SITE_URL.DS. ATTACH_PATH.DS.'refund'.DS;
        }
        if ($refund['express_id'] > 0 && !empty($refund['invoice_no'])) {
            $express_list  = rkcache('express',true);
            $refund['express_info'] = $express_list[$refund['express_id']];
        }
        output_data(array('refund'=>$refund,'order_info'=>$order_info));
    }

    /**
     * 发货
     *
     */
    public function sendOp(){
        $refund_id = intval($_GET['refund_id']);
        if(chksubmit()){
            $refund_id = intval($_POST['refund_id']);
        }
        $model_refund = Model('refund_return');
        $condition = array();
        $condition['buyer_id'] = $this->member_info['member_id'];
        $condition['refund_id'] = $refund_id;
        $return_list = $model_refund->getReturnList($condition);
        if(empty($return_list)){
            output_error('参数错误');
        }
        $return = $return_list[0];
        $express_list  = rkcache('express',true);
        if ($return['seller_state'] != '2' || $return['goods_state'] != '1') {//检查状态,防止页面刷新不及时造成数据错误
            output_error('参数错误');
        }
        if (chksubmit()) {
            if(!intval($_POST['express_id'])||!$_POST['invoice_no']){
                output_error('参数1错误');
            }
            $refund_array = array();
            $refund_array['ship_time'] = time();
            $refund_array['delay_time'] = time();
            $refund_array['express_id'] = $_POST['express_id'];
            $refund_array['invoice_no'] = $_POST['invoice_no'];
            $refund_array['goods_state'] = '2';
            $state = $model_refund->editRefundReturn($condition, $refund_array);
            if ($state) {
                output_data(array('msg'=>'提交成功'));
            } else {
                output_error('提交失败');
            }
        }
        output_data(array('refund'=>$return,'express_list'=>$express_list));
    }


    public function image_uploadOp(){
        // 上传图片
        $member_id=$this->member_info['member_id'];
        $upload = new UploadFile();
        $upload->set('default_dir', ATTACH_PATH.DS.'refund'.DS);
        $upload->set('max_size',C('image_max_filesize'));
        $upload->set('thumb_width', "100,1000");
        $upload->set('thumb_height', "100,1000");
        $upload->set('thumb_ext', "_s,_b");
        $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
        $result = $upload->upfile($_POST['name']);
        if (!$result) {
            output_error($upload->error);
        }
        $filename=$upload->get('file_name');
        output_data(array("img_path"=>UPLOAD_SITE_URL.DS.$upload->get('default_dir').str_replace('.','_s.',$filename),"img_name"=>$filename));
    }
}
