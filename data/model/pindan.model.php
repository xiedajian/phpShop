<?php
class pindanModel extends Model{
	public function __construct(){
		parent::__construct('pindan');
	}
	
	public function createPindan($pindan){
		$pindan_id=$this->table("pindan")->insert($pindan);
		if($pindan_id){
			Model("goods")->editGoodsCommonById(array("pindan_id"=>$pindan_id),$pindan["goods_commonid"]);
		}
		return $pindan_id;
	}
	
	public  function editPindan($condition,$pindan){
		return $this->table("pindan")->where($condition)->update($pindan);
	}
	
	public function getPindanInfo($condition,$field="*"){
		return $this->table("pindan")->field($field)->where($condition)->find();
	}
	
	
	public  function addPindanRebates($data){
		return $this->table("pindan_rebates")->insert($data);
	}
	
	public  function getPindanRebatesList($condition,$field = '*',$order = '',$page = 0){
		return $this->table("pindan_rebates")->field($field)->where($condition)->order($order)->page($page)->select();
	}
	
	
	//拼单返利记录
	public function  pindan_rebates($order_list){
		$order_ids=array();
		$orderid_2_order=array();
		foreach ($order_list as $order){
			$order_ids[]=$order['order_id'];
			$orderid_2_order[$order['order_id']]=$order;
		}
		$model_order=Model("order");
		$model_goods=Model("goods");
		$order_goods=$model_order->getOrderGoodsList(array('order_id'=>array("in",$order_ids)),"order_id,goods_id,goods_num");
		$goods_ids=array();
		$goods_2_order=array();
		foreach ($order_goods as $goods){
			$goods_ids[]=$goods["goods_id"];
			$goods_2_order[$goods["goods_id"]]=$goods;
		}
		$goods_list=$model_goods->getGoodsList(array("goods_id"=>array("in",$goods_ids)),"goods_id,goods_name,goods_commonid,pindan_goods_rebates");
		$commonids=array();
		foreach ($goods_list as $i=>$goods){
			$commonids[]=$goods["goods_commonid"];
			$o_id=$goods_2_order[$goods["goods_id"]]["order_id"];
			$goods_list[$i]["order_id"]=$o_id;
			$goods_list[$i]["goods_num"]=$goods_2_order[$goods["goods_id"]]["goods_num"];
			$goods_list[$i]["buyer_id"]=$orderid_2_order[$o_id]["buyer_id"];
			$goods_list[$i]["buyer_name"]=$orderid_2_order[$o_id]["buyer_name"];
			$goods_list[$i]["member_org_id"]=$orderid_2_order[$o_id]["member_org_id"];
			$goods_list[$i]["member_org_name"]=$orderid_2_order[$o_id]["member_org_name"];
			$goods_list[$i]["order_sn"]=$orderid_2_order[$o_id]["order_sn"];
		}
		$common_list=$model_goods->getGoodsCommonList(array("goods_commonid"=>array("in",array_unique($commonids))),"goods_commonid,pindan_id");
		$common_2_pindan=array();
		foreach ($common_list as $val){
			$common_2_pindan[$val["goods_commonid"]]=$val["pindan_id"];
		}
		$pindan_rebates_list=array();
		$pindan_all_nums=array();
		foreach ($goods_list as $v){
			$pindan_id=$common_2_pindan[$v['goods_commonid']];
			if($pindan_id ){//返利金额 大于0  记录 && $v["pindan_goods_rebates"]>0
				$pindan_rebates=array();
				$pindan_rebates["pindan_id"]=$common_2_pindan[$v['goods_commonid']];
				$pindan_rebates["order_id"]=$v["order_id"];
				$pindan_rebates["order_sn"]=$v["order_sn"];
				$pindan_rebates["member_id"]=$v["buyer_id"];
				$pindan_rebates["member_name"]=$v["buyer_name"];
				$pindan_rebates["member_org_id"]=$v["member_org_id"];
				$pindan_rebates["member_org_name"]=$v["member_org_name"];
				$pindan_rebates["goods_id"]=$v['goods_id'];
				$pindan_rebates["goods_name"]=$v['goods_name'];
				$pindan_rebates["goods_num"]=$v['goods_num'];
				$pindan_rebates["goods_rebates"]=$v["pindan_goods_rebates"];
				$pindan_rebates["total_rebates"]=0;
				$pindan_rebates["create_time"]=TIMESTAMP;
				$pindan_rebates_list[]=$pindan_rebates;
				if($pindan_all_nums[$pindan_rebates["pindan_id"]]){
					$pindan_all_nums[$pindan_rebates["pindan_id"]]+=$v['goods_num'];
				}else{
					$pindan_all_nums[$pindan_rebates["pindan_id"]]=$v['goods_num'];
				}
			}
		}
		//添加拼单返利
		if(!empty($pindan_rebates_list)){
			$this->table("pindan_rebates")->insertAll($pindan_rebates_list);
		}
        //修改拼单当前量
        if(!empty($pindan_all_nums)){
	        foreach ($pindan_all_nums as $pid=>$num){
	        	$this->editPindan(array("pindan_id"=>$pid),array("current_num"=>array('exp',"current_num+".$num)));
	        }
        }

	}
	
