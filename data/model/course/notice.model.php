<?php
/*
 * tag 管理
 * */
defined('InShopNC') or exit('Access Invalid!');
class noticeModel extends Model{
 public function __construct() {
        parent::__construct('notice','mc_');
    }
    
    public function getlist($condition = array(), $field = '*', $page = 0, $order = 'id desc', $limit = ''){
    	return $this->table('notice')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }
}