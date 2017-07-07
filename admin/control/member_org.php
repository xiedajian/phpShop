<?php
class member_orgControl extends SystemControl{

	public function __construct(){
		parent::__construct();
	}
	
	//会员店铺认证
	public function indexOp(){
		$model_member = Model('member');
		$condition=array();
		if($_GET["auth_statu"]){
			$condition["auth_statu"]=$_GET["auth_statu"];
		}else{
			$condition["auth_statu"]=0;
		}
		if($_GET["org_name"]){
			$condition["org_name"]=array("like",'%'.$_GET["org_name"].'%');
		}
		if($_GET["applicant_name"]){
			$condition["applicant_name"]=array("like",'%'.$_GET["applicant_name"].'%');
		}
		if($_GET["corporate"]){
			$condition["corporate"]=array("like",'%'.$_GET["corporate"].'%');
		}
		if($_GET["conneter"]){
			$condition["conneter"]=array("like",'%'.$_GET["conneter"].'%');
		}
		$memberorg_list=$model_member->getMemberOrgListsh($condition,"*",15);
		Tpl::output('member_org_list',$memberorg_list);
		Tpl::output('page',$model_member->showpage());
		Tpl::showpage('member_org.index');
	}
	//会员审核
	public function member_org_viewOp(){
		$model_member = Model('member');
		$member_org_info=$model_member->getMemberOrg(array("org_id"=>$_GET["org_id"]));
		if($member_org_info["license_img"]){
			$member_org_info["license_img"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["license_img"]);
		}
		if($member_org_info["organization_certificate_img"]){
			$member_org_info["organization_certificate_img"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["organization_certificate_img"]);
		}
		if($member_org_info["tax_certificate_img"]){
			$member_org_info["tax_certificate_img"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["tax_certificate_img"]);
		}
		if($member_org_info["corporate_idcart_img"]){
			$member_org_info["corporate_idcart_img"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["corporate_idcart_img"]);
		}
		if($member_org_info["corporate_idcartf_img"]){
			$member_org_info["corporate_idcartf_img"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["corporate_idcartf_img"]);
		}
// 		if($member_org_info["auth_statu"]!=0){
// 			//所在地
// 			$area_name_array = Model('area')->getAreaNames();
// 			$member_org_info['area_name']=$area_name_array[$member_org_info['province_id']];//.$area_name_array[$member_org_info['city_id']].$area_name_array[$member_org_info['district_id']];
// 			if($member_org_info['city_id']>0) {
// 				$member_org_info['area_name'] .=' '.$area_name_array[$member_org_info['city_id']];
// 				if($member_org_info['district_id']>0) {
// 					$member_org_info['area_name'] .= ' '.$area_name_array[$member_org_info['district_id']];
// 				}
// 			}
// 		}
		Tpl::output('member_org_info',$member_org_info);
		Tpl::showpage('member_org.view');
	}
	//会员审核
	public function member_org_view_saveOp(){
		$org_id=$_POST["org_id"];
		$data=array();
		$data["auth_statu"]=$_POST["auth_statu"];
		$data["auth_remark"]=$_POST["auth_remark"];
		$data["province_id"]=$_POST["province_id"];
		$data["city_id"]=$_POST["city_id"];
		$data["district_id"]=$_POST["district_id"];
		$data["auth_admin"]=$this->admin_info['name'];
		$data["auth_time"]=TIMESTAMP;
		$area_id=0;
		if(!empty($data["district_id"])&&$data["district_id"]!='0'){
			$area_id=$data["district_id"];
		}elseif(!empty($data["city_id"])&&$data["city_id"]!='0'){
			$area_id=$data["city_id"];
		}elseif(!empty($data["province_id"])&&$data["province_id"]!='0'){
			$area_id=$data["province_id"];
		}
		if($area_id!=0){
			$area_info=Model("area")->getAreaInfo(array("area_id"=>$area_id));
			if(!empty($area_info)){
				$data["area_code"]=$area_info["area_code"];
				$data["area_name"]=$area_info["mergername"];
			}
		}
		$result=Model('member')->editMemberOrg(array("org_id"=>$org_id),$data);
		showMessage("提交成功!","index.php?act=member_org&op=index");
	}
	
