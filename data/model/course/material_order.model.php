<?php
/**
 * 首页板块模型
 */
defined('InShopNC') or exit('Access Invalid!');

class material_orderModel extends Model {
    public function __construct() {
        parent::__construct('material_order','mc_');
    }

    //获取收藏夹列表
    public function getlist($condition = array(), $field = '*', $page = 0, $order = 'id desc', $limit = ''){
        return $this->table('user_collection')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }


    public function getUserpayingList($condition = array(), $field = '*', $page = 0, $order = 'material_order.id desc', $limit = ''){
        return $this->table('material_order,material')->join('inner')->on('material_order.material_id=material.id')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }
}