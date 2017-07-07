<?php

/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2016/8/1
 * Time: 11:07
 */


class registerControl extends BaseAPPControl {
    protected $POST;
    public function __construct(){
        $this->POST = json_decode(file_get_contents('php://input'),true);
    }


    //发送验证短信
    public function send_codeOp(){
        $mobile_no=$_GET["mobile"];
        $type = $_GET['type'];  //register_mobile_code      reset_password_code  modify_mobile
        $tpl = array(
            'register_mobile_code'    =>  'register_mobile_code',
            'reset_password_code'      =>  'reset_password_code',
            'modify_mobile'      =>  'modify_mobile',
        );
        if(!preg_match("/^1\d{10}$/",$mobile_no)){
            $this->output('手机号格式错误！',1);
        }

        //验证手机是否被注册
        $member_model = Model('member');
        $condition = array();
        $condition['member_name|member_mobile'] =$mobile_no;
        $member_id= $member_model->getMemberInfo($condition,'member_id');
        if ($member_id) {
            //已经注册  注册账号时报错
            if ($type=='register_mobile_code'){
                $this->output('手机号已经注册', 303, '');
            }
        }else{
            //还没有已经注册  忘记密码时报错
            if ($type=='reset_password_code'){
                $this->output('手机号还没有注册', 303, '');
            }
        }


        //是否发送频繁    在手机端验证
/*        if($data){
            $pre_send_time=$data["time"];
            if(TIMESTAMP-$pre_send_time<60){//时间过短  在一分钟内发送
                $this->output('验证码发送太过频繁了！',1);
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
        }*/
        
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
            $result = array();
            $result['mobile'] = $mobile_no;                  //手机
            $result['countDownSeconds'] = 60;                 //过期时间
            $result['send_time'] =TIMESTAMP ;                //发送时间
            $result['mobile_code'] = $data["code"];         //验证码
            $result['type'] =$type;             //验证码类型

            $this->output('发送成功！',0,$result);
        }else{
            $this->output('发送失败！',1);
        }

    }

    //检查手机是否被注册过
    public function check_mobileOp(){
        $mobile=$_GET["mobile"];
        $member_model=Model("member");
        $condition = array();
        $condition['member_name|member_mobile'] =$mobile;
        $member_info = $member_model->getMemberInfo($condition,'member_id');
        if ($member_info) {
            $this->output('手机号已存在',303,'error');
        }
        $this->output('手机号可用',0,'');
    }
    /**
     * 用户注册
     */
    public function registerOp(){

        $mobile=$this->POST['mobile'];
        $new_pwd=$this->POST['new_pwd'];
        $true_name=$this->POST['true_name'];
        if(!preg_match("/^1\d{10}$/",$mobile)){
            $this->output('手机号格式错误！',1);
        }

        $member_model = Model('member');
        $condition = array();
        $condition['member_name|member_mobile'] =$mobile;
        $member_id= $member_model->getMemberInfo($condition,'member_id');
        if ($member_id) {
            //已经注册  报错
            $this->output('手机号已经注册',303,$member_id);
        }else{

            //注册新用户
            $par=array();
            $par['member_name']=$mobile;
            $par['member_truename']=$true_name;
            $par['member_passwd']=$new_pwd;
            $par['member_mobile']=$mobile;
            $par['member_mobile_bind']=1;
            $par['member_email_bind']=0;
            $par['member_sex']='';
            $par['member_avatar']='';
            $par['register_client']=0;
            $par['mobile_address']='';
            $par['member_group']=0;
            $member_info=$member_model->addMember($par);
            //$member_info=$member_model->addMemberf($par);
            if($member_info){
                $this->_sendMsgTO($mobile,1);//发送短信给张涛
                //注册成功
                $this->output('注册成功',0,$member_id);
            }else{
                //注册失败
                $this->output('注册失败,请重试',1,$member_id);
            }
        }
        
    }
    /**
     * 发送短信给张涛
     * 短信模板 【拼单网】Hi#name#，客户（#customer_mobile#）成功注册店满分！
     *          【拼单网】Hi#name#，客户（#customer_mobile#）申请开通店铺指数！
     */
    private function _sendMsgTO($customer_mobile,$type)
    {
        $mobile_no='18086220999';//张涛手机号
        if(!preg_match("/^1\d{10}$/",$mobile_no)){
            return 3;
        }
        if($type==1){
            $message='【拼单网】Hi张涛，客户（'.$customer_mobile.'）成功注册店满分！';//短信内容
        }else{
            $message='【拼单网】Hi张涛，客户（'.$customer_mobile.'）申请开通店铺指数！';//短信内容
        }
        $sms = new Sms();
        if($sms->send($mobile_no,$message)){
            return 1;
        }else{
            return 2;
        }
    }
    /**
     * 申请店铺指数
     */
    public function ApplyDataCubeOp()
    {
        $customer_mobile=$_GET['mobile'];
        $r=$this->_sendMsgTO($customer_mobile,2);
        if($r==1){
            $this->output('已提交申请',0,array());
        }else if($r==2){
            $this->output('申请提交失败，请重试',1,array('mobile'=>$customer_mobile));
        }else{
            $this->output('手机格式错误',1,array('mobile'=>$customer_mobile));
        }
    }
    //重置登录密码
    public function resetPwdOp()
    {
        $member_model = Model('member');
        $mobile=$this->POST['mobile'];
        $new_pwd=$this->POST['new_pwd'];
        if(!preg_match("/^1\d{10}$/",$mobile)){
            $this->output('手机号格式错误！',1);
        }

        $condition = array();
        $condition['member_name|member_mobile'] =$mobile;
        $member_id= $member_model->getMemberInfo($condition,'member_id');
        if ($member_id) {
            //if($member_model->editMember(array('member_id' => $member_id), array('member_passwd' => md5($new_pwd)))){
            if($member_model->editPassword('',$mobile, $new_pwd)){
                $this->output('修改成功',0 ,$member_id);
            }
            $this->output('修改失败',1 ,$member_id);
        }else{
            $this->output('账户不存在',303,'error');
        }
    }
    /**
     *  检查客满分的服务是否到期
     *      org_id  店铺id
     */
    public function checkKMF_expiredOp()
    {
	    $checkUrl = 'http://crm.ipvp.cn/YtxApp/YtxApi/GetMemberId';
        $member_id=$_GET['org_id'];
	    $result = post1($checkUrl, array(
		    'orgId' => $member_id
	    ));
	    $result = json_decode( $result );
	    $member_id = $result->Data;
	    $licences=Model('licences');
        $licences_company_list=$licences->getlicences_company(intval($member_id));
        foreach ($licences_company_list as $k =>$v){
            if($licences_company_list[$k]['app_code']=='KMF'){
                //如果客满分超期
                if($this->daysBetweenOp($licences_company_list[$k]['expire_date'])){
                    $this->output('expired',0,'');//超期
                }else{
                    $this->output('no_expired',1,'');//没有超期
                }
            }
        }
        $this->output('no_info',303,'');//没有相关信息
    }
    /**
     *  判断当前日期与指定日期
     */
    private function daysBetweenOp($expire_date){
        $second1 = strtotime(date('Y-m-d',time()));
        log::record($second1);
        $second2 = strtotime(substr($expire_date,0,10));
        log::record($second2);
        if ($second1>$second2){
            return true;//超期
        }else{
            return false;//没有超期
        }

    }

    

}
function post($url, $post_data = '' , $time_out){//curl

	$postData = http_build_query($post_data);
	$options = array(
		'http' => array(
			'method' => 'POST',
			'header' => 'Content-type:application/x-www-form-urlencoded',
			'content' => $postData,
			'timeout' => $time_out // 超时时间（单位:s）
		)
	);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);

	return $result;
}
function post1($url, $post_data = array() , $isToken = false ,$time_out = 10){//curl
	$postData = is_array($post_data) || is_object($post_data) ? http_build_query($post_data) : $post_data;
	$headerToken = '';
	if($isToken){
		$secretKey = '32213c157b6491e6c7b7ccea09e8eeae';
		$token = sha1($secretKey . $postData);
		$headerToken = "\r\nX-Loginlog-Token:$token";
	}

	$options = array(
		'http' => array(
			'method' => 'POST',
			'header' => "Content-type:application/x-www-form-urlencoded$headerToken",
			'content' => $postData,
			'timeout' => $time_out // 超时时间（单位:s）
		)
	);
	$context = stream_context_create($options);
	$result = file_get_contents($url, false, $context);
	return $result;
}