	public function member_org_editOp(){
		$model_member = Model('member');
		$member_org_info=$model_member->getMemberOrg(array("org_id"=>$_GET["org_id"]));
		if($member_org_info["license_img"]){
			$member_org_info["license_img_path"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["license_img"]);
		}
		if($member_org_info["organization_certificate_img"]){
			$member_org_info["organization_certificate_img_path"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["organization_certificate_img"]);
		}
		if($member_org_info["tax_certificate_img"]){
			$member_org_info["tax_certificate_img_path"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["tax_certificate_img"]);
		}
		if($member_org_info["corporate_idcart_img"]){
			$member_org_info["corporate_idcart_img_path"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["corporate_idcart_img"]);
		}
		if($member_org_info["corporate_idcartf_img"]){
			$member_org_info["corporate_idcartf_img_path"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$member_org_info["applicant_id"].DS.str_replace(".","_s.",$member_org_info["corporate_idcartf_img"]);
		}
		Tpl::output('member_org_info',$member_org_info);
		Tpl::showpage('member_org.form');
	}
	public function member_org_edit_saveOp(){
		$org_id=$_POST["org_id"];
		$data=array();
		$data["org_name"]=$_POST["org_name"];
		$data["store_count"]=$_POST["store_count"];
		$data["license_img"]=$_POST["license_img"];
		$data["organization_certificate_img"]=$_POST["organization_certificate_img"];
		$data["tax_certificate_img"]=$_POST["tax_certificate_img"];
		$data["corporate"]=$_POST["corporate"];
		$data["corporate_idcart_img"]=$_POST["corporate_idcart_img"];
		$data["corporate_idcartf_img"]=$_POST["corporate_idcartf_img"];
		$data["conneter"]=$_POST["conneter"];
		$data["conneter_tel"]=$_POST["conneter_tel"];
		$data["conneter_qq"]=$_POST["conneter_qq"];
		$data["province_id"]=$_POST["province_id"];
		$data["city_id"]=$_POST["city_id"];
		$data["district_id"]=$_POST["district_id"];
		$area_id=0;
		if(!empty($data["district_id"])&&$data["district_id"]!='0'){
			$area_id=$data["district_id"];
		}elseif(!empty($data["city_id"])&&$data["city_id"]!='0'){
			$area_id=$data["city_id"];
		}elseif(!empty($data["province_id"])&&$data["province_id"]!='0'){
			$area_id=$data["province_id"];
		}
		if($area_id!=0){
			$area_info=Model("area")->getAreaInfo(array("area_id"=>$area_id));
			if(!empty($area_info)){
				$data["area_code"]=$area_info["area_code"];
				$data["area_name"]=$area_info["mergername"];
			}
		}
		$result=Model('member')->editMemberOrg(array("org_id"=>$org_id),$data);
		showMessage("提交成功!","index.php?act=member_org&op=index");
	}
	public function member_org_imageOp(){
		$member_id=$_POST["member_id"];
		$upload = new UploadFile();
		$upload->set('default_dir', ATTACH_MEMBER_ORG.DS.$member_id.DS. $upload->getSysSetPath());
		$upload->set('max_size',C('image_max_filesize'));
		$upload->set('thumb_width', "100,1000");
		$upload->set('thumb_height', "100,1000");
		$upload->set('thumb_ext', "_s,_b");
		$upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
		$result = $upload->upfile($_POST['name']);
		if (!$result) {
			exit(json_encode(array("error"=>$upload->error)));
		}
		$filename=$upload->get('file_name');
		exit(json_encode(array("img_path"=>UPLOAD_SITE_URL.DS.$upload->get('default_dir').str_replace('.','_s.',$filename),"img_name"=>$filename)));
	}
}