<?php
class member_orgControl extends mobileMemberControl {
	public function __construct(){
		parent::__construct();
	}
	public function image_uploadOp(){
		// 上传图片
		$member_id=$this->member_info['member_id'];
		$upload = new UploadFile();
		$upload->set('default_dir', ATTACH_MEMBER_ORG.DS.$member_id.DS. $upload->getSysSetPath());
		$upload->set('max_size',C('image_max_filesize'));
		$upload->set('thumb_width', "100,1000");
		$upload->set('thumb_height', "100,1000");
		$upload->set('thumb_ext', "_s,_b");
		$upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
		$result = $upload->upfile($_POST['name']);
		if (!$result) {
			output_error($upload->error);
		}
		$filename=$upload->get('file_name');
		output_data(array("img_path"=>UPLOAD_SITE_URL.DS.$upload->get('default_dir').str_replace('.','_s.',$filename),"img_name"=>$filename));
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
		$member_auth["applicant_id"]=$this->member_info['member_id'];
		$member_auth["applicant_name"]=$this->member_info['member_name'];
		$member_auth["create_time"]=TIMESTAMP;
		$member_auth["auth_statu"]=0;
		$member_model=Model('member');
		if(empty($this->member_info["org_id"])){
			$result=$member_model->addMemberOrg($member_auth);
			if($result){
				$member_model->editMember(array('member_id'=>$this->member_info["member_id"]),array("org_id"=>$result));
			}
		}else{
			$result=$member_model->editMemberOrg(array('org_id'=>$this->member_info["org_id"]),$member_auth);
		}
		if($result){
			output_data("ok");
		}else{
			output_error('数据提交失败!');
		}
	}
	
}