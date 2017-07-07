<?php
/**
 * 首页板块模型
 */
defined('InShopNC') or exit('Access Invalid!');

class user_collectionModel extends Model {
    public function __construct() {
        parent::__construct('user_collection','mc_');
    }

    //获取收藏夹列表
    public function getlist($condition = array(), $field = '*', $page = 0, $order = 'id desc', $limit = ''){
        return $this->table('user_collection')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }


    public function getUserCollectList($condition = array(), $field = '*', $page = 0, $order = 'user_collection.id desc', $limit = ''){
        return $this->table('user_collection,material')->join('inner')->on('user_collection.material_id=material.id')->field($field)->where($condition)->page($page)->order($order)->limit($limit)->select();
    }
}