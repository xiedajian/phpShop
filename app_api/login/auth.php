<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/7/15
 * Time: 14:25
 */
define('InShopNC',true);
error_reporting(E_WARNING);
include_once '../config.php';
include_once 'sso_api.php';
include_once '../../data/config/config.ini.php';
include_once '../MyRedis.php';
session_start();
$ossAuthUrl = sync_login();
$ac = isset($_GET['act']) ? trim($_GET['act']) : '';

if($ac === 'login'){
    $POST = json_decode(file_get_contents('php://input'),true);

    $username = trim($POST['username']);
    $password = trim($POST['password']);

    $result = post1($ossAuthUrl, array(
        'user_name' => $username,
        'password' => $password
    ));
    $result = json_decode( $result );

    if($result->no === 1) {
        $userInfo = getUserInfo($result->data);
        if($userInfo['member_id']){
            $userInfo['member_avatar'] = $userInfo['member_avatar'] ? $config['upload_site_url'].'/shop/avatar/'.$userInfo['member_avatar'] : '';
            unset ($userInfo['member_passwd']);
            unset($userInfo['member_paypwd']);
           // $db = new mysqli($config['db']['1']['dbhost'],$config['db']['1']['dbuser'],$config['db']['1']['dbpwd'],$config['db']['1']['dbname'],$config['db']['1']['dbport']);
           // $r = $db->query("select * from 33hao_member where member_id='{$userInfo['member_id']}'"); //获取
        //    $member_token = $redis->get($userInfo['member_id']);
            //$r = $r->fetch_array(MYSQLI_ASSOC);
//            $token = '';
       //     if($member_token){
      //          $token = $member_token;
      //      }else{
            //var_dump($r);
            //链接redis
            $user_info_token=array(
                'member_id'=>$userInfo['member_id'],
                'member_name'=>$userInfo['member_name'],
                'member_truename'=>$userInfo['member_truename'],
                'member_avatar'=>$userInfo['member_avatar'],
                'member_mobile'=>$userInfo['member_mobile'],
                'org_id' => $userInfo['org_id'],
                'member_mobile_bind'=>$userInfo['member_mobile_bind'],
                'login_device' =>$_SERVER['HTTP_USER_AGENT']
            );
            $redis = new MyRedis(REDIS_CONN_HOST,REDIS_CONN_PORT);
            $token = $redis->access_token($userInfo['member_id'],json_encode($user_info_token));
               // $db->query("insert into 33hao_app_token(user_id,token,create_time) VALUES ({$userInfo['member_id']}, '$token' ,$createTime) ON DUPLICATE KEY UPDATE create_time=$createTime");
       //     }
            echo json_encode(array(
                'no' => 1,
                'data' => array('auth_key'=>$token,'userdata'=>$userInfo)
            ));
        }else{
            echo json_encode(array(
                'no' => 1002,
                'data' => '用户资料获取失败'
            ));
        }
    }else{
        echo json_encode(array(
            'no' => 1001,
            'data' => '账号或者密码错误'
        ));
    }
//        $postData = json_encode(array(
//            "id" => 0,
//            "orgid" => 1,
//            "username" => '213',
//            "employeeid" =>   213,
//            "agent" => '21321',
//            "ip" => '232',
//            "createtime" => '2016-01-01T00:00:00.00+08:00',
//            "systemid" => 213
//        ));
//
//        var_dump(post('http://192.168.1.20:8080/v1/auth/loginlogforipvp/', $postData ,true));
   // }


}else if ($ac === 'logout'){
    
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