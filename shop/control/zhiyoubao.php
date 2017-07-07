<?php
class zhiyoubaoControl extends BaseMemberControl {

	public function __construct() {
		parent::__construct();
	}
	
	public function indexOp(){
		Tpl::output("is_zhiyoubao",$this->member_info["is_zhiyoubao"]);
		Tpl::showpage('member_zhiyoubao.index');
	}
	
	public function agree_protocolOp(){
		Model("member")->editMember(array("member_id"=>$_SESSION["member_id"]),array("is_zhiyoubao"=>1));
		exit("ok");
	}
	
	
	
}