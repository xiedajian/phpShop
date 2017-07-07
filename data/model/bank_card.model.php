<?php
/**
 * 富友支付
 * 银行卡
 */
defined('InShopNC') or exit('Access Invalid!');
class bank_cardModel extends Model {

	public function getList($member_id){
		$condition = array();
		$condition['member_id'] = $member_id;
		return $this->table('bank_card')->field('*')->where($condition)->select();
	}
}