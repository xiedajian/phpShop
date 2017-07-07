<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 2016/7/15
 * Time: 14:25
 */
include_once BASE_PATH.'/auth/sso_api.php';
class loginControl extends BaseControl{

    private $ossAuthUrl;
    public function __construct(){
        parent::__construct();
        $this->ossAuthUrl = sync_login();
    }

    public function loginOp(){

        $username = trim($this->POST['username']);
        $password = trim($this->POST['password']);
        $str = post($this->ossAuthUrl , array(
            'user_name' => 'lxr',
            'password' => '123123'
        ));
        $result = json_decode($str, true);
        if($result['no'] === 1) {

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
        }
        echo $str;
    }

}

function post($url, $post_data = array() , $isToken = false ,$time_out = 10){//curl
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
var_dump($url);
    return $result;
}