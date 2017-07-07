<?php
/**
 * Created by PhpStorm.
 * User: lin
 * Date: 2016/9/18
 * Time: 9:28
 */
/*
 * 计费模型
 * */
defined('InShopNC') or exit('Access Invalid!');
class licencesModel extends Model{
    public function __construct(){
        parent::__construct('licences_goods');
    }

    /*
     * 获取计费产品
     * */

    public  function getlicences_goods_list($data=array()){
        return $this->table('licences_goods')->where($data)->select();
    }

    /*
     * 获取公司授权信息
     * */
    public function getlicences_company($member_id){

        return $this->table('licences_companys')->where(array('member_id'=>$member_id))->select();
    }
    /*
     * 创建订单
     * */
    public function setorder($order_data){
        $error=array();
        $error["no"]=303;
        $error["msg"]="参数不正确";
        if(!empty($order_data["member_id"])){
            $member_id=$order_data["member_id"];
        }else{
            return $error;
        }
        if(!empty($order_data["app_code"])){
            $app_code=$order_data["app_code"];
        }else{
            return $error;
        }

        if(!empty($order_data["goods_id"])){
            $goods_id=$order_data["goods_id"];
        }else{
            return $error;
        }

        if(!empty($order_data["goods_cid"])){
            $goods_cid=$order_data["goods_cid"];
        }else{
            return $error;
        }

        if(!empty($order_data['licences_months'])){
            $licences_months=$order_data["licences_months"];
        }else{
            $licences_months=0;
        }
        if(!empty($order_data['deal_prices'])){
            $deal_prices=$order_data['deal_prices'];
        }else{
            return $error;
        }
        if(!empty($order_data["licences"])){
            $licences=$order_data["licences"];
        }else{
            $licences=1;
        }
        $pay_way=$order_data['pay_way'];

        $model_member = Model('member');
        $model_goods=Model('goods');
        $model_buy=Model('buy');
        $model_order=Model('order');
        //判断订单是否已经存在，如果存在的话就不再创建新的订单
        $check=array();
        $charge_data=array(); //付费订单的扩展信息
        $charge_data["app_code"]=$app_code;
        $charge_data["licences_months"]=$licences_months;
        $charge_data["licences"]=$licences;

        $chargeremark=serialize($charge_data);
        $check["buyer_id"]=$member_id;
        $check['charge_remark']=$chargeremark;
        $check["order_state"]=10;

        $getorderdata=$model_order->getorder_info($check);
        if($getorderdata!=null){
            $orderupdate = $model_order->editOrder(array('add_time'=>TIMESTAMP),array('order_id'=>$getorderdata["order_id"]));

            $goods_info=$model_goods->getGoodsInfo(array("goods_id"=>$goods_id));//获取商品信息
            $data=array();
            $data["goods_name"]=$goods_info["goods_name"];
            $data["body"]="";
            $data["price"]=$deal_prices;
            $data["order_sn"]=$getorderdata["order_sn"];
            $data["pay_sn"]=$getorderdata["pay_sn"];
            $success=array();
            $success["no"]=0;
            $success["msg"]="创建订单成功";
            $success["data"]=$data;
            return $success;
        }


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
            $success=array();
            $success["no"]=0;
            $success["msg"]="创建订单成功";
            $success["data"]=$data;
            return $success;
        }else{
            $error["no"]=500;
            $error["msg"]="创建订单失败";
            return $error;
        }

    }

    /*
     * 付费订单支付成功后修改状态
     * */
    public function update_licences_order($payment_code, $order_list, $trade_no=null){
        //更新order 表的状态和order_pay的状态
        $model_order = Model('order');
        try {
            $model_order->beginTransaction();
            $data=array();
            $data["api_pay_state"]=1;
            $update=$model_order->editOrderPay($data,array('pay_sn'=>$order_list[0]['pay_sn']));

            if(!$update){
                throw new Exception('更新支付单状态失败');
            }

            //更新订单状态
            $update_order=array();
            $update_order["order_state"]=40;
            $update_order["payment_time"]=TIMESTAMP;
            $update_order["payment_code"]=$payment_code;
            $update = $model_order->editOrder($update_order,array('pay_sn'=>$order_list[0]['pay_sn'],'order_state'=>ORDER_STATE_NEW));

            if (!$update) {
                throw new Exception('操作失败');
            }
            //写入缴费记录表
            $order_goods=$model_order->getOrderGoodsInfo(array("order_id"=>$order_list[0]["order_id"])); //获得商品信息
            $licences_goods=$this->getlicences_goods($order_goods["goods_id"]);

            $paylog_data=array();
            $paylog_data["app_code"]=$licences_goods["app_code"];
            $paylog_data["member_id"]=$order_list[0]["buyer_id"];
            $paylog_data["pay_money"]=$order_list[0]["order_amount"];
            $paylog_data["order_no"]=$order_list[0]["order_sn"];
            $paylog_data["pay_no"]=$order_list[0]["pay_sn"];
            $paylog_data["pre_order_no"]=$trade_no;
            $paylog_data["pay_date"]=date('Y-m-d H:i:s',TIMESTAMP);
            $paylog_data["pay_type"]=$payment_code;
            $result=$this->addlicences_paylog($paylog_data);
            // $licences_goods["app_code"]="MRJ";
            $charge_data=array();
            $charge_data["app_code"]=$licences_goods["app_code"];
            $charge_data["licences_months"]=$licences_goods["licences_months"];
            $charge_data["licences"]=$order_goods["goods_num"];
            //user_app表的参数
            $user_app_data=array();
            $user_app_data['uid']=$order_list[0]["buyer_id"];
            //更新客户的购买情况
            if($licences_goods["app_code"]=="KMF"){ //购买客满分
                $comdata=array();
                $comdata["app_code"]=$licences_goods["app_code"];
                $comdata["member_id"]=$order_list[0]["buyer_id"];
                $licences_companys=$this->getlicences_companys($comdata);

                $user_app_data['app_id']=2;//客满分
                if(!empty($licences_companys)){ //不为空是为续费
                    $companys_data=array();
                    $addtime=$licences_goods["licences_months"];
                    $times=strtotime($licences_companys["expire_date"]);
                    $companys_data["expire_date"]=date('Y-m-d',strtotime("+".$addtime."month",$times));
                    $companys_data["licences"]=$order_goods["goods_num"];
                    $companys_data["licences_month"]=$licences_goods["licences_months"];
                    $condition=array();
                    $condition["app_code"]="KMF";
                    $condition["member_id"]=$order_list[0]["buyer_id"];
                    $charge_data["expire_date"]=$companys_data["expire_date"];
                    $this->updatelicences_companys($companys_data,$condition);
                    //不是首次购买客满分  如果为过期续费 改状态2=>10   如果为普通续费 不改状态
                    $this->upd_user_app(array('uid'=>$user_app_data['uid'],'app_id'=>$user_app_data['app_id'],'status'=>10),array('uid'=>$user_app_data['uid'],'app_id'=>$user_app_data['app_id'],'status'=>2));
                }else{ //为空时为缴费
                    $companys_data=array();
                    $companys_data["app_code"]="KMF";
                    $companys_data["member_id"]=$order_list[0]["buyer_id"];
                    $companys_data["effect_date"]=date('Y-m-d',TIMESTAMP);
                    $companys_data["licences_month"]=$licences_goods["licences_months"];
                    $addtime=$licences_goods["licences_months"];
                    $companys_data["expire_date"]=date('Y-m-d',strtotime("+".$addtime."month",TIMESTAMP));
                    $companys_data["licences"]=$order_goods["goods_num"];
                    $companys_data["create_time"]=date('Y-m-d H:i:s',TIMESTAMP);
                    $charge_data["expire_date"]=$companys_data["expire_date"];
                    $this->addlicences_companys($companys_data);
                    //首次购买客满分 状态为1已购未装
                    $user_app_data['status']=1;
                    $this->add_user_app($user_app_data);
                }
            }else if($licences_goods["app_code"]=="ZG"){ //增购
                $comdata=array();
                $comdata["app_code"]="KMF";
                $comdata["member_id"]=$order_list[0]["buyer_id"];
                $licences_companys=$this->getlicences_companys($comdata);
                $companys_data=array();
                $licences=$licences_companys["licences"];
                $companys_data["licences"]=$licences+$order_goods["goods_num"];
                $condition=array();
                $condition["app_code"]="KMF";
                $condition["member_id"]=$order_list[0]["buyer_id"];
                $charge_data["expire_date"]=$licences_companys["expire_date"];
                $this->updatelicences_companys($companys_data,$condition);

            }elseif($licences_goods["app_code"]=="DPZS"){//店铺指数
                $comdata=array();
                $comdata["app_code"]="DPZS";
                $comdata["member_id"]=$order_list[0]["buyer_id"];
                $licences_companys=$this->getlicences_companys($comdata);

                $user_app_data['app_id']=1;//店铺指数
                if(!empty($licences_companys)){ //不为空是为续费
                    $companys_data=array();
                    $addtime=$licences_goods["licences_months"];
                    $times=strtotime($licences_companys["expire_date"]);
                    $companys_data["expire_date"]=date('Y-m-d',strtotime("+".$addtime."month",$times));
                    $condition=array();
                    $condition["app_code"]="DPZS";
                    $condition["member_id"]=$order_list[0]["buyer_id"];
                    $charge_data["expire_date"]=$companys_data["expire_date"];
                    $this->updatelicences_companys($companys_data,$condition);
                    //不是首次购买店铺指数  如果为过期续费 改状态2=》10   如果为普通续费 不改状态
                    $this->upd_user_app(array('uid'=>$user_app_data['uid'],'app_id'=>$user_app_data['app_id'],'status'=>10),array('uid'=>$user_app_data['uid'],'app_id'=>$user_app_data['app_id'],'status'=>2));
                }else{ //为空时为缴费
                    $companys_data=array();
                    $companys_data["app_code"]="DPZS";
                    $companys_data["member_id"]=$order_list[0]["buyer_id"];
                    $companys_data["effect_date"]=date('Y-m-d',TIMESTAMP);
                    $addtime=$licences_goods["licences_months"];
                    $companys_data["expire_date"]=date('Y-m-d',strtotime("+".$addtime."month",TIMESTAMP));
                    //   $companys_data["licences"]=$order_goods["goods_num"];
                    $companys_data["create_time"]=date('Y-m-d H:i:s',TIMESTAMP);
                    $charge_data["expire_date"]=$companys_data["expire_date"];
                    $this->addlicences_companys($companys_data);
                    //首次购买 店铺指数
                    $user_app_data['status']=1;
                    $this->add_user_app($user_app_data);
                }
            }elseif($licences_goods["app_code"]=="MRJ"){//每人计
                $comdata=array();
                $comdata["app_code"]="MRJ";
                $comdata["member_id"]=$order_list[0]["buyer_id"];
                $licences_companys=$this->getlicences_companys($comdata);
                if(!empty($licences_companys)){ //不为空是为续费
                    $companys_data=array();
                    $addtime=$licences_goods["licences_months"];
                    $times=strtotime($licences_companys["expire_date"]);
                    $companys_data["expire_date"]=date('Y-m-d',strtotime("+".$addtime."month",$times));
                    $condition=array();
                    $condition["app_code"]="MRJ";
                    $condition["member_id"]=$order_list[0]["buyer_id"];
                    $charge_data["expire_date"]=$companys_data["expire_date"];
                    $this->updatelicences_companys($companys_data,$condition);
                }else { //为空时为缴费
                    $companys_data = array();
                    $companys_data["app_code"] = "MRJ";
                    $companys_data["member_id"] = $order_list[0]["buyer_id"];
                    $companys_data["effect_date"] = date('Y-m-d', TIMESTAMP);
                    $addtime = $licences_goods["licences_months"];
                    $companys_data["expire_date"] = date('Y-m-d', strtotime("+" . $addtime . "month", TIMESTAMP));
                    $companys_data["create_time"] = date('Y-m-d H:i:s', TIMESTAMP);
                    $charge_data["expire_date"]=$companys_data["expire_date"];
                    $this->addlicences_companys($companys_data);
                }
            }elseif($licences_goods["app_code"]=="THFW") {//通话服务

                $comdata=array();
                $comdata["app_code"]="THFW";
                $comdata["member_id"]=$order_list[0]["buyer_id"];
                $licences_companys=$this->getlicences_companys($comdata);
                if(!empty($licences_companys)){ //不为空是为续费
                    $companys_data=array();
                    $licences_month=$licences_companys["licences_month"];
                    $companys_data["licences_month"]=$licences_month+$licences_goods["licences_months"];
                    $condition=array();
                    $condition["app_code"]="THFW";
                    $condition["member_id"]=$order_list[0]["buyer_id"];
                    $charge_data["expire_date"]=$licences_companys["expire_date"];
                    $this->updatelicences_companys($companys_data,$condition);
                }else { //为空时为缴费
                    $companys_data = array();
                    $companys_data["app_code"] = "THFW";
                    $companys_data["member_id"] = $order_list[0]["buyer_id"];
                    $companys_data["effect_date"] = date('Y-m-d', TIMESTAMP);
                    $companys_data["expire_date"] = date('Y-m-d', strtotime("+" . '12' . "month", TIMESTAMP));
                    $companys_data["create_time"] = date('Y-m-d H:i:s', TIMESTAMP);
                    $companys_data["licences"]=1;
                    $companys_data["licences_month"]=$licences_goods["licences_months"];
                    $charge_data["expire_date"]=$companys_data["expire_date"];
                    $this->addlicences_companys($companys_data);
                }
                /* 购买通话服务后修改剩余分钟时间*/
                $url='http://crm.ipvp.cn/YtxApp/YtxApi/InsertVoiceConfig';
                $params=array();
                $params['memberId']=$order_list[0]["buyer_id"];
                $params['licencesMonth']=$licences_goods["licences_months"];
                $res_data=$this->request_post($url,$params);//模拟post提交
                if(!$res_data){
                    throw new Exception('update fail');
                }
            }
            $chargeremark=serialize($charge_data);
            $charge_remark=array();
            $charge_remark['charge_remark']=$chargeremark;
            $update = $model_order->editOrder($charge_remark,array('pay_sn'=>$order_list[0]['pay_sn']));//更新订单的扩展信息
            $model_order->commit();
        }catch(Exception $e){
            $model_order->rollback();
            return callback(false,$e->getMessage());
        }
        return callback(true,'操作成功');
    }

    /*
     * 根据商品的goods_id获得对应的缴费商品
     * */
    public function getlicences_goods($goods_id){
        return $this->table("licences_goods")->where(array("goods_id"=>$goods_id))->find();
    }

    /*
     * 写入licences_log表
     * */
    public function  addlicences_paylog($paylog_log){

        $is= $this->table('licences_paylog')->insert($paylog_log);
        return $is;
    }

    /*
     * 获得客户的购买情况
     * */
    public function getlicences_companys($data){
        return $this->table("licences_companys")->where($data)->find();
    }

    /*
     * 将公司购买情况写入到数据库
     * */
    public function addlicences_companys($data){
        return $this->table("licences_companys")->insert($data);
    }

    /*
     *更新公司的购买情况
     * */
    public function  updatelicences_companys($data,$condition){
        return $this->table("licences_companys")->where($condition)->update($data);
    }

    /*
     * 获取客户付费信息
     * */
    public function getlicences_info($data){
        return $this->table("licences_companys")->where($data)->select();
    }

    /*
     * 获取客户最近一次购买记录信息
     * */
    public  function getlicences_paylog($data){
        return $this->table("licences_paylog")->where($data)->order("id desc")->find();

    }
    /*
     * 更新客户user_app表的状态
     * */
    public  function upd_user_app($data,$condition){
        //如果是续费不改状态   如果是过期续费状态改为已购买已安装
        //conditin=array(uid,app_id,status=2)  $data=array(uid,app_id,status=10)
        return $this->table("user_app")->where($condition)->update($data);
    }
    /*
     * 首次购买添加user_app记录
     * */
    public  function add_user_app($data){
        //data=array(uid,app_id,status)
        return $this->table("user_app")->insert($data);

    }

    /**
     * 模拟post方式url请求
     */
    private function request_post($url='',$params=array()){
        if(empty($url) || empty($params)){
            return false;
        }
        $o='';
        foreach ($params as $k =>$v){
            $o.="$k=".urlencode($v)."&";
        }
        $params=substr($o,0,-1);

        $postUrl = $url;
        $curlPost=$params;
        $ch=curl_init();//初始化curl
        curl_setopt($ch,CURLOPT_URL,$postUrl);//抓取指定网页
        curl_setopt($ch,CURL_HEADER,0);//设置header
        curl_setopt($ch,CURL_REYURNTRANSFER,1);//要求结果为字符串且输出到屏幕上
        curl_setopt($ch,CURL_POST,1);//post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $curlPost);
        $data=curl_exec($ch);//运行curl
        curl_close($ch);
        return $data;
    }
}
?>