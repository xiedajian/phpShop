<?php
class member_orgControl extends BaseMemberControl {

	public function __construct() {
		parent::__construct();
	}
	
	public function indexOp(){
		if($_GET["submit"]){
			Tpl::showpage('member_org.view');
			return;
		}
		if(empty($_SESSION["org_id"])){
		   Tpl::showpage('member_org.form');
		}else{
		   $member_org_info=Model("member")->getMemberOrg(array("org_id"=>$_SESSION["org_id"]));
		   if($member_org_info["auth_statu"]==1){
		   	   Tpl::showpage('member_org.authsuccess');
		   }else{
			   if($member_org_info["license_img"]){
			       $member_org_info["license_img_path"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$_SESSION ['member_id'].DS.str_replace(".","_s.",$member_org_info["license_img"]);
			   }
			   if($member_org_info["organization_certificate_img"]){
			   	   $member_org_info["organization_certificate_img_path"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$_SESSION ['member_id'].DS.str_replace(".","_s.",$member_org_info["organization_certificate_img"]);
			   }
			   if($member_org_info["tax_certificate_img"]){
			   	   $member_org_info["tax_certificate_img_path"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$_SESSION ['member_id'].DS.str_replace(".","_s.",$member_org_info["tax_certificate_img"]);
			   }
			   if($member_org_info["corporate_idcart_img"]){
			       $member_org_info["corporate_idcart_img_path"]=UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$_SESSION ['member_id'].DS.str_replace(".","_s.",$member_org_info["corporate_idcart_img"]);
			   }
			   Tpl::output("member_org_info",$member_org_info);
			   if($_GET["reset_auth"]){
			   	 Tpl::showpage('member_org.form');
			   }else{
			     Tpl::showpage('member_org.view');
			   }
		   }
		}
	}
	
	public function image_uploadOp(){
		// 上传图片
		//$filename=$_SESSION ['member_id']."_".$_POST['name'];
		$upload = new UploadFile();
		$upload->set('default_dir', ATTACH_MEMBER_ORG.DS.$_SESSION ['member_id'].DS. $upload->getSysSetPath());
		$upload->set('max_size',C('image_max_filesize'));
		//$ext = strtolower(pathinfo($_FILES[$_POST['name']]['name'], PATHINFO_EXTENSION));
		//$upload->set('file_name',$filename.".".$ext);
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
	//成员门店认证
	public function org_authOp(){
		$member_auth=array();
		$member_auth["org_name"]=$_POST["org_name"];
		$member_auth["store_count"]=$_POST["store_count"];
		$member_auth["license_img"]=$_POST["license_img"];
		$member_auth["organization_certificate_img"]=$_POST["organization_certificate_img"];
		$member_auth["tax_certificate_img"]=$_POST["tax_certificate_img"];
		$member_auth["corporate"]=$_POST["corporate"];
		$member_auth["corporate_idcart_img"]=$_POST["corporate_idcart_img"];
		$member_auth["corporate_idcartf_img"]=$_POST["corporate_idcartf_img"];
		$member_auth["conneter"]=$_POST["conneter"];
		$member_auth["conneter_tel"]=$_POST["conneter_tel"];
		$member_auth["conneter_qq"]=$_POST["conneter_qq"];
		$member_auth["applicant_id"]=$_SESSION["member_id"];
		$member_auth["applicant_name"]=$_SESSION["member_name"];
		$member_auth["create_time"]=TIMESTAMP;
		$member_auth["auth_statu"]=0;
		$member_model=Model('member');
	   if(empty($_SESSION["org_id"])){
		   $result=$member_model->addMemberOrg($member_auth);
		   if($result){
		   	 $member_model->editMember(array('member_id'=>$_SESSION["member_id"]),array("org_id"=>$result));
		   	 $_SESSION["org_id"]=$result;
		   }
	   }else{
	   	   $result=$member_model->editMemberOrg(array('org_id'=>$_SESSION["org_id"]),$member_auth);
	   }
	   if($result){
			if(strpos($_SERVER['HTTP_REFERER'],"login")){//来自注册
				redirect(SHOP_SITE_URL.'/index.php?act=login&op=ipvp_register_succ&is_auth=1');
			}else{
				redirect(SHOP_SITE_URL.'/index.php?act=member_org&submit=1');
			}
		}else{
			showDialog('数据提交失败!');
		}
	}
}
