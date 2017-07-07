<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/7/18
 * Time: 15:40
 */
defined('InShopNC') or exit('Access Invalid!');
if (!@include(BASE_PATH.'/MyRedis.php')) exit('MyRedis.php isn\'t exists!');
if (!@include(BASE_PATH.'/config.php')) exit('config.php isn\'t exists!');
class BaseAPPControl{
    protected $POST;
    public function __construct(){
        $this->POST = json_decode(file_get_contents('php://input'),true);
        $this->currentUser = array();
        if($_SERVER['HTTP_IPVP_APP_TOKEN']){
            //链接redis
            $redis = new MyRedis(REDIS_CONN_HOST,REDIS_CONN_PORT);
            $user_info = $redis->get($_SERVER['HTTP_IPVP_APP_TOKEN']);
            if(!$user_info){
                $this->returnJson(3825,'您太久没有登录或已在其他登陆，请重新登陆');
            }else{
//                $ttl_time = $redis->ttl($_SERVER['HTTP_IPVP_APP_TOKEN']); //redis的剩余生存期
//                if($ttl_time <=24*60*60){
//                    $_SERVER['HTTP_IPVP_APP_TOKEN'] = $redis->access_token($member_id);
//                }
                $this->currentUser = json_decode($user_info,true);
            }
//            $m = new Model();
//            $r=$m->table('member')->where(array('member_id'=>$member_id))->find();
           // $r = $m->table('app_token,member')->join('inner')->on('app_token.user_id = member.member_id')->where(array('token'=>$_SERVER['HTTP_IPVP_APP_TOKEN']))->find();
//            if($r){
////                unset ($r['member_passwd']);
//                $this->currentUser = $r;
//            }else{
//                $this->returnJson(3825,'你的账号已在其他登陆，请重新登陆');
//            }
        }else{
           // $this->returnJson(3825,'你的账号已下线，请重新登陆');
        }
    }
    
    protected function returnJson($no,$msg,$data=''){
        echo json_encode(array('no'=>$no,'msg'=>$msg,'data'=>$data));
        exit;
    }

    /**
     * 输出
     * @param $msg
     * @param int $code 成功0，失败1，参数错误10
     * @param array $data
     */
    public function output($msg,$code = 0,$data = array()){
        echo json_encode(array(
            'code'  =>  $code,
            'msg'   =>  $msg,
            'data'  =>  $data
        ));
        exit;
    }

    public function  edit_token($member_id,$userInfo){
        $redis = new MyRedis(REDIS_CONN_HOST,REDIS_CONN_PORT);
        $user_token = $redis->get($member_id);
        if(!$user_token){
            $this->returnJson(3825,'您太久没有登录或已在其他登陆，请重新登陆');
        }else{
            $user_info_token=array(
                'member_id'=>$userInfo['member_id'],
                'member_name'=>$userInfo['member_name'],
                'member_truename'=>$userInfo['member_truename'],
                'member_avatar'=>UPLOAD_SITE_URL.'/'.ATTACH_AVATAR.'/'.$userInfo['member_avatar'],
                'member_mobile'=>$userInfo['member_mobile'],
                'org_id' => $userInfo['org_id'],
                'member_mobile_bind'=>$userInfo['member_mobile_bind'],
                'login_device' =>$_SERVER['HTTP_USER_AGENT']
            );
            $redis->set_expire($member_id,$user_token,24*60*60*7);  //设置一天过期
            $redis->set_expire($user_token,json_encode($user_info_token),24*60*60*7);
        }
    }
}