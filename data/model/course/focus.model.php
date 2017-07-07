<?php
/**
 * 文章管理
 *
 *
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');

class focusModel extends Model {
    public function __construct() {
        parent::__construct('focus','mc_');
    }

    public function getFocusList($condition =array()){
        return $this->where($condition)->order('sort asc')->select();
    }
}