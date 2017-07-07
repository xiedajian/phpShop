<?php
/*
 * 素材tag
 * 
 * */
defined('InShopNC') or exit('Access Invalid!');

class material_tagModel extends Model{
	public function __construct() {
		parent::__construct('material_tag','mc_');
	}
}
?>