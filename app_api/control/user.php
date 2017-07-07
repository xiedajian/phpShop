<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/8/2
 * Time: 14:18
 */
class userControl extends BaseAPPControl{
    public function __construct(){
        parent::__construct();
    }
    public function getInfoOP(){
        if($this->currentUser){
            $this->returnJson(1, 'success', $this->currentUser);
        }else{
            $this->returnJson(0, 'fail', array());
        }
    }
    private function getImgURL($imgName,$thumb_ext = ''){
        if($imgName){
            if($thumb_ext){
                $imgName = explode('.', $imgName);
                $imgName = $imgName[0] . "$thumb_ext." .$imgName[1];
            }
            $imgName = UPLOAD_SITE_URL.DS.ATTACH_MEMBER_ORG.DS.$this->currentUser['member_id'].DS.$imgName;
        }

        return $imgName;
    }
    //获取账户的认证信息
    public function getMermberOrgOP()
    {
        $user = $this->currentUser;
        if (!empty($user)) {
            //获取用户认证信息
            $member_org_info = Model("member")->getMemberOrg(array("org_id" => $user["org_id"]));
            if ($member_org_info) {

                $member_org_info["license_img"] = array(
                    'name' => $member_org_info["license_img"],
                    'thumb' => $this->getImgURL($member_org_info["license_img"], '_s')
                );
                $member_org_info["organization_certificate_img"]= array(
                    'name' => $member_org_info["organization_certificate_img"],
                    'thumb' => $this->getImgURL($member_org_info["organization_certificate_img"], '_s')
                );
                $member_org_info["tax_certificate_img"] = array(
                    'name' => $member_org_info["tax_certificate_img"],
                    'thumb' => $this->getImgURL($member_org_info["tax_certificate_img"], '_s')
                );
                $member_org_info["corporate_idcart_img"] = array(
                    'name' => $member_org_info["corporate_idcart_img"],
                    'thumb' => $this->getImgURL($member_org_info["corporate_idcart_img"], '_s')
                );
                $member_org_info["corporate_idcartf_img"] = array(
                    'name' => $member_org_info["corporate_idcartf_img"],
                    'thumb' => $this->getImgURL($member_org_info["corporate_idcartf_img"], '_s')
                );

                $this->returnJson(1, 'success', $member_org_info);
            } else {
                $this->returnJson(0, '未认证', array());
            }
        }
    }
    public function uploadImgOp(){
        // 上传图片
        if($this->currentUser){
            $upload = new UploadFile();
            $upload->set('default_dir', ATTACH_MEMBER_ORG.DS.$this->currentUser['member_id'].DS. $upload->getSysSetPath());
            $upload->set('max_size',10240);
            $upload->set('thumb_width', "100,1000");
            $upload->set('thumb_height', "100,1000");
            $upload->set('thumb_ext', "_s,_b");
            $upload->set('allow_type', array('gif', 'jpg', 'jpeg', 'png'));
            $result = $upload->upfile('file');

            if (!$result) {
                $this->returnJson(1001, '请尝试jpg图片格式或小图片', $upload->error);
            }
            $filename=$upload->get('file_name');
            $this->returnJson(1, '上传成功', array(
                "img_path" => UPLOAD_SITE_URL.DS.$upload->get('default_dir').str_replace('.','_s.',$filename),
                "img_name" => $filename,
            ));
        }
    }

