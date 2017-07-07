<?php
/**
 * 文章管理
 *
 *
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');

class top_menuModel extends Model {
    public function __construct() {
        parent::__construct('top_menu','mc_');
    }

    public function get_top_menu($condition = array()){
        return $this->where($condition)->order('sort asc')->select();

    }

}