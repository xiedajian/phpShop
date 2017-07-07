<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2016/9/21
 * Time: 16:56
 */
//付费pc接口调用
defined('InShopNC') or exit('Access Invalid!');
if (!@include(BASE_PATH.'/config.php')) exit('config.php isn\'t exists!');
class licences_pcControl{
    protected function returnJson($no,$msg,$data=''){
        echo json_encode(array('no'=>$no,'msg'=>$msg,'data'=>$data));
        exit;
    }
    /*
    * 获取用户付费授权商品情况
    * */
    public function indexOp(){
        $member_id=$_POST["member_id"];
        $data=array();
        $data["member_id"]=$member_id;
        //$data["app_code"]="KMF";
        $model_licences=Model("licences");
        $licences_data=$model_licences->getlicences_info($data);
        $this->returnJson(0,"",$licences_data);
    }

    /*
     * 获取付费商品列表
     * */
    public function goodsOp(){
        $model_licences=Model('licences');
        $data=array();
        $data["app_code"]=$_POST["app_code"];
       // $data["app_code"]=array('in',$code);
        $goodslist=$model_licences->getlicences_goods_list($data);
        $this->returnJson(0,"操作成功",$goodslist);
    }

    /*
     * 获得当前购买许可的详情
     * */

    public function orderinfoOp(){
        $model_order=Model('order');
        $model_licences=Model("licences");
        $member_id=$_POST["member_id"];
        $data=array();
        $data["member_id"]=3;
        $data["app_code"]="KMF";
        $paylog=$model_licences->getlicences_paylog($data);
        $orderinfo=$model_order->getorder_info(array("pay_sn"=>$paylog["pay_no"]),"payment_time desc");

        if($orderinfo==null){
            $this->returnJson(0,"操作成功",null);
            exit;
        }
        $send=array();
        //$send["add_time"]=date('Y-m-d H:i:s',$orderinfo["add_time"]);
        //$send["payment_time"]=date('Y-m-d',$orderinfo["payment_time"]);
        $send["add_time"]=date('Y-m-d H:i:s',$orderinfo["add_time"]);
        $send["payment_time"]=date('Y-m-d H:i:s',$orderinfo["payment_time"]);
        $this->returnJson(0,"操作成功",$send);
    }

    /*
     * pc端创建订单
     * */
    public function orderOp(){
        $model_licences=Model("licences");
        $data=$model_licences->setorder($_POST);
        $this->returnJson($data["no"],$data["msg"],$data["data"]);
    }

    /*
     * pc端获取最近一次订单情况
     *只要kmf 的和 zg增购的
     * */
    public function lastOrderOp(){
        $model_order=Model('order');
        $data=array();
        $data["order_type"]=2;
        $data["buyer_id"]=$_POST["member_id"];
        $order_info=$model_order->getorder_list($data,"add_time desc");
       if($order_info){
           foreach($order_info as $list){
               $charge_remark=unserialize($list["charge_remark"]);

               if($charge_remark["app_code"]=="KMF" || $charge_remark["app_code"]=="ZG"){
                   if($list["order_state"]==40){
                       $this->returnJson(0,"操作成功",null);
                       exit;
                   }
                   $order_data=array();
                   $order_data["order_state"]=$list["order_state"];
                   $order_data["add_time"]=date('Y-m-d H:i:s', $list["add_time"]);
                   $order_data["order_amount"]=$list["order_amount"];
                   $order_data["app_code"]=$charge_remark["app_code"];
                   $order_data["licences_months"]=$charge_remark["licences_months"];
                   $order_data["licences"]=$charge_remark["licences"];
                   $this->returnJson(0,"操作成功",$order_data);
                   exit;
               }
           }
       }
        $this->returnJson(0,"操作成功",null);
    }


}
?>