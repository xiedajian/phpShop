<?php
/**
 * 首页
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');

class BaseControl{

	public function __construct(){
        Tpl::setDir('xuetang');
        Tpl::setLayout('common_layout');
	}
    //顶部菜单、分类
    protected function _get_top_menu(){
        $model_menu = Model('course/top_menu');
        $menu_list = $model_menu->get_top_menu();
        $model_category = Model('course/category');
        $condition = array();
        $condition['state'] = 1;
        $category_list = $model_category->get_category_menu($condition);
        log::record(json_encode($category_list));
        Tpl::output('nav_list',$menu_list);
        Tpl::output('category_list',$category_list);
    }
    //头部焦点图
    protected function _get_focus(){
        $model_focus = Model('course/focus');
        $list = $model_focus->getFocusList(array('state'=>1));
        foreach($list as $k=>$v){
            $v['image_url'] = '/'.DIR_UPLOAD.'/'.ATTACH_EDITOR.'/'.$v['image_url'];
            $list[$k] = $v;
        }
        Tpl::output('focus_list',$list);
    }


}
