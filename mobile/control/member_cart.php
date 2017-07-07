<?php
/**
 * 我的购物车
 *
 *
 *
 *
 
 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class member_cartControl extends mobileMemberControl {

	public function __construct() {
		parent::__construct();
	}

    /**
     * 购物车列表
     */
    public function cart_listOp() {
        $model_cart = Model('cart');

        $condition = array('buyer_id' => $this->member_info['member_id']);
        $cart_list	= $model_cart->listCart('db', $condition);
        $sum = 0;
        foreach ($cart_list as $key => $value) {
            $cart_list[$key]['goods_image_url'] = cthumb($value['goods_image'],"60",$value['store_id']);
            $cart_list[$key]['goods_sum'] = ncPriceFormat($value['goods_price'] * $value['goods_num']);
            $sum += $cart_list[$key]['goods_sum'];
        }

        output_data(array('cart_list' => $cart_list, 'sum' => ncPriceFormat($sum)));
    }

    public function ipvp_cart_listOp(){
    	$model_cart = Model('cart');
    	$condition = array('buyer_id' => $this->member_info['member_id']);
    	$cart_list	= $model_cart->listCart('db', $condition);
    	$goods_ids=array();
    	foreach ($cart_list as $key=>$value){
    		$goods_ids[]=$value["goods_id"];
    		$cart_list[$key]['goods_image_url'] = cthumb($value['goods_image'],"240", $value['store_id']);
    	}
    	//查询商品
    	$model_goods=Model("goods");
    	$goods_list=$model_goods->getGoodsOnlineList(array("goods_id"=>array("in",$goods_ids)),"goods_id,goods_commonid,goods_spec,goods_moq");
    	$goods_list_key_val=array();
    	foreach ($goods_list as $v){
    		$goods_list_key_val[$v['goods_id']]=$v;
    	}
    	$off_down_list=array();
    	$on_line_cart_list=array();
    	foreach ($cart_list as $key=>$value){
    		if($goods_list_key_val[$value["goods_id"]]){
    			$value["goods_commonid"]=$goods_list_key_val[$value["goods_id"]]["goods_commonid"];
    			$s_array = unserialize($goods_list_key_val[$value["goods_id"]]["goods_spec"]);
    			$tmp_spec_val_array=array();
    			if(!empty($s_array)&& is_array($s_array)){
    				foreach ($s_array as $k => $v) {
    					$tmp_spec_val_array[]=$v;
    				}
    				$value["goods_spec_name"]=implode(' ',$tmp_spec_val_array);
    			}else{
    				$value["goods_spec_name"]=$value["goods_name"];
    			}
    			$value["goods_moq"]=$goods_list_key_val[$value["goods_id"]]["goods_moq"];
    			$on_line_cart_list[]=$value;
    		}else{
    			$off_down_list[]=$value;
    		}
    	}
    	$goods_list_by_commonid=array();
    	foreach ($on_line_cart_list as $key=>$value){
    		$goods_list_by_commonid[$value["goods_commonid"]]["cart_list"][]=$value;
    	}
    	$common_ids=array_keys($goods_list_by_commonid);
    	$common_goods_array=$model_goods->getGoodsCommonList(array("goods_commonid"=>array("in",$common_ids)),"goods_commonid,goods_name,goods_image",0,"");
    	$common_goods_array_k_v=array();
    	foreach ($common_goods_array  as $v){
    		$common_goods_array_k_v[$v["goods_commonid"]]=$v;
    	}
    	foreach ($goods_list_by_commonid as $key=>$value){
    		$goods_list_by_commonid[$key]["common_name"]=$common_goods_array_k_v[$key]["goods_name"];
    		$goods_list_by_commonid[$key]["common_image"]=cthumb($common_goods_array_k_v[$key]["goods_image"],"240",$value["cart_list"][0]["store_id"]);
    		$goods_list_by_commonid[$key]["store_id"]=$value["cart_list"][0]["store_id"];
    	}
    	$goods_list_by_storeid=array();
    	foreach ($goods_list_by_commonid as $key=>$value){
    		$goods_list_by_storeid[$value["store_id"]]["common_list"][]=$value;
    	}
    	foreach ($goods_list_by_storeid as $key=>$value){
    		$goods_list_by_storeid[$key]["store_name"]=$value["common_list"][0]["cart_list"][0]["store_name"];
    	}
    	output_data(array('online_list' => $goods_list_by_storeid, 'offline_list' =>$off_down_list));
    }
    /**
     * 购物车添加
     */
    public function cart_addOp() {
    	$model_cart	= Model('cart');
        $goods_id_array=explode(',',$_POST['goods_id']);
        $quantity_array=explode(',',$_POST['quantity']);
        $remark_text="";
        if($_POST['commonid']){
        	if(!$model_cart->common_checkcart($_POST['commonid'],$goods_id_array,$this->member_info['member_id'])){
        		$remark_text="该商品您曾加入了其他规格";
        	}
        }
        foreach($goods_id_array as $i=>$goods_id){
        //$goods_id = intval($_POST['goods_id']);
        $quantity = intval($quantity_array[$i]);
        if($quantity <= 0) {
            output_error('参数错误');
        }

        $model_goods = Model('goods');
        
        $logic_buy_1 = Logic('buy_1');

        $goods_info = $model_goods->getGoodsOnlineInfoAndPromotionById($goods_id);

        //验证是否可以购买
		if(empty($goods_info)) {
            output_error('商品已下架或不存在');
		}

		//抢购
		$logic_buy_1->getGroupbuyInfo($goods_info);
		
		//限时折扣
		$logic_buy_1->getXianshiInfo($goods_info,$quantity);

        //VIP会员
        $logic_buy_1->getVipInfo($goods_info);

        if ($goods_info['store_id'] == $this->member_info['store_id']) {
            output_error('不能购买自己发布的商品');
		}
		if(intval($goods_info['goods_storage']) < 1 || intval($goods_info['goods_storage']) < $quantity) {
            output_error('库存不足');
		}

        $param = array();
        $param['buyer_id']	= $this->member_info['member_id'];
        $param['store_id']	= $goods_info['store_id'];
        $param['goods_id']	= $goods_info['goods_id'];
        $param['goods_name'] = $goods_info['goods_name'];
        $param['goods_price'] = $goods_info['goods_price'];
        $param['goods_image'] = $goods_info['goods_image'];
			$param['goods_tag'] = $goods_info['goods_tag'];
        $param['store_name'] = $goods_info['store_name'];
        $result = $model_cart->addCart($param, 'db', $quantity);
        }
        if($result) {
            output_data(array("success"=>1,"text"=>$remark_text));
        } else {
            output_error('添加失败');
        }
    }

    /**
     * 购物车删除
     */
    public function cart_delOp() {
        $cart_id = intval($_POST['cart_id']);

        $model_cart = Model('cart');

        if($cart_id > 0) {
            $condition = array();
            $condition['buyer_id'] = $this->member_info['member_id'];
            $condition['cart_id'] = $cart_id;

            $model_cart->delCart('db', $condition);
        }

        output_data('1');
    }
    public function cart_any_delOp() {
    	$cart_id =$_POST['cart_id'];
    	$cart_ids_array=explode(",",$cart_id);
    	if(empty($cart_ids_array)||!is_array($cart_ids_array)){
    		output_error('参数错误');
    	}
    	$condition = array();
    	$condition['buyer_id'] = $this->member_info['member_id'];
    	$condition['cart_id'] = array("in",$cart_ids_array);
    	Model('cart')->delCart('db', $condition);
    	output_data('1');
    }
    /**
     * 更新购物车购买数量
     */
    public function cart_edit_quantityOp() {
		$cart_id = intval(abs($_POST['cart_id']));
		$quantity = intval(abs($_POST['quantity']));
		if(empty($cart_id) || empty($quantity)) {
            output_error('参数错误');
		}

		$model_cart = Model('cart');

        $cart_info = $model_cart->getCartInfo(array('cart_id'=>$cart_id, 'buyer_id' => $this->member_info['member_id']));

        //检查是否为本人购物车
        if($cart_info['buyer_id'] != $this->member_info['member_id']) {
            output_error('参数错误');
        }

        //检查库存是否充足
        
        $check_result=$this->_check_goods_storage($cart_info, $quantity, $this->member_info['member_id']); 
        if(!empty($check_result)) {
            output_error($check_result);
        }

		$data = array();
        $data['goods_num'] = $quantity;
        $update = $model_cart->editCart($data, array('cart_id'=>$cart_id));
		if ($update) {
		    $return = array();
            $return['quantity'] = $quantity;
			$return['goods_price'] = ncPriceFormat($cart_info['goods_price']);
			$return['total_price'] = ncPriceFormat($cart_info['goods_price'] * $quantity);
            output_data($return);
		} else {
            output_error('修改失败');
		}
    }

    /**
     * 检查库存是否充足
     */
    private function _check_goods_storage($cart_info, $quantity, $member_id) {
		$model_goods= Model('goods');
        $model_bl = Model('p_bundling');
        $logic_buy_1 = Logic('buy_1');

		if ($cart_info['bl_id'] == '0') {
            //普通商品
		    $goods_info	= $model_goods->getGoodsOnlineInfoAndPromotionById($cart_info['goods_id']);

		    //抢购
		    $logic_buy_1->getGroupbuyInfo($goods_info);

		    //限时折扣
		    $logic_buy_1->getXianshiInfo($goods_info,$quantity);
 
		    // $quantity = $cart_info['goods_num'];
		    if(intval($goods_info['goods_storage']) < $quantity) {
                      return  "库存不足";
		    }
                    if(intval($goods_info['goods_moq'])>$quantity){
                       return "不能少于".$goods_info['goods_moq']."最小起订量";
                    }
		} else {
		    //优惠套装商品
		    $bl_goods_list = $model_bl->getBundlingGoodsList(array('bl_id' => $cart_info['bl_id']));
		    $goods_id_array = array();
		    foreach ($bl_goods_list as $goods) {
		        $goods_id_array[] = $goods['goods_id'];
		    }
		    $bl_goods_list = $model_goods->getGoodsOnlineListAndPromotionByIdArray($goods_id_array);

		    //如果有商品库存不足，更新购买数量到目前最大库存
		    foreach ($bl_goods_list as $goods_info) {
		        if (intval($goods_info['goods_storage']) < $quantity) {
                             return "库存不足";
		        }
		    }
		}
        return "";
    }

}