	//拼单返利  操作根据订单ID(前提该订单已完成)
	public function  pindan_rebates_do_by_orderid($order_id){
		$rebates_list=$this->table("pindan_rebates")->field("pindan_id")->where(array("order_id"=>$order_id,"rebates_state"=>0))->select();
		if(empty($rebates_list)){
			return;
		}
		$pindan_ids=array();
		foreach ($rebates_list as $val){
			$pindan_ids[]=$val["pindan_id"];
		}
		//获取拼单成功的拼单ＩＤ
		$rebates_pindanids=array();
		$pindan_array=$this->table("pindan")->field("pindan_id")->where(array("pindan_id"=>array("in",$pindan_ids),"pindan_state"=>1))->select();
		if(empty($pindan_array)){
			return;
		}
		foreach ($pindan_array as $val){
			$rebates_pindanids[]=$val["pindan_id"];
		}
		$rebates_list_do=$this->table("pindan_rebates")->field("*")->where(array("order_id"=>$order_id,"pindan_id"=>array("in",$rebates_pindanids),"rebates_state"=>0))->select();
		$model_pd = Model('predeposit');
		foreach ($rebates_list_do as $rebates){
			$data_pd = array();
			$data_pd['member_id'] = $rebates['member_id'];
			$data_pd['member_name'] = $rebates['member_name'];
			$data_pd['amount'] = $rebates["goods_rebates"]*$rebates["goods_num"];
			$data_pd['order_sn'] = $rebates['order_sn'];
			$data_pd['goods_name']=$rebates["goods_name"];
			$model_pd->changePd('pindan_rebates',$data_pd);
		}
		$this->table("pindan_rebates")->where(array("order_id"=>$order_id,"pindan_id"=>array("in",$rebates_pindanids),"rebates_state"=>0))->update(array("rebates_state"=>1,"rebates_time"=>TIMESTAMP,"total_rebates"=>array("exp","goods_rebates*goods_num")));
	}
	//拼单返利  操作根据拼单ID(前提该拼单已成功)
	public function pindan_rebates_do_by_pindanid($pindan_id){
		$rebates_list=$this->table("pindan_rebates")->field("order_id")->where(array("pindan_id"=>$pindan_id,"rebates_state"=>0))->select();
		if(empty($rebates_list)){
			return;
		}
		$order_ids=array();
		foreach ($rebates_list as $val){
			$order_ids[]=$val["order_id"];
		}
		//获取交易成功的订单ID
		$rebates_order_ids=array();
		$rebates_order_array=$this->table("order")->field("order_id")->where(array("order_id"=>array("in",$order_ids),"order_state"=>ORDER_STATE_SUCCESS))->select();
		if(empty($rebates_order_array)){
			return;
		}
		foreach ($rebates_order_array as $val){
			$rebates_order_ids[]=$val["order_id"];
		}
		$rebates_list_do=$this->table("pindan_rebates")->field("*")->where(array("pindan_id"=>$pindan_id,"order_id"=>array("in",$rebates_order_ids),"rebates_state"=>0))->select();
		$model_pd = Model('predeposit');
		foreach ($rebates_list_do as $rebates){
			$data_pd = array();
			$data_pd['member_id'] = $rebates['member_id'];
			$data_pd['member_name'] = $rebates['member_name'];
			$data_pd['amount'] = $rebates["goods_rebates"]*$rebates["goods_num"];
			$data_pd['order_sn'] = $rebates['order_sn'];
			$data_pd['goods_name']=$rebates["goods_name"];
			$model_pd->changePd('pindan_rebates',$data_pd);
		}
		$this->table("pindan_rebates")->where(array("pindan_id"=>$pindan_id,"order_id"=>array("in",$rebates_order_ids),"rebates_state"=>0))->update(array("rebates_state"=>1,"rebates_time"=>TIMESTAMP,"total_rebates"=>array("exp","goods_rebates*goods_num")));
	}
	
	//拼单  定时 任务
	public function crontab_pindan(){
		//获取所有正在拼单 的记录
		//拼单成功检测
		$succeess_pindan_list=$this->table("pindan")->field("*")->where(array("is_over"=>0,"pindan_state"=>0,"current_num"=>array("egt",array("exp","success_num"))))->select();
		foreach ($succeess_pindan_list as $pindan){
			//修改成功状态
			$this->editPindan(array("pindan_id"=>$pindan["pindan_id"]),array("pindan_state"=>1,"state_edittime"=>TIMESTAMP));
		    //拼单成功进行返现
		    $this->pindan_rebates_do_by_pindanid($pindan["pindan_id"]);
	    }
	    //拼单结束检测
	    $end_pindan_list=$this->table("pindan")->field("*")->where(array("is_over"=>0,"end_time"=>array("elt",TIMESTAMP)))->select();
		foreach ($end_pindan_list as $pindan){
		    $this->editPindan(array("pindan_id"=>$pindan["pindan_id"]),array("is_over"=>1,"over_time"=>TIMESTAMP));
			//获取拼单周期 拼单成功数
			$goods_common=$this->table("goods_common")->field("pindan_success_num,pindan_cycle")->where(array("goods_commonid"=>$pindan["goods_commonid"]))->find();
			if(!empty($goods_common)&&$goods_common["pindan_success_num"]>0){
				//开始新拼单
				$new_pindan=array();
				$new_pindan["goods_commonid"]=$pindan["goods_commonid"];
				$new_pindan["start_time"]=TIMESTAMP;
				$new_pindan["end_time"]=$new_pindan["start_time"]+$goods_common["pindan_cycle"]*60*60;
				$new_pindan["success_num"]=$goods_common["pindan_success_num"];
				$this->createPindan($new_pindan);
			}
		}
	}
}
