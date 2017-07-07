<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');

class loginControl extends BaseHomeControl {

	public function __construct(){
		parent::__construct();
		Tpl::output('hidden_nctoolbar', 1);
	}

    private function _is_weixin(){
        if(strpos($_SERVER['HTTP_USER_AGENT'],'MicroMessenger') !== false){
            return true;
        }else{
            return false;
        }
    }
    //登录时判断是否是手机端
    private function _check_mobile(){
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        $uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
        if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap'))
        {
            global $config;
            header('location:'.$config['wap_site_url'].'/tmpl/member/login.html?ref_url='.$_GET['ref_url']);exit;
        }
    }
    /**
     * 登录生成token
     */
    private function _get_token($member_id, $member_name, $client='wap') {
        $model_mb_user_token = Model('mb_user_token');

        //重新登录后以前的令牌失效
        //暂时停用
        //$condition = array();
        //$condition['member_id'] = $member_id;
        //$condition['client_type'] = $_POST['client'];
        //$model_mb_user_token->delMbUserToken($condition);

        //生成新的token
        $mb_user_token_info = array();
        $token = md5($member_name . strval(TIMESTAMP) . strval(rand(0,999999)));
        $mb_user_token_info['member_id'] = $member_id;
        $mb_user_token_info['member_name'] = $member_name;
        $mb_user_token_info['token'] = $token;
        $mb_user_token_info['login_time'] = TIMESTAMP;
        $mb_user_token_info['client_type'] =$client;

        $result = $model_mb_user_token->addMbUserToken($mb_user_token_info);

        if($result) {
            return $token;
        } else {
            return null;
        }

    }
	/**
	 * 登录操作
	 *
	 */
	public function indexOp(){
        //同步登录
        $model_member	= Model('member');
        if($_SESSION['is_login'] == '1'&&$_REQUEST['ref_url']) {
            @header("Location:".$_REQUEST['ref_url']);
            exit();
        }
        //检查登录状态
        $model_member->checkloginMember();

        if(isset($_GET['ticket'])){//验证登录
            $ref = htmlspecialchars_decode($_GET['ref_url']);
            if(!$ref)$ref = C('base_site_url');
            $member_info = getUserInfo($_GET['ticket']);
            if(is_array($member_info)&&!empty($member_info)){
                $model_member->createSession($member_info);
                $key = $this->_get_token($member_info['member_id'],$member_info['member_name']);

				//xwt 修复登陆405错误
				$domain = COOKIE_DOMAIN;
                setcookie('username',$member_info['member_name'], time()+3600,'/',$domain);
                setcookie('key',$key, time()+3600,'/',$domain);

                if($this->_is_weixin()){//在微信端
                    $weixin_bind_model = Model('weixin_bind');
                    $bind_info = $weixin_bind_model->where(array('member_id'=>$member_info['member_id']))->find();
                    if(empty($bind_info)){
                        header('location:'.C('wap_site_url').'/tmpl/member/weixin_bind.html?ref='.$ref);exit;
                    }
                }
                header('location:'.$ref);exit;
            }
            header('location:'.C('base_site_url'));exit;
        }else{
            //登录表单页面
            Language::read("home_login_index");
            $lang	= Language::getLangContent();
            $_pic = @unserialize(C('login_pic'));
            if ($_pic[0] != ''){
                Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
            }else{
                Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
            }
            if(empty($_GET['ref_url'])) {
                $ref_url = getReferer();
                if (!preg_match('/act=login&op=logout/', $ref_url)) {
                    $_GET['ref_url'] = $ref_url;
                }else{
                    $ref_url = C('base_site_url');
                }
            }else{
                $ref_url = $_GET['ref_url'];
            }
            $url = sync_login(SSO_CALLBACK."&ref_url=".urlencode($ref_url));
            $this->_check_mobile();
            Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);
            Tpl::output('action',$url);
            if ($_GET['inajax'] == 1){
                Tpl::showpage('login_inajax','null_layout');
            }else{
                Tpl::showpage('login');
            }
        }
        exit;

