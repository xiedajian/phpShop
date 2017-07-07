<?php
/**
 * 订单管理
 *
 *
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');
class orderModel extends Model {

    /**
     * 取单条订单信息
     *
     * @param unknown_type $condition
     * @param array $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return unknown
     */
    public function getOrderInfo($condition = array(), $extend = array(), $fields = '*', $order = '',$group = '') {
        $order_info = $this->table('order')->field($fields)->where($condition)->group($group)->order($order)->find();
        if (empty($order_info)) {
            return array();
        }
        if (isset($order_info['order_state'])) {
            $order_info['state_desc'] = orderState($order_info);
        }
        if (isset($order_info['payment_code'])) {
            $order_info['payment_name'] = orderPaymentName($order_info['payment_code']);
        }

        //追加返回订单扩展表信息
        if (in_array('order_common',$extend)) {
            $order_info['extend_order_common'] = $this->getOrderCommonInfo(array('order_id'=>$order_info['order_id']));
            $order_info['extend_order_common']['reciver_info'] = unserialize($order_info['extend_order_common']['reciver_info']);
            $order_info['extend_order_common']['invoice_info'] = unserialize($order_info['extend_order_common']['invoice_info']);
        }

        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $order_info['extend_store'] = Model('store')->getStoreInfo(array('store_id'=>$order_info['store_id']));
        }

        //返回买家信息
        if (in_array('member',$extend)) {
            $order_info['extend_member'] = Model('member')->getMemberInfoByID($order_info['buyer_id']);
        }

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            //取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id'=>$order_info['order_id']));
            $order_info['extend_order_goods'] = $order_goods_list;
        }

        return $order_info;
    }

    public function getOrderCommonInfo($condition = array(), $field = '*') {
        return $this->table('order_common')->where($condition)->field($field)->find();
    }

    public function getOrderPayInfo($condition = array(), $master = false) {
        return $this->table('order_pay')->where($condition)->master($master)->find();
    }

    /**
     * 取得支付单列表
     *
     * @param unknown_type $condition
     * @param unknown_type $pagesize
     * @param unknown_type $filed
     * @param unknown_type $order
     * @param string $key 以哪个字段作为下标,这里一般指pay_id
     * @return unknown
     */
    public function getOrderPayList($condition, $pagesize = '', $filed = '*', $order = '', $key = '') {
        return $this->table('order_pay')->field($filed)->where($condition)->order($order)->page($pagesize)->key($key)->select();
    }

    /**
     * 取得订单列表(未被删除)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getNormalOrderList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array()){
        $condition['delete_state'] = 0;
        return $this->getOrderList($condition, $pagesize, $field, $order, $limit, $extend);
    }

    /**
     * 取得订单列表(所有)
     * @param unknown $condition
     * @param string $pagesize
     * @param string $field
     * @param string $order
     * @param string $limit
     * @param unknown $extend 追加返回那些表的信息,如array('order_common','order_goods','store')
     * @return Ambigous <multitype:boolean Ambigous <string, mixed> , unknown>
     */
    public function getOrderList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array(), $master = false){
        $list = $this->table('order')->field($field)->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();
        if (empty($list)) return array();
        $order_list = array();
        foreach ($list as $order) {
            if (isset($order['order_state'])) {
                $order['state_desc'] = orderState($order);
            }
            if (isset($order['payment_code'])) {
                $order['payment_name'] = orderPaymentName($order['payment_code']);
            }
        	if (!empty($extend)) $order_list[$order['order_id']] = $order;
        }
        if (empty($order_list)) $order_list = $list;

        //追加返回订单扩展表信息
        if (in_array('order_common',$extend)) {
            $order_common_list = $this->getOrderCommonList(array('order_id'=>array('in',array_keys($order_list))));
            foreach ($order_common_list as $value) {
                $order_list[$value['order_id']]['extend_order_common'] = $value;
                $order_list[$value['order_id']]['extend_order_common']['reciver_info'] = @unserialize($value['reciver_info']);
                $order_list[$value['order_id']]['extend_order_common']['invoice_info'] = @unserialize($value['invoice_info']);
            }
        }
        //追加返回店铺信息
        if (in_array('store',$extend)) {
            $store_id_array = array();
            foreach ($order_list as $value) {
            	if (!in_array($value['store_id'],$store_id_array)) $store_id_array[] = $value['store_id'];
            }
            $store_list = Model('store')->getStoreList(array('store_id'=>array('in',$store_id_array)));
            $store_new_list = array();
            foreach ($store_list as $store) {
            	$store_new_list[$store['store_id']] = $store;
            }
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_store'] = $store_new_list[$order['store_id']];
            }
        }

        //追加返回买家信息
        if (in_array('member',$extend)) {
            foreach ($order_list as $order_id => $order) {
                $order_list[$order_id]['extend_member'] = Model('member')->getMemberInfoByID($order['buyer_id']);
            }
        }

        //追加返回商品信息
        if (in_array('order_goods',$extend)) {
            //取商品列表
            $order_goods_list = $this->getOrderGoodsList(array('order_id'=>array('in',array_keys($order_list))));
            if (!empty($order_goods_list)) {
                foreach ($order_goods_list as $value) {
                    $order_list[$value['order_id']]['extend_order_goods'][] = $value;
                }
            } else {
                $order_list[$value['order_id']]['extend_order_goods'] = array();
            }
        }

        return $order_list;
    }
    //支持members的查询条件
    public function getOrderListm($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = '', $extend = array(), $master = false){
    	$list = $this->table('order,member')->field($field)->on('order.buyer_id = member.member_id')->where($condition)->page($pagesize)->order($order)->limit($limit)->master($master)->select();
    	if (empty($list)) return array();
    	$order_list = array();
    	foreach ($list as $order) {
    		if (isset($order['order_state'])) {
    			$order['state_desc'] = orderState($order);
    		}
    		if (isset($order['payment_code'])) {
    			$order['payment_name'] = orderPaymentName($order['payment_code']);
    		}
    		if (!empty($extend)) $order_list[$order['order_id']] = $order;
    	}
    	if (empty($order_list)) $order_list = $list;
    
    	//追加返回订单扩展表信息
    	if (in_array('order_common',$extend)) {
    		$order_common_list = $this->getOrderCommonList(array('order_id'=>array('in',array_keys($order_list))));
    		foreach ($order_common_list as $value) {
    			$order_list[$value['order_id']]['extend_order_common'] = $value;
    			$order_list[$value['order_id']]['extend_order_common']['reciver_info'] = @unserialize($value['reciver_info']);
    			$order_list[$value['order_id']]['extend_order_common']['invoice_info'] = @unserialize($value['invoice_info']);
    		}
    	}
    	//追加返回店铺信息
    	if (in_array('store',$extend)) {
    		$store_id_array = array();
    		foreach ($order_list as $value) {
    			if (!in_array($value['store_id'],$store_id_array)) $store_id_array[] = $value['store_id'];
    		}
    		$store_list = Model('store')->getStoreList(array('store_id'=>array('in',$store_id_array)));
    		$store_new_list = array();
    		foreach ($store_list as $store) {
    			$store_new_list[$store['store_id']] = $store;
    		}
    		foreach ($order_list as $order_id => $order) {
    			$order_list[$order_id]['extend_store'] = $store_new_list[$order['store_id']];
    		}
    	}
    
    	//追加返回买家信息
    	if (in_array('member',$extend)) {
    		foreach ($order_list as $order_id => $order) {
    			$order_list[$order_id]['extend_member'] = Model('member')->getMemberInfoByID($order['buyer_id']);
    		}
    	}
    
    	//追加返回商品信息
    	if (in_array('order_goods',$extend)) {
    		//取商品列表
    		$order_goods_list = $this->getOrderGoodsList(array('order_id'=>array('in',array_keys($order_list))));
    		if (!empty($order_goods_list)) {
    			foreach ($order_goods_list as $value) {
    				$order_list[$value['order_id']]['extend_order_goods'][] = $value;
    			}
    		} else {
    			$order_list[$value['order_id']]['extend_order_goods'] = array();
    		}
    	}
    
    	return $order_list;
    }

    /**
     * 取得(买/卖家)订单某个数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id   买家ID、店铺ID
     * @param string $key 允许传入  NewCount、PayCount、SendCount、EvalCount，分别取相应数量缓存，只许传入一个
     * @return array
     */
    public function getOrderCountCache($type, $id, $key) {
        if (!C('cache_open')) return array();
        $type = 'ordercount'.$type;
        $ins = Cache::getInstance('cacheredis');
        $order_info = $ins->hget($id,$type,$key);
        return !is_array($order_info) ? array($key => $order_info) : $order_info;
    }

    /**
     * 设置(买/卖家)订单某个数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id 买家ID、店铺ID
     * @param array $data
     */
    public function editOrderCountCache($type, $id, $data) {
        if (!C('cache_open') || empty($type) || !intval($id) || !is_array($data)) return ;
        $ins = Cache::getInstance('cacheredis');
        $type = 'ordercount'.$type;
        $ins->hset($id,$type,$data);
    }

    /**
     * 取得买卖家订单数量某个缓存
     * @param string $type $type 买/卖家标志，允许传入 buyer、store
     * @param int $id 买家ID、店铺ID
     * @param string $key 允许传入  NewCount、PayCount、SendCount、EvalCount，分别取相应数量缓存，只许传入一个
     * @return int
     */
    public function getOrderCountByID($type, $id, $key) {
        $cache_info = $this->getOrderCountCache($type, $id, $key);
        
        if (is_string($cache_info[$key])) {
            //从缓存中取得
            $count = $cache_info[$key];
        } else {
            //从数据库中取得
            $field = $type == 'buyer' ? 'buyer_id' : 'store_id';
            $condition = array($field => $id);
            $func = 'getOrderState'.$key;
            $count = $this->$func($condition);
            $this->editOrderCountCache($type,$id,array($key => $count));
        }
        return $count;
    }

    /**
     * 删除(买/卖家)订单全部数量缓存
     * @param string $type 买/卖家标志，允许传入 buyer、store
     * @param int $id   买家ID、店铺ID
     * @return bool
     */
    public function delOrderCountCache($type, $id) {
        if (!C('cache_open')) return true;
        $ins = Cache::getInstance('cacheredis');
        $type = 'ordercount'.$type;
        return $ins->hdel($id,$type);
    }

    /**
     * 待付款订单数量
     * @param unknown $condition
     */
    public function getOrderStateNewCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_NEW;
        return $this->getOrderCount($condition);
    }

    /**
     * 待发货订单数量
     * @param unknown $condition
     */
    public function getOrderStatePayCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_PAY;
        return $this->getOrderCount($condition);
    }

    /**
     * 待收货订单数量
     * @param unknown $condition
     */
    public function getOrderStateSendCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_SEND;
        return $this->getOrderCount($condition);
    }

    /**
     * 待评价订单数量
     * @param unknown $condition
     */
    public function getOrderStateEvalCount($condition = array()) {
        $condition['order_state'] = ORDER_STATE_SUCCESS;
        $condition['evaluation_state'] = 0;
        return $this->getOrderCount($condition);
    }

    /**
     * 交易中的订单数量
     * @param unknown $condition
     */
    public function getOrderStateTradeCount($condition = array()) {
        $condition['order_state'] = array(array('neq',ORDER_STATE_CANCEL),array('neq',ORDER_STATE_SUCCESS),'and');
        return $this->getOrderCount($condition);
    }
    /*退款中订单数量*/
    public function getOrderStateRefundCount($condition = array()){
        $condition['refund_state']= array('lt',3);
        $count = $this->table('refund_return')->field('distinct(order_id)')->where($condition)->select();
        return count($count);
    }

    /**
     * 取得订单数量
     * @param unknown $condition
     */
    public function getOrderCount($condition) {
        return $this->table('order')->where($condition)->count();
    }

    /**
     * 取得订单商品表详细信息
     * @param unknown $condition
     * @param string $fields
     * @param string $order
     */
    public function getOrderGoodsInfo($condition = array(), $fields = '*', $order = '') {
        return $this->table('order_goods')->where($condition)->field($fields)->order($order)->find();
    }

    /**
     * 取得订单商品表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     * @param string $page
     * @param string $order
     * @param string $group
     * @param string $key
     */
    public function getOrderGoodsList($condition = array(), $fields = '*', $limit = null, $page = null, $order = 'rec_id desc', $group = null, $key = null) {
        return $this->table('order_goods')->field($fields)->where($condition)->limit($limit)->order($order)->group($group)->key($key)->page($page)->select();
    }

    /**
     * 取得订单扩展表列表
     * @param unknown $condition
     * @param string $fields
     * @param string $limit
     */
    public function getOrderCommonList($condition = array(), $fields = '*', $order = '', $limit = null) {
        return $this->table('order_common')->field($fields)->where($condition)->order($order)->limit($limit)->select();
    }

    /**
     * 插入订单支付表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderPay($data) {
        return $this->table('order_pay')->insert($data);
    }

    /**
     * 插入订单表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrder($data) {
        $insert = $this->table('order')->insert($data);
        if ($insert) {
            //更新缓存
            QueueClient::push('delOrderCountCache',array('buyer_id'=>$data['buyer_id'],'store_id'=>$data['store_id']));
        }
        return $insert;
    }

    /**
     * 插入订单扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderCommon($data) {
        return $this->table('order_common')->insert($data);
    }

    /**
     * 插入订单扩展表信息
     * @param array $data
     * @return int 返回 insert_id
     */
    public function addOrderGoods($data) {
        return $this->table('order_goods')->insertAll($data);
    }

    /*
     * 插入单条扩展信息
     * */

    public function addOrderGoodsOne($data){
        return $this->table('order_goods')->insert($data);
    }
	/**
	 * 添加订单日志
	 */
	public function addOrderLog($data) {
	    $data['log_role'] = str_replace(array('buyer','seller','system','admin'),array('买家','商家','系统','管理员'), $data['log_role']);
	    $data['log_time'] = TIMESTAMP;
	    return $this->table('order_log')->insert($data);
	}

	/**
	 * 更改订单信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editOrder($data,$condition,$limit = '') {
		$update = $this->table('order')->where($condition)->limit($limit)->update($data);
		if ($update) {
		    //更新缓存
		    QueueClient::push('delOrderCountCache',$condition);
		}
		return $update;
	}

	/**
	 * 更改订单信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editOrderCommon($data,$condition) {
	    return $this->table('order_common')->where($condition)->update($data);
	}

	/**
	 * 更改订单信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editOrderGoods($data,$condition) {
	    return $this->table('order_goods')->where($condition)->update($data);
	}

	/**
	 * 更改订单支付信息
	 *
	 * @param unknown_type $data
	 * @param unknown_type $condition
	 */
	public function editOrderPay($data,$condition) {
		return $this->table('order_pay')->where($condition)->update($data);
	}

	/**
	 * 订单操作历史列表
	 * @param unknown $order_id
	 * @return Ambigous <multitype:, unknown>
	 */
    public function getOrderLogList($condition) {
        return $this->table('order_log')->where($condition)->select();
    }

    /**
     * 订单操作历史列表
     * @param unknown $condition
     * @param unknown $limit
     * @return Ambigous <multitype:, unknown>
     */
    public function getOrderLogListByPage($condition,$limit) {
        return $this->table('order_log')->where($condition)->limit($limit)->select();
    }

    /**
     * 获取退款/退货信息列表
     * @param unknown $condition
     * @param unknown $limit
     * @return Ambigous <multitype:, unknown>
     */
    public function getOrderRefundListByPage($condition,$limit)
    {
        return $this->table('refund_return')->where($condition)->limit($limit)->select();
    }

        /**
     * 取得单条订单操作记录
     * @param unknown $condition
     * @param string $order
     */
    public function getOrderLogInfo($condition = array(), $order = '') {
        return $this->table('order_log')->where($condition)->order($order)->find();
    }

    /**
     * 返回是否允许某些操作
     * @param unknown $operate
     * @param unknown $order_info
     */
    public function getOrderOperateState($operate,$order_info){

        if (!is_array($order_info) || empty($order_info)) return false;

        switch ($operate) {

            //买家取消订单
        	case 'buyer_cancel':
        	   $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
        	       ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
        	   break;

    	   //申请退款
    	   case 'refund_cancel':
    	       $state = $order_info['refund'] == 1 && !intval($order_info['lock_state']);
    	       break;

    	   //商家取消订单
    	   case 'store_cancel':
    	       $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
    	       ($order_info['payment_code'] == 'offline' &&
    	       in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND)));
    	       break;

           //平台取消订单
           case 'system_cancel':
               $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
               ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
               break;

           //平台收款
           case 'system_receive_pay':
               $state = $order_info['order_state'] == ORDER_STATE_NEW && $order_info['payment_code'] == 'online';
               break;

	       //买家投诉
	       case 'complain':
	           $state = in_array($order_info['order_state'],array(ORDER_STATE_PAY,ORDER_STATE_SEND)) ||
	               intval($order_info['finnshed_time']) > (TIMESTAMP - C('complain_time_limit'));
	           break;

	       case 'payment':
	           $state = $order_info['order_state'] == ORDER_STATE_NEW && $order_info['payment_code'] == 'online';
	           break;

            //调整运费
        	case 'modify_price':
        	    $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
        	       ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
        	    $state = floatval($order_info['shipping_fee']) > 0 && $state;
        	   break;
	    //调整商品价格
        	case 'spay_price':
        	    $state = ($order_info['order_state'] == ORDER_STATE_NEW) ||
        	       ($order_info['payment_code'] == 'offline' && $order_info['order_state'] == ORDER_STATE_PAY);
				   $state = floatval($order_info['goods_amount']) > 0 && $state;
        	   break;

        	//发货
        	case 'send':
        	    $state = !$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_PAY;
        	    break;

        	//收货
    	    case 'receive':
    	        $state = !$order_info['lock_state'] && $order_info['order_state'] == ORDER_STATE_SEND;
    	        break;

    	    //评价
    	    case 'evaluation':
    	        $state = !$order_info['lock_state'] && !$order_info['evaluation_state'] && $order_info['order_state'] == ORDER_STATE_SUCCESS;
    	        break;

        	//锁定
        	case 'lock':
        	    $state = intval($order_info['lock_state']) ? true : false;
        	    break;

        	//快递跟踪
        	case 'deliver':
        	    $state = !empty($order_info['shipping_code']) && in_array($order_info['order_state'],array(ORDER_STATE_SEND,ORDER_STATE_SUCCESS));
        	    break;

        	//放入回收站
        	case 'delete':
        	    $state = in_array($order_info['order_state'], array(ORDER_STATE_CANCEL,ORDER_STATE_SUCCESS)) && $order_info['delete_state'] == 0;
        	    break;

        	//永久删除、从回收站还原
        	case 'drop':
        	case 'restore':
        	    $state = in_array($order_info['order_state'], array(ORDER_STATE_CANCEL,ORDER_STATE_SUCCESS)) && $order_info['delete_state'] == 1;
        	    break;

        	//分享
        	case 'share':
        	    $state = true;
        	    break;

        }
        return $state;

    }
    
    /**
     * 联查订单表订单商品表
     *
     * @param array $condition
     * @param string $field
     * @param number $page
     * @param string $order
     * @return array
     */
    public function getOrderAndOrderGoodsList($condition, $field = '*', $page = 0, $order = 'rec_id desc') {
        return $this->table('order_goods,order')->join('inner')->on('order_goods.order_id=order.order_id')->where($condition)->field($field)->page($page)->order($order)->select();
    }
   //为了得到用户购买了限时抢购需要的 
    public function justgetorder($condition, $field = 'order.order_id', $page = 0, $order = 'rec_id desc') {
    	return $this->table('order_goods,order')->on('order_goods.order_id=order.order_id')->where($condition)->field($field)->page($page)->order($order)->select();
    }
    public function getOrderAndOrderGoodsSalesRecordListX($condition, $field="*", $page = 0, $order = 'rec_id desc') {
    	$condition['order_state'] = array('in', array(ORDER_STATE_NEW,ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));
    	return $this->justgetorder($condition, $field, $page, $order);
    }
    /**
     * 订单销售记录 订单状态为20、30、40时
     * @param unknown $condition
     * @param string $field
     * @param number $page
     * @param string $order
     */
    public function getOrderAndOrderGoodsSalesRecordList($condition, $field="*", $page = 0, $order = 'rec_id desc') {
        $condition['order_state'] = array('in', array(ORDER_STATE_NEW,ORDER_STATE_PAY, ORDER_STATE_SEND, ORDER_STATE_SUCCESS));
        return $this->getOrderAndOrderGoodsList($condition, $field, $page, $order);
    }

    /**查询用户买过商品的样品数量**/
    public function getBuySampleGoodsNum($goods_commonid,$buyer_id){
        $goods_ids = $this->table('goods')->field('goods_id')->where(array('goods_commonid'=>$goods_commonid))->select();
        if(!$goods_ids){
            return true;
        }
        $ids  = array();
        foreach($goods_ids as $val){
            $ids[] = $val['goods_id'];
        }
        $condition = array();
        $condition['goods_id'] = array('in',$ids);
        $condition['buyer_id'] = $buyer_id;
        $condition['goods_pay_type'] = 3;//样品交易

        $buy_list = $this->table('order_goods')->where($condition)->field('goods_num,order_id')->select();
        $order_ids = array();
        $goods_num =array();
        foreach($buy_list as $buy){
            $order_ids[] = $buy['order_id'];
            $goods_num[$buy['order_id']] = $buy['goods_num'];
        }
        $order_list = $this->table('order')->where(array('order_state'=>array('neq',0),'order_id'=>array('in',$order_ids)))->field('order_id')->select();
        $sum = 0;
        foreach($order_list as $val){
            $sum+= $goods_num[$val['order_id']];
        }
        return $sum;
    }
    //获取子订单信息
    public function getSubOrderInfo($condition = array(), $field='*'){
        return $this->table('sub_order')->field($field)->where($condition)->find();
    }
    //拆单商品信息列表
    public function getSubOrderList($condition, $pagesize = '', $field = '*', $order = 'order_id desc', $limit = ''){
        return $this->table('sub_order')->field($field)->page($pagesize)->where($condition)->order($order)->limit($limit)->select();
    }
    //添加拆单,子单有物流整单发货
    public function addSubOrderList($dataList,$refund_goods,$order_info,$tran = true){

        if(count($dataList)>0){
            if(!$tran){
                foreach($dataList as $val){
                    if($val['sub_order_state']==1){
                        $send=true;
                        break;
                    }
                }
                $order_id = current($dataList);
                $order_id = $order_id['order_id'];
                if($send){
                    $this->_sub_order_send($order_id);
                }
                $this->table('order')->where(array('order_id'=>$order_id))->update(array('take_apart'=>1));
                $dataList = array_values($dataList);
                if(!$this->table('sub_order')->insertAll($dataList)){
                    throw new Exception();
                }
                //退款
                $model_refund=Model('refund_return');
                $model_refund->addRefundGoodsList($refund_goods,$order_info);
                return true;
            }
            try{
                $this->beginTransaction();
                $send = false;
                foreach($dataList as $val){
                    if($val['sub_order_state']==1){
                        $send=true;
                        break;
                    }
                }
                $order_id = current($dataList);
                $order_id = $order_id['order_id'];
                if($send){
                    $this->_sub_order_send($order_id);
                }
                $this->table('order')->where(array('order_id'=>$order_id))->update(array('take_apart'=>1));
                $dataList = array_values($dataList);
                if(!$this->table('sub_order')->insertAll($dataList)){
                    throw new Exception();
                }
                //退款
                $model_refund=Model('refund_return');
                $model_refund->addRefundGoodsList($refund_goods,$order_info);
                $this->commit();
                return true;
            }catch (Exception $e) {
                $this->rollback();
                return false;
            }

        }
    }
    //编辑拆单
    public function editSubOrderList($new_order_list,$old_order_list,$condition,$refund_goods,$order_info){
        try {
            $this->beginTransaction();
            foreach($old_order_list as $val){
                $this->editSubOrder($val);
            }
            $this->table('sub_order')->where($condition)->delete();
            $this->addSubOrderList($new_order_list,$refund_goods,$order_info,false);
            $this->commit();
            return true;
        } catch (Exception $e) {
            $this->rollback();
            return false;
        }
    }
    //编辑子单（发货）
    public function editSubOrder($data){
        if($data['sub_order_id']){
            $sub_id = $data['sub_order_id'];
            unset($data['sub_order_id']);
            $sub_order = $this->table('sub_order')->where(array('sub_order_id'=>$sub_id))->find();
            $update = $this->table('sub_order')->where(array('sub_order_id'=>$sub_id))->update($data);
            if($data['sub_order_state']==1&&$sub_order['sub_order_state']!=1){
                $this->_sub_order_send($data['order_id']);//发货
            }
            return $update;
        }
    }

    public function editSubOrderInfo($data,$condition){
        return $this->table('sub_order')->where($condition)->update($data);
    }

    //设置订单发货
    private function _sub_order_send($order_id){
        $data = array();
        $data['shipping_time'] = TIMESTAMP;
        $condition = array();
        $condition['order_id'] = $order_id;
        $condition['store_id'] = $_SESSION['store_id'];
        $update = $this->editOrderCommon($data,$condition);
        if (!$update) {
            throw new Exception('操作失败');
        }
        $data = array();
        $data['order_state'] = ORDER_STATE_SEND;
        $data['delay_time'] = TIMESTAMP;
        $update = $this->editOrder($data,$condition);
        if (!$update) {
            throw new Exception('操作失败');
        }
    }
    //根据订单好获得订单信息
    public function getorder_info($where,$order=""){
        return $this->table('order')->where($where)->order($order)->find();
    }
//记录支付回调接口日志
    public function add_pay_log($data){
        return $this->table('pay_log')->insert($data);
    }

    //获取订单列表
    public function getorder_list($where,$order=""){
        return $this->table('order')->where($where)->order($order)->select();
    }
}
