<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 *
 
 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

class loginControl extends mobileHomeControl {

	public function __construct(){
		parent::__construct();
	}

	/**
	 * 登录
	 */
	public function indexOp(){
        if(empty($_POST['username']) || empty($_POST['password']) || !in_array($_POST['client'], $this->client_type_array)) {
            output_error('登录失败');
        }

		$model_member = Model('member');

        $array = array();
        $array['member_name']	= $_POST['username'];
        $array['member_passwd']	= md5($_POST['password']);
        $member_info = $model_member->getMemberInfo($array);

        if(!empty($member_info)) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'key' => $token));
            } else {
                output_error('登录失败');
            }
        } else {
            output_error('用户名密码错误');
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
	 * 注册
	 */
	public function registerOp(){
		$model_member	= Model('member');

        $register_info = array();
        $register_info['username'] = $_POST['username'];
        $register_info['password'] = $_POST['password'];
        $register_info['password_confirm'] = $_POST['password_confirm'];
        $register_info['email'] = $_POST['email'];
        $member_info = $model_member->register($register_info);
        if(!isset($member_info['error'])) {
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], $_POST['client']);
            if($token) {
                output_data(array('username' => $member_info['member_name'], 'key' => $token));
            } else {
                output_error('注册失败');
            }
        } else {
			output_error($member_info['error']);
        }

    }
    
    //发送注册验证码
    public function send_register_codeOp(){
        $code = $_REQUEST['code'];
        $hash = _getNchash('login','send_register_code');
        //验证后销毁验证码
        if(!_check_code($hash,$code,true)){
            exit(json_encode(array('code'=>1,'msg'=>'验证码错误!')));
        }

    	$mobile_no=$_GET["mobile"];
    	if(!preg_match("/^1\d{10}$/",$mobile_no)){
    		exit(json_encode(array('code'=>1,'msg'=>'手机号格式错误!')));
    	}
    	$member_info=Model('member')->getMemberInfo(array("member_mobile|member_name"=>$mobile_no),"member_id");
    	if($member_info){
    		exit(json_encode(array('code'=>2,'msg'=>'手机号已注册')));
    	}
    	$code=Model('mobile_code')->getMobileCode(1,$mobile_no);
    	if($code===false){
    		exit(json_encode(array('code'=>1,'msg'=>'验证码发送太频繁!')));
    	}
    	$model_tpl = Model('mail_templates');
    	$tpl_info = $model_tpl->getTplInfo(array('code'=>'register_mobile_code'));
    	$param = array();
    	$param['site_name']	= C('site_name');
    	$param['verify_code'] =$code;
    	$message= ncReplaceText($tpl_info['content'],$param);
    	$sms = new Sms();

    	if($sms->send($mobile_no,$message,true)){
    		exit(json_encode(array('code'=>0,'msg'=>'发送成功!')));
    	}else{
    		exit(json_encode(array('code'=>1,'msg'=>'验证码发送失败!')));
    	}
    }
    //验证 验证码
    public function check_register_codeOp(){
    	$mobile_no=$_GET["mobile"];
    	$valid_code=$_GET["valid_code"];
    	$result=Model('mobile_code')->checkMobileCode(1,$mobile_no,$valid_code);
    	if($result===true){
    		exit(json_encode(array('code'=>0,'msg'=>'验证成功!')));
    	}else{
    		exit(json_encode(array('code'=>1,'msg'=>$result)));
    	}
    }
    
    /**
     * 注册
     */
    public function ipvp_registerOp(){
			$mobile=$_POST["mobile"];
			$valid_code=$_POST["valid_code"];
			$password=$_POST["password"];
			$true_name=$_POST["true_name"];
			$code_result=Model('mobile_code')->checkMobileCode(1,$mobile,$valid_code,0);
			if($code_result!==true){
				output_error($code_result);
			}
			$member_model=Model("member");
			$condition = array();
			$condition['member_name|member_mobile'] =$mobile;
			$member_info = $member_model->getMemberInfo($condition,'member_id');
			if ($member_info) {
				output_error("手机号已注册!");
			}
			$register=array();
        	$register["member_mobile"]=$mobile;
        	$register["member_name"]=$mobile;
        	$register["member_truename"]=empty($true_name)?$mobile:$true_name;
        	$register["member_passwd"]=$password;
        	$register["member_mobile_bind"]=1;
        	$register["register_client"]=1;//wap
        	//获取手机IP归属地
        	require_once(BASE_DATA_PATH .'/api/utils/mobile_address.php');
        	$register["mobile_address"]=get_mobile_address($mobile);
        	$member_id=$member_model->addMember($register);
        	if(!$member_id){
        		output_error("注册失败!");
        	}
        	// 添加默认相册
        	$insert['ac_name']      = '买家秀';
        	$insert['member_id']    = $member_id;
        	$insert['ac_des']       = '买家秀默认相册';
        	$insert['ac_sort']      = 1;
        	$insert['is_default']   = 1;
        	$insert['upload_time']  = TIMESTAMP;
        	Model('sns_album')->addSnsAlbumClass($insert);
        	$token = $this->_get_token($member_id,$mobile);
        	if($token) {
        		output_data(array('username' =>$mobile, 'key' => $token));
        	} else {
        		output_error('注册失败!');
        	}
	}

    /**发送忘记密码验证码**/
    public function send_forgetpwd_codeOp(){
        $code = $_REQUEST['code'];
        $hash = _getNchash('login','send_forgetpwd_code');
        //验证后销毁验证码
        if(!_check_code($hash,$code,true)){
            exit(json_encode(array('code'=>1,'msg'=>'验证码错误!')));
        }

        $mobile_no=$_GET["mobile"];
        if(!preg_match("/^1\d{10}$/",$mobile_no)){
            exit(json_encode(array('code'=>1,'msg'=>'手机号格式错误!')));
        }
        $member_info=Model('member')->getMemberInfo(array("member_mobile|member_name"=>$mobile_no),"member_id");
        if(!$member_info){
            exit(json_encode(array('code'=>2,'msg'=>'手机号未注册')));
        }
        $code=Model('mobile_code')->getMobileCode(2,$mobile_no);
        if($code===false){
            exit(json_encode(array('code'=>1,'msg'=>'验证码发送太频繁!')));
        }
        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>'reset_password_code'));
        $param = array();
        $param['site_name']	= C('site_name');
        $param['verify_code'] =$code;
        $message= ncReplaceText($tpl_info['content'],$param);
        $sms = new Sms();

        if($sms->send($mobile_no,$message,true)){
            exit(json_encode(array('code'=>0,'msg'=>'发送成功!')));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>'验证码发送失败!')));
        }
    }
    /**验证忘记密码验证码**/
    public function check_forgetpwd_codeOp(){
        $mobile_no=$_GET["mobile"];
        $valid_code=$_GET["valid_code"];
        $result=Model('mobile_code')->checkMobileCode(2,$mobile_no,$valid_code);
        if($result===true){
            exit(json_encode(array('code'=>0,'msg'=>'验证成功!')));
        }else{
            exit(json_encode(array('code'=>1,'msg'=>$result)));
        }
    }
    /**重置密码**/
    public function reset_passwordOp(){
        $mobile=$_POST["mobile"];
        $valid_code=$_POST["valid_code"];
        $password=$_POST["password"];
        $code_result=Model('mobile_code')->checkMobileCode(2,$mobile,$valid_code,0);
        if($code_result!==true){
            output_error($code_result);
        }
        $member_model=Model("member");
        $ret = $member_model->editPassword('',$mobile,$password);
        if($ret){
            output_data(array());
        }else{
            output_error('重置密码失败!');
        }
    }

    /*微信跳转*/
    public function wxjumpOp(){
        $code = $_GET['code'];
        if($code){
            try{
                $wx = new weixin();
                $wx_auth = $wx->get_member_auth($code);
                $openid = $wx_auth['openid'];
                $model_weixin_bind = Model('weixin_bind');
                $bind_info = $model_weixin_bind->check_bind($openid);
                if($bind_info){
                    //登陆
                    $model_member = Model('member');
                    $array['member_id'] = $bind_info['member_id'];
                    $member_info = $model_member->getMemberInfo($array);
                    if(!empty($member_info)) {
                        $model_member	= Model('member');
                        $model_member->createSession($member_info);
                        $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'wap');
                        if($token) {
                            output_data(array('username' => $member_info['member_name'], 'key' => $token));
                        }
                    }
                }else{
                    $wx_info = $wx->get_member_info($wx_auth['openid'],$wx_auth['access_token']);
                    $wx_info['access_token'] = $wx_auth['access_token'];
                    output_data(base64_encode(serialize($wx_info)));
                }
            }catch (Exception $e){
                //echo $e->getMessage();
            }
        }
        echo output_error('出错啦！');
    }

    /*微信号绑定*/
    public function wxbindOp(){
        if(chksubmit()){
            try{
                $info = unserialize(base64_decode(urldecode($_POST['wxinfo'])));
                if(empty($_SESSION['member_id'])) {
                    output_error('绑定失败');
                }
                $wx = new weixin();
                $wx_info = $wx->get_member_info($info['openid'],$info['access_token']);
                if($wx_info){
                    if($_SESSION['member_id']) {
                        //添加绑定
                        $insert =array();
                        $insert['member_id'] = $_SESSION['member_id'];
                        $insert['openid'] = $wx_info['openid'];
                        $insert['nickname'] = $wx_info['nickname'];
                        $insert['sex'] = $wx_info['sex'];
                        $insert['city'] = $wx_info['city'];
                        $insert['province'] = $wx_info['province'];
                        $insert['country'] = $wx_info['country'];
                        $insert['headimgurl'] = $wx_info['headimgurl'];
                        $insert['privilege'] = serialize($wx_info['province']);

                        $model_weixin_bind = Model('weixin_bind');
                        if($model_weixin_bind->add_bind($insert)){
                            output_data('绑定成功');
                        }
                    }
                }
            }catch (Exception $e){
            }
            output_error('绑定失败');
        }else{
            $info = unserialize(base64_decode($_GET['wxinfo']));
            if($info){
                output_data($info);
            }else{
                output_error('error');
            }
        }
    }
    public function AppCheckLoginOp(){
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfo(array('member_id'=>$_GET['member_id']));
        if(!empty($member_info)) {
            $_SESSION['is_login']=1;
            $_SESSION['member_id']=$member_info['member_id'];
            $token = $this->_get_token($member_info['member_id'], $member_info['member_name'], 'wap');
            $model_mb_user_token = Model('mb_user_token');
            $data=array();
            $data['member_id']=$member_info['member_id'];
            $data['member_name']=$member_info['member_name'];
            $data['token']=$token;
            $data['login_time']=TIMESTAMP;
            $data['client_type']='wap';
            if($model_mb_user_token->addMbUserToken($data)){
                echo json_encode(array('no'=>0,'msg'=>'success','key' => $token));
                exit;
            }else{
                echo json_encode(array('no'=>0,'msg'=>'token入库失败','key' => $token));
                exit;
            }
        } else {
            echo json_encode(array('no'=>1,'msg'=>'用户id没有对应信息'));
            exit;
        }
    }

}
