<?php
class mobile_codeModel extends Model {

	public function __construct(){
		parent::__construct('mobile_code');
	}
	
	public function getMobileCode($type,$mobile,$invalid_time=1800){
		$request_ip=getIp();
		if($this->table('mobile_code')->where(array('request_ip'=>$request_ip,'createtime'=>array('gt',TIMESTAMP-60)))->count()>3){
			//防刷
			return false;
		}
		$mobile_code_info=$this->table('mobile_code')->field('*')->where(array('mobile'=>$type.$mobile))->find();
		if(empty($mobile_code_info)){
			$code=rand(100000,999999); 
		    $this->table('mobile_code')->insert(array('mobile'=>$type.$mobile,'code'=>$code,'createtime'=>TIMESTAMP,'invalid_time'=>$invalid_time,'request_ip'=>$request_ip));
		    return $code;
		}else{
			if((TIMESTAMP-$mobile_code_info['createtime'])>$invalid_time){
				$code=rand(100000,999999);
				$this->table('mobile_code')->where(array('mobile'=>$mobile_code_info['mobile']))->update(array('code'=>$code,'createtime'=>TIMESTAMP,'invalid_time'=>$invalid_time,'request_ip'=>$request_ip));
				return $code;
			}
			return $mobile_code_info['code'];
		}
	}
	
	public function checkMobileCode($type,$mobile,$code,$invalid_time=1800){
		$mobile_code_info=$this->table('mobile_code')->field('*')->where(array('mobile'=>$type.$mobile))->find();
		if(empty($mobile_code_info)){
			return "未发送验证码";
		}
		if($invalid_time>0){
			if((TIMESTAMP-$mobile_code_info['createtime'])>$invalid_time){
			    return "验证码已过期";
			}
		}
		if($mobile_code_info['code']==$code){
			return true;
		}else{
			return "验证码不正确";
		}
	}
	
	
}