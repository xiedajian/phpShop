<?php
/**
 * 我的商城
 *
 *
 *
 *
 
 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class member_indexControl extends mobileMemberControl {

	public function __construct(){
		parent::__construct();
	}

    /**
     * 我的商城
     */
	public function indexOp() {
        $member_info = array();
        $member_info['user_name'] = $this->member_info['member_name'];
        $member_info['avator'] = getMemberAvatarForID($this->member_info['member_id']);
        $member_info['point'] = $this->member_info['member_points'];
        $member_info['predepoit'] = $this->member_info['available_predeposit'];
        $member_info['available_rc_balance'] = $this->member_info['available_rc_balance'];
        //收藏
        $model_favorites = Model('favorites');
        $member_info['favorites_count'] = $model_favorites->getMemberGoodsFavoritesCount($this->member_info['member_id']);

        $model_member = Model('member');
        //认证信息
        if($this->member_info['org_id']){
            $auth = $model_member->getMemberOrg(array('org_id'=>$this->member_info['org_id']));
            $member_info['auth'] = $auth['auth_statu'];
        }else{
            $member_info['auth'] = '';
        }
        //会员级别
        //$member_gradeinfo = $model_member->getOneMemberGrade(intval($member_info['member_exppoints']));
        //$member_info = array_merge($member_info,$member_gradeinfo);
        $member_info['is_vip'] = $this->member_info['member_level']==2;
        //订单数量
        $model_order=Model('order');
        $member_info['order_nopay_count']=$model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'NewCount');//代付款
        $member_info['order_nosend_count']=$model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'PayCount');//代发货
        $member_info['order_norecevice_count']=$model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'SendCount');//待收货
        $member_info['order_refund_count']=$model_order->getOrderCountByID('buyer',$this->member_info['member_id'],'RefundCount');//退款中

        output_data(array('member_info' => $member_info));
	}

    /**充值卡充值**/
    public function charge_cardOp(){
        $sn = (string) $_POST['cardcode'];
        if (!$sn || strlen($sn) > 50) {
            output_data(array('msg'=>'平台充值卡卡号不能为空且长度不能大于50'));
        }
        $msg = '';
        $member = array(
            'member_id'=>$this->member_info['member_id'],
            'member_name'=>$this->member_info['member_name'],
        );
        try {
            $denomination = model('predeposit')->addRechargeCard($sn, $member);
            $msg = '平台充值卡使用成功';
        } catch (Exception $e) {
            $msg = $e->getMessage();
        }
        output_data(array('msg'=>$msg,'denomination'=>$denomination));
    }

    //加入直邮宝
    public function agree_zhiyoubao_protocalOp(){
    	Model("member")->editMember(array("member_id"=>$this->member_info['member_id']),array("is_zhiyoubao"=>1));
    	output_data(array('msg'=>'ok'));
    }
}
