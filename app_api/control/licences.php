<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2016/9/14
 * Time: 16:56
 */
class licencesControl extends BaseAPPControl{
    public function __construct(){
        parent::__construct();
    }
/*
 * 计费产品列表接口
 * */
    public function goodsOp(){
        $goods_model=Model('licences');
        $goods=$goods_model->getlicences_goods_list();
       if($goods){
           $this->returnJson(0,'操作成功',json_encode($goods));
       }else{
            $this->returnJson(1001,"操作失败");   
       }
    }

    /*
     * 公司的授权信息接口
     * */
    public function indexOp(){
       $licences=Model('licences');
        $member_id=intval($this->currentUser['member_id']);
        if(empty($member_id)){
            $this->returnJson(303,"参数不正确");
        }
        $data=$licences->getlicences_company($member_id);
        $this->returnJson(0,'',json_encode($data));
    }

    /*
     * 付费授权订单生成接口
     * */
    /*
    public function orderOp(){
        $member_id=intval($this->currentUser['member_id']);
        if(empty($member_id)){
            $this->returnJson(303,"参数不正确");
        }

        if(!empty($this->POST["app_code"])){
            $app_code=$this->POST["app_code"];
        }else{
            $this->returnJson(303,"参数不正确");
        }

        if(!empty($this->POST["goods_id"])){
            $goods_id=$this->POST["goods_id"];
        }else{
            $this->returnJson(303,"参数不正确");
        }

        if(!empty($this->POST["goods_cid"])){
            $goods_cid=$this->POST["goods_cid"];
        }else{
            $this->returnJson(303,"参数不正确");
        }

        if(!empty($this->POST['licences_months'])){
            $licences_months=$this->POST["licences_months"];
        }else{
            $licences_months=0;
        }
        if(!empty($this->POST['deal_prices'])){
            $deal_prices=$this->POST['deal_prices'];
        }else{
            $this->returnJson(303,"参数不正确");
        }
        if(!empty($this->POST["licences"])){
            $licences=$this->POST["licences"];
        }else{
            $licences=1;
        }
        $pay_way=$this->POST['pay_way'];


        $model_member = Model('member');
        $model_goods=Model('goods');
        $model_buy=Model('buy');
        $model_order=Model('order');
        $member_info=$model_member->getMemberInfoByID($member_id); //获得用户信息
        $goods_info=$model_goods->getGoodsInfo(array("goods_id"=>$goods_id));//获取商品信息
        //写入到order_pay 表
        $pay_sn=$model_buy->makePaySn($member_id);
        $order_pay=array();
        $order_pay["pay_sn"]=$pay_sn;
        $order_pay["buyer_id"]=$member_id;
        $order_pay_id=$model_order->addOrderPay($order_pay);

        //写入到order 表
        $order=array();
        $order_sn=$model_buy->makeOrderSn($order_pay_id);
        $order["order_sn"]=$order_sn;
        $order["pay_sn"]=$pay_sn;
        $order["store_id"]=$goods_info["store_id"];
        $order["store_name"]=$goods_info["store_name"];
        $order["buyer_id"]=$member_id;
        $order["buyer_name"]=$member_info["member_name"];
        $order["buyer_email"]=$member_info["member_email"];
        $order["add_time"]=TIMESTAMP;
        $order["payment_code"]="online";
        $order["order_state"]=10;
        $order["order_amount"]=$deal_prices;
        $order["shipping_fee"]=0;
        $order["goods_amount"]=$deal_prices;
        $order["order_from"]=2;
        $order["order_type"]=2;
        $charge_data=array(); //付费订单的扩展信息
        $charge_data["app_code"]=$app_code;
        $charge_data["licences_months"]=$licences_months;
        $charge_data["licences"]=$licences;

        $chargeremark=serialize($charge_data);

        $order['charge_remark']=$chargeremark;
        $order_id=$model_order->addOrder($order);


        //写入到order_goods表
        $order_goods=array();
        $order_goods["order_id"]=$order_id;
        $order_goods["goods_id"]=$goods_info["goods_id"];
        $order_goods["goods_name"]=$goods_info["goods_name"];
        $order_goods["goods_price"]=$goods_info["goods_price"];
        $order_goods["goods_num"]=$licences;
        $order_goods["goods_image"]=$goods_info["goods_image"];
        $order_goods["goods_pay_price"]=$deal_prices;
        $order_goods["goods_pay_type"]=0;
        $order_goods["store_id"]=$goods_info["store_id"];
        $order_goods["buyer_id"]=$member_id;
        $order_goods["goods_type"]=1;
        $order_goods["promotions_id"]=0;
        $order_goods["commis_rate"]=0;
        $order_goods["gc_id"]=$goods_info["gc_id"];
        if($app_code=="ZG"){
            $order_goods["month"]=$licences_months;
        }
        $model_order->addOrderGoodsOne($order_goods);

        if($order_id){
            $data=array();
            $data["goods_name"]=$goods_info["goods_name"];
            $data["body"]="";
            $data["price"]=$deal_prices;
            $data["order_sn"]=$order_sn;
            $data["pay_sn"]=$pay_sn;
            $this->returnJson(0,"",$data);
        }else{
            $this->returnJson(500,"创建订单失败");
        }
    }
    */
    public function orderOp(){
        $model_licences=Model("licences");
        $order_data=$this->POST;
        $order_data['member_id']=intval($this->currentUser['member_id']);
        $data=$model_licences->setorder($order_data);
        $this->returnJson($data["no"],$data["msg"],$data["data"]);
    }
}


?>