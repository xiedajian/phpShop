<?php
/**
 * 文章管理
 *
 *
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');

class categoryModel extends Model {
    public function __construct() {
        parent::__construct('category','mc_');
    }
    //获取分类分级列表
    public function get_category_menu($condition){
        $list = $this->where($condition)->select();
        if($list){
            $ret = array();
            foreach($list as $k=>$val){
                if($val['level'] == 1){
                    $ret[$val['category']] = $val;
                    unset($list[$k]);
                }
            }
            foreach($list as $k=>$val){
                if($val['level'] == 2){
                    $key = substr($val['category'],0,4).'00000000';
                    if(isset($ret[$key])){
                        $ret[$key]['sub_category'][$val['category']] = $val;
                        unset($list[$k]);
                    }
                }
            }
            foreach($list as $k=>$val){
                if($val['level'] == 3){
                    $key1 = substr($val['category'],0,4).'00000000';
                    $key2 = substr($val['category'],0,8).'0000';
                    if(isset($ret[$key1]['sub_category'][$key2])){
                        $ret[$key1]['sub_category'][$key2]['sub_category'][$val['category']] = $val;
                    }
                }
            }
            return $ret;
        }
    }

}