    public function uploadAvatarOp(){
        if($this->currentUser) {
            //上传图片
            $upload = new UploadFile();
            $upload->set('thumb_width', 500);
            $upload->set('thumb_height', 499);
            $upload->set('max_size',10240);
            $ext = strtolower(pathinfo($_FILES['pic']['name'], PATHINFO_EXTENSION));
            $upload->set('file_name', "avatar_{$this->currentUser['member_id']}.$ext");
            $upload->set('thumb_ext', '_new');
            $upload->set('ifremove', true);
            $upload->set('default_dir', ATTACH_AVATAR);
            if (!empty($_FILES['pic']['tmp_name'])) {
                $result = $upload->upfile('pic');
                if (!$result) {
                    //$this->returnJson(1001, '上传失败', '大小不超过1M');
                    $this->returnJson(1001, '请尝试jpg图片格式或大小不超过1M', $upload->error);
                }
            } else {
                $this->returnJson(1002, '请尝试jpg图片格式或小图片', $upload->error);
            }

            $avatarFile = BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS."avatar_{$this->currentUser['member_id']}_new.jpg";
       //     echo $avatarFile;
        //    echo file_exists($avatarFile);

            if(file_exists($avatarFile)){
                $newFile = BASE_UPLOAD_PATH.DS.ATTACH_AVATAR.DS."avatar_{$this->currentUser['member_id']}.jpg";
             //   var_dump($newFile);
                rename($avatarFile, $newFile);
            }
       //     exit;
            $filename = $upload->get('file_name');

            $memberModel = Model('member');
            $memberModel->editMember(array('member_id'=>$this->currentUser['member_id']), array('member_avatar'=>$filename));
            //修改头像，修改token
            $userinfo = Model('member')->getMemberInfo(array('member_id'=>$this->currentUser['member_id']));
            //获取该用户的token
            $this->edit_token($this->currentUser['member_id'],$userinfo);
            $this->returnJson(1, '上传成功', array(
                "img_path" => UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.'/'.$filename,
                'name' => $filename
            ));
        }else{
            echo '请登录';
        }

    }
    public function certApplyOp(){
        $member_auth=array();
        $member_auth["org_name"] = $this->POST["org_name"] ? $this->POST["org_name"] : '';
        $member_auth["store_count"] = $this->POST["store_count"] ? $this->POST["store_count"] : 0;
        $member_auth["license_img"] = $this->POST["license_img"] ? $this->POST["license_img"] : '';
        $member_auth["organization_certificate_img"] = $this->POST["organization_certificate_img"] ? $this->POST["organization_certificate_img"] : '';
        $member_auth["tax_certificate_img"] = $this->POST["tax_certificate_img"] ? $this->POST["tax_certificate_img"] : '';
        $member_auth["corporate"] = $this->POST["corporate"] ? $this->POST["corporate"] : '';
        $member_auth["corporate_idcart_img"] = $this->POST["corporate_idcart_img"] ? $this->POST["corporate_idcart_img"] : '';
        $member_auth["corporate_idcartf_img"] = $this->POST["corporate_idcartf_img"] ? $this->POST["corporate_idcartf_img"] : '';
        $member_auth["conneter"] = $this->POST["conneter"] ? $this->POST["conneter"] : '';
        $member_auth["conneter_tel"] = $this->POST["conneter_tel"] ? $this->POST["conneter_tel"] : '';
        $member_auth["conneter_qq"] = $this->POST["conneter_qq"] ? $this->POST["conneter_qq"] : '';
        $member_auth["applicant_id"] = $this->currentUser['member_id'] ;
        $member_auth["applicant_name"] = $this->currentUser['member_name'];
        $member_auth["create_time"] = TIMESTAMP;
        $member_auth["auth_statu"] = 0;

        $member_model = Model('member');
     //   $this->returnJson(1, 'SUCCESS',$member_auth);

        if(empty($this->currentUser["org_id"])){
            $result = $member_model->addMemberOrg($member_auth);
            if($result){
                $member_model->editMember(array('member_id' => $this->currentUser["member_id"]), array("org_id" => $result));
                //修改redis
                //修改绑定手机，修改token
                $userinfo = Model('member')->getMemberInfo(array('member_id'=>$this->currentUser['member_id']));
                //获取该用户的token
                $this->edit_token($this->currentUser['member_id'],$userinfo);
            }else{
                $this->returnJson(1002, '数据提交失败!', array());
            }

        }else{
            $result = $member_model->editMemberOrg(array('org_id'=>$this->currentUser["org_id"]), $member_auth);
            $userinfo = Model('member')->getMemberInfo(array('member_id'=>$this->currentUser['member_id']));
            //获取该用户的token
            $this->edit_token($this->currentUser['member_id'],$userinfo);
        }
        
        if($result){
            $this->returnJson(1, 'SUCCESS', array());
        }else{
            $this->returnJson(1001, '数据提交失败!', array());
        }
    }
}