		//old
		Language::read("home_login_index");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');
		//检查登录状态
		$model_member->checkloginMember();
		if ($_GET['inajax'] == 1 && C('captcha_status_login') == '1'){
		    $script = "document.getElementById('codeimage').src='".APP_SITE_URL."/index.php?act=seccode&op=makecode&nchash=".getNchash()."&t=' + Math.random();";
		}
		$result = chksubmit(true,C('captcha_status_login'),'num');
		if ($result !== false){
			if ($result === -11){
				showDialog_test($lang['login_index_login_illegal'],'','error',$script);  //非法提交
			}elseif ($result === -12){
				showDialog_test($lang['login_index_wrong_checkcode'],'','error',$script); //验证码错误
			}
			if (process::islock('login')) {
				showDialog_test($lang['nc_common_op_repeat'],'','error',$script);  //您的操作过于频繁，请稍后再试
			}
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
				array("input"=>trim($_POST["user_name"]),		"require"=>"true", "message"=>$lang['login_index_username_isnull']),
				array("input"=>trim($_POST["password"]),		"require"=>"true", "message"=>$lang['login_index_password_isnull']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
			    showDialog_test($error,SHOP_SITE_URL,'error',$script);
			}
			$array	= array();
			$array['member_name']	= trim($_POST['user_name']);
			$array['member_passwd']	= md5(trim($_POST['password']));
			$member_info = $model_member->getMemberInfo($array);
			if(is_array($member_info) and !empty($member_info)) {
				if(!$member_info['member_state']){
			        showDialog($lang['login_index_account_stop'],''.'error',$script);  //账号被停用
				}
			}else{
			    process::addprocess('login');
			    showDialog_test($lang['login_index_login_again'],$_SERVER['HTTP_REFERER'],'error',$script);   //用户名或密码错误，请重新登录
			}
    		$model_member->createSession($member_info);
			process::clear('login');

			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);

			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);

			if ($_GET['inajax'] == 1){
			    showDialog_test('',$_POST['ref_url'] == '' ? 'reload' : $_POST['ref_url'],'js');
			} else {
			    redirect($_POST['ref_url']);
			}
		}else{

			//登录表单页面
			$_pic = @unserialize(C('login_pic'));
			if ($_pic[0] != ''){
				Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
			}else{
				Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
			}

			if(empty($_GET['ref_url'])) {
			    $ref_url = getReferer();
			    if (!preg_match('/act=login&op=logout/', $ref_url)) {
			     $_GET['ref_url'] = $ref_url;
			    }
			}
			Tpl::output('html_title',C('site_name').' - '.$lang['login_index_login']);
			if ($_GET['inajax'] == 1){
				Tpl::showpage('login_inajax','null_layout');
			}else{
				Tpl::showpage('login'); 
			}
		}
	}

	/**
	 * 退出操作
	 *
	 * @param int $id 记录ID
	 * @return array $rs_row 返回数组形式的查询结果
	 */
	public function logoutOp(){
        //sso
        if(isset($_GET['data'])){
            sync_logout($_GET['data']);exit;
        }
		//xwt修复登录出现405错误
		session_unset();

		session_destroy();

		//xwt修复登录出现405错误
		$domain = COOKIE_DOMAIN;
		setcookie('username', '', time()-3600,'/',$domain);
		setcookie('key','', time()-3600,'/',$domain);
		setNcCookie('msgnewnum'.$_SESSION['member_id'],'',-3600);
		setNcCookie('cart_goods_num','',-3600);

        header('location:'.SSO_AUTH_API.'?m=sso&c=sso&a=sync_logout&app_id='.SSO_APP_ID);exit;
	    //old
		Language::read("home_login_index");
		$lang	= Language::getLangContent();
		// 清理消息COOKIE
		setNcCookie('msgnewnum'.$_SESSION['member_id'],'',-3600);
		session_unset();
		session_destroy();
		setNcCookie('cart_goods_num','',-3600);
		if(empty($_GET['ref_url'])){
			$ref_url = getReferer();
		}else {
			$ref_url = $_GET['ref_url'];
		}
		redirect('index.php?act=login&ref_url='.urlencode($ref_url));
	}

	/**
	 * 会员注册页面
	 *
	 * @param
	 * @return
	 */
	public function registerOp() {
		//zmr>v30
		$zmr=intval($_GET['zmr']);
		if($zmr>0)
		{
		  setcookie('zmr', $zmr);
		}
		
		Language::read("home_login_register");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');
		$model_member->checkloginMember();
		Tpl::output('html_title',C('site_name').' - '.$lang['login_register_join_us']);
		Tpl::showpage('register');
	}
	
	public function ipvp_registerOp(){
		if(chksubmit(true)){
			$mobile=$_POST["mobile"];
			$code=$_POST["code"];
			$password=$_POST["password"];
			$true_name=$_POST["truename"];
			$check_register=$_SESSION["register_mobile_code"];
  			if(empty($check_register)){
  				showDialog("验证码已过期",'','error');
  			}
  			if($check_register["mobile"]!=$mobile||$check_register["code"]!=$code){
  				showDialog("验证码错误",'','error');
  			}
			$member_model=Model("member");
			$condition = array();
			$condition['member_name|member_mobile'] =$mobile;
			$member_info = $member_model->getMemberInfo($condition,'member_id');
			if ($member_info) {
				showDialog("手机号已存在",'','error');
			}
			$register=array();
        	$register["member_mobile"]=$mobile;
        	$register["member_name"]=$mobile;
        	$register["member_truename"]=empty($true_name)?$mobile:$true_name;
        	$register["member_passwd"]=$password;
        	$register["member_mobile_bind"]=1;
        	$register["register_client"]=0;
        	$register["member_group"]=1;
        	//获取手机IP归属地
        	require_once(BASE_DATA_PATH .'/api/utils/mobile_address.php');
        	$register["mobile_address"]=get_mobile_address($mobile);

            $member_id=$member_model->addMember($register);
            if(!$member_id){
                showDialog("注册失败!",'','error');
            }

            $_SESSION["register_mobile_code"]=null;
            // 添加默认相册
            $insert['ac_name']      = '买家秀';
            $insert['member_id']    = $member_id;
            $insert['ac_des']       = '买家秀默认相册';
            $insert['ac_sort']      = 1;
            $insert['is_default']   = 1;
            $insert['upload_time']  = TIMESTAMP;
            Model('sns_album')->addSnsAlbumClass($insert);
            $memberInfo=$member_model->getMemberInfoByID($member_id);
            $member_model->createSession($memberInfo);
            //进入店铺认证
            redirect('index.php?act=login&op=ipvp_auth');
		}else{
			//Model('member')->checkloginMember();
			Tpl::showpage('ipvp_register','null_layout');
		}
	}

	
	public function ipvp_authOp(){
		Tpl::showpage('ipvp_register_1','null_layout');
	}
	
	public function ipvp_register_succOp(){
		Tpl::showpage('ipvp_register_2','null_layout');
	}
	/**
	 * 会员添加操作
	 *
	 * @param
	 * @return
	 */
	public function usersaveOp() {
		//重复注册验证
		if (process::islock('reg')){
			showDialog(Language::get('nc_common_op_repeat'));
		}
		Language::read("home_login_register");
		$lang	= Language::getLangContent();
		$model_member	= Model('member');
		$model_member->checkloginMember();
		$result = chksubmit(true,C('captcha_status_register'),'num');
		if ($result){
			if ($result === -11){
				showDialog($lang['invalid_request'],'','error');
			}elseif ($result === -12){
				showDialog($lang['login_usersave_wrong_code'],'','error');
			}
		} else {
		    showDialog($lang['invalid_request'],'','error');
		}
        $register_info = array();
        $register_info['username'] = $_POST['user_name'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
		//添加奖励积分zmr>v30
		$zmr=intval($_COOKIE['zmr']);
		if($zmr>0)
		{
			$pinfo=$model_member->getMemberInfoByID($zmr,'member_id');
			if(empty($pinfo))
			{
				$zmr=0;
			}
		}
		$register_info['inviter_id'] = $zmr;
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $model_member->createSession($member_info,true);
			process::addprocess('reg');

			// cookie中的cart存入数据库
			Model('cart')->mergecart($member_info,$_SESSION['store_id']);

			// cookie中的浏览记录存入数据库
			Model('goods_browse')->mergebrowse($_SESSION['member_id'],$_SESSION['store_id']);

			$_POST['ref_url']	= (strstr($_POST['ref_url'],'logout')=== false && !empty($_POST['ref_url']) ? $_POST['ref_url'] : 'index.php?act=member_information&op=member');
			redirect($_POST['ref_url']);
        } else {
			showDialog($member_info['error']);
        }
	}
	/**
	 * 会员名称检测
	 *
	 * @param
	 * @return
	 */
	public function check_memberOp() {
			/**
		 	* 实例化模型
		 	*/
			$model_member	= Model('member');

			$check_member_name	= $model_member->getMemberInfo(array('member_name'=>$_GET['user_name']));
			if(is_array($check_member_name) and count($check_member_name)>0) {
				echo 'false';
			} else {
				echo 'true';
			}
	}

	/**
	 * 电子邮箱检测
	 *
	 * @param
	 * @return
	 */
	public function check_emailOp() {
		$model_member = Model('member');
		$check_member_email	= $model_member->getMemberInfo(array('member_email'=>$_GET['email']));
		if(is_array($check_member_email) and count($check_member_email)>0) {
			echo 'false';
		} else {
			echo 'true';
		}
	}

	/**
	 * 忘记密码页面
	 */
	public function forget_passwordOp(){
		/**
		 * 读取语言包
		 */
		Language::read('home_login_register');
		$_pic = @unserialize(C('login_pic'));
		if ($_pic[0] != ''){
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.$_pic[array_rand($_pic)]);
		}else{
			Tpl::output('lpic',UPLOAD_SITE_URL.'/'.ATTACH_LOGIN.'/'.rand(1,4).'.jpg');
		}
		Tpl::output('html_title',C('site_name').' - '.Language::get('login_index_find_password'));
		Tpl::showpage('find_password');
	}

	/**
	 * 找回密码的发邮件处理
	 */
	public function find_passwordOp(){
		Language::read('home_login_register');
		$lang	= Language::getLangContent();

		$result = chksubmit(true,true,'num');
		if ($result !== false){
		    if ($result === -11){
		        showDialog('非法提交');
		    }elseif ($result === -12){
		        showDialog('验证码错误');
		    }
		}

		if(empty($_POST['username'])){
			showDialog($lang['login_password_input_username']);
		}

		if (process::islock('forget')) {
		    showDialog($lang['nc_common_op_repeat'],'reload');
		}

		$member_model	= Model('member');
		$member	= $member_model->getMemberInfo(array('member_name'=>$_POST['username']));
		if(empty($member) or !is_array($member)){
		    process::addprocess('forget');
			showDialog($lang['login_password_username_not_exists'],'reload');
		}

		if(empty($_POST['email'])){
			showDialog($lang['login_password_input_email'],'reload');
		}

		if(strtoupper($_POST['email'])!=strtoupper($member['member_email'])){
		    process::addprocess('forget');
			showDialog($lang['login_password_email_not_exists'],'reload');
		}
		process::clear('forget');
		//产生密码
		$new_password	= random(15);
		if(!($member_model->editMember(array('member_id'=>$member['member_id']),array('member_passwd'=>md5($new_password))))){
			showDialog($lang['login_password_email_fail'],'reload');
		}

		$model_tpl = Model('mail_templates');
		$tpl_info = $model_tpl->getTplInfo(array('code'=>'reset_pwd'));
		$param = array();
		$param['site_name']	= C('site_name');
		$param['user_name'] = $_POST['username'];
		$param['new_password'] = $new_password;
		$param['site_url'] = SHOP_SITE_URL;
		$subject	= ncReplaceText($tpl_info['title'],$param);
		$message	= ncReplaceText($tpl_info['content'],$param);

		$email	= new Email();
		$result	= $email->send_sys_email($_POST["email"],$subject,$message);
		showDialog('新密码已经发送至您的邮箱，请尽快登录并更改密码！','','succ','',5);
	}

	/**
	 * 邮箱绑定验证
	 */
	public function bind_emailOp() {
	   $model_member = Model('member');
	   $uid = @base64_decode($_GET['uid']);
	   $uid = decrypt($uid,'');
	   list($member_id,$member_email) = explode(' ', $uid);

	   if (!is_numeric($member_id)) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }

	   $member_info = $model_member->getMemberInfo(array('member_id'=>$member_id),'member_email');
	   if ($member_info['member_email'] != $member_email) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }

	   $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$member_id));
	   if (empty($member_common_info) || !is_array($member_common_info)) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }
	   if (md5($member_common_info['auth_code']) != $_GET['hash'] || TIMESTAMP - $member_common_info['send_acode_time'] > 24*3600) {
	       showMessage('验证失败',SHOP_SITE_URL,'html','error');
	   }

	   $update = $model_member->editMember(array('member_id'=>$member_id),array('member_email_bind'=>1));
	   if (!$update) {
	       showMessage('系统发生错误，如有疑问请与管理员联系',SHOP_SITE_URL,'html','error');
	   }

	   $data = array();
	   $data['auth_code'] = '';
	   $data['send_acode_time'] = 0;
	   $update = $model_member->editMemberCommon($data,array('member_id'=>$_SESSION['member_id']));
	   if (!$update) {
	       showDialog('系统发生错误，如有疑问请与管理员联系');
	   }
	   showMessage('邮箱设置成功','index.php?act=member_security&op=index');

	}
    //检测手机号是否存在
	public function check_mobile_existOp() {
		$member_info=Model('member')->getMemberInfo(array('member_name|member_mobile'=>$_GET['mobile']),"member_id");
		echo empty($member_info)?"true":"false";
	}
	//发送注册验证码
	public function send_register_codeOp(){
        //验证码验证
        $code = $_REQUEST['code'];
        $hash = _getNchash('login','ipvp_register');
        //验证后销毁验证码
        if(!_check_code($hash,$code,true)){
            exit(json_encode(array('code'=>false,'msg'=>'验证码错误!')));
        }

		$mobile_no=$_GET["mobile"];
		if(!preg_match("/^1\d{10}$/",$mobile_no)){
			exit(json_encode(array('state'=>false,'msg'=>'手机号格式错误!')));
		}
		$data=$_SESSION["register_mobile_code"];
		if($data){
			$pre_send_time=$data["time"];
			if(TIMESTAMP-$pre_send_time<60){//时间过短  在一分钟内发送
				exit(json_encode(array('state'=>false,'msg'=>'发送太过频繁了')));
			}
			$mobile_old_no=$data["mobile"];
			if($mobile_old_no!=$mobile_no){
				$data["mobile"]=$mobile_no;
				$data["code"]=rand(100000,999999);
			}
		}else{
			$data=array();
			$data["mobile"]=$mobile_no;
			$data["code"]=rand(100000,999999);
		}
		$data["time"]=TIMESTAMP;
		$model_tpl = Model('mail_templates');
		$tpl_info = $model_tpl->getTplInfo(array('code'=>'register_mobile_code'));
		$param = array();
		$param['site_name']	= C('site_name');
		$param['verify_code'] = $data["code"];
		$message= ncReplaceText($tpl_info['content'],$param);
		$sms = new Sms();
		if($sms->send($mobile_no,$message,true)){
            $_SESSION["register_mobile_code"]=$data;
            exit(json_encode(array('state'=>true,'msg'=>'发送成功!')));
        }else{
            exit(json_encode(array('state'=>false,'msg'=>'发送失败!')));
        }
	}


    //手机端单点登录获取登录地址
    public function get_login_actionOp(){
        if($_SESSION['member_id']){
            echo json_encode(array('error'=>0,'data'=>''));exit;
        }
        $ref_url = $_REQUEST['ref'];
        $url = sync_login(SSO_CALLBACK."&ref_url=".urlencode($ref_url));
        echo json_encode(array('error'=>0,'data'=>$url));
    }
