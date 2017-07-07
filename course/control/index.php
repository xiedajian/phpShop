<?php
/**
 * 首页
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');

class indexControl extends BaseControl{

	public function indexOp(){
        $this->_get_top_menu();
        $this->_get_focus();
        Tpl::showpage('index');
    }

}
