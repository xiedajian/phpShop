<?php
/*
 * 内容管理
 * 
 * */
defined('InShopNC') or exit('Access Invalid!');

class materialModel extends Model{
	public function __construct() {
		parent::__construct('material','mc_');
	}
	
	public function getlist($condition = array(), $field = '*', $page = 0, $order = 'id desc', $limit = ''){
		return $this->table('material')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
	}
}
?>