//会员宝登录的ipvp
	public function CrmLoginOp(){
		$member_id=$_GET["member_id"];
		$pay_sn=$_GET["pay_sn"];
		$member_model=Model("member");
		$condition = array();
		$condition['member_id'] =$member_id;
		$member_info = $member_model->getMemberInfo($condition);
		$member_model->createSession($member_info);
		$output=array();

		redirect('index.php?act=buy&op=pay&pay_sn='.$pay_sn);

		echo json_encode($output);
	}
	//发送短信
	public function ipvpAPP_send_codeOp(){
		$mobile_no=$_GET["mobile"];
		$type = $_GET['type'];  //register_mobile_code      reset_password_code
		$tpl = array(
			'register_mobile_code'    =>  'register_mobile_code',
			'reset_password_code'      =>  'reset_password_code',
		);
		
		$data=array();
		$data["mobile"]=$mobile_no;
		$data["code"]=rand(100,999).rand(100,999);
		$data["time"]=TIMESTAMP;
		$model_tpl = Model('mail_templates');
		$tpl_info = $model_tpl->getTplInfo(array('code'=>$tpl[$type]));
		$param = array();
		$param['site_name']	= C('site_name');
		$param['verify_code'] = $data["code"];
		$message= ncReplaceText($tpl_info['content'],$param);
		$sms = new Sms();
		if($sms->send($mobile_no,$message)){
			$code=0;
			$msg='发送成功';
			$result = array();
			$result['mobile'] = $mobile_no;                  //手机
			$result['countDownSeconds'] = 60;                 //过期时间
			$result['send_time'] =TIMESTAMP ;                //发送时间
			$result['mobile_code'] = $data["code"];         //验证码
			$result['type'] = $tpl[$type];              //验证码类型
		}else{
			$code=1;
			$msg='发送失败';
			$result = array();
		}
		echo json_encode(array(
			'code'  =>  $code,
			'msg'   =>  $msg,
			'data'  =>  $result
		));
	}
	

}
