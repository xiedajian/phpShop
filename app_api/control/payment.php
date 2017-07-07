<?php
/*
 * 支付接口
 *
 * */
defined('InShopNC') or exit('Access Invalid!');
if (!@include(BASE_PATH.'/config.php')) exit('config.php isn\'t exists!');

class paymentControl{
    /*
     * 手机端支付回调
     *
     * */
    public  function PayBackOp(){
        $success = 'success'; $fail = 'fail';
        /*  switch ($_GET['payment_code']) {
              case 'alipay':
                  $success = 'success'; $fail = 'fail'; break;
              case 'chinabank':
                  $success = 'ok'; $fail = 'error'; break;
              case 'fuiou':
                  $success = 'ok'; $fail = 'error'; break;
              default:
                  exit();
          }*/

        $order_type = 'real_order';
        $out_trade_no = $_POST['out_trade_no'];
        $trade_no = $_POST['trade_no'];


        //参数判断
        if(!preg_match('/^\d{18}$/',$out_trade_no)) exit($fail);

        $model_pd = Model('predeposit');
        $logic_payment = Logic('payment');

        if ($order_type == 'real_order') {
            $result = $logic_payment->getRealOrderInfo($out_trade_no);
            if (intval($result['data']['api_pay_state'])) {
                exit($success);
            }
            $order_list = $result['data']['order_list'];

        }  else {
            exit();
        }
        $order_pay_info = $result['data'];
        //取得支付方式

        try{
            $pay_log=array();
            $pay_log["order_type"]=$order_type;
            $pay_log["out_trade_no"]=$out_trade_no;
            $pay_log["trade_no"]=$trade_no;
            $pay_log["remark"]=serialize($result);
            $model_order=Model('order');
            $model_order->add_pay_log($pay_log);
        }catch(Exception $e){

        }
        //购买商品
        if ($order_list[0]["order_type"]==2) {
            $model_licences=Model("licences");
            $result=$model_licences->update_licences_order($_GET['payment_code'], $order_list, $trade_no);
        }
        exit($result['state'] ? $success : $fail);
    }

}



?>