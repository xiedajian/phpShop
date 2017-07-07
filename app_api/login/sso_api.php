<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 15-10-23
 * Time: 上午10:20
 */



include_once 'aes.php';

function _encode($string = '',$key = ''){
    return AESEncryptCtr($string,$key,128);
}

function _decode($string = '',$key = ''){
    return AESDecryptCtr($string,$key,128);
}

function sync_login($callback = ''){
    $param = array();
    $param['session_id'] = session_id();
    $data = serialize($param);
    $data = _encode($data,SSO_APP_KEY);
    $callback = urlencode($callback);
    $data = base64_encode($data);
    $url = SSO_AUTH_API.'?m=sso&c=sso&a=sync_login&callback='.$callback.'&app_id='.SSO_APP_ID.'&data='.$data;
    return $url;
    //header('location:'.$url);
}
function getUserInfo($ticket){
    if(empty($ticket))return array();
    $ticket = base64_decode($ticket);
    $ticket = _decode($ticket,SSO_APP_KEY);
    $ticket_info = unserialize($ticket);
    if($ticket_info['session_id']!=session_id()){
        return '参数错误';
    }
    if($ticket_info['app_id']!=SSO_APP_ID){
        return '参数错误';
    }
    if($ticket_info['time']<time()-60*5){
        return '参数过期';
    }

    $param = array();
    $param['ticket_id'] = $ticket_info['ticket_id'];
    $data = serialize($param);
    $data = _encode($data,SSO_APP_KEY);
    $data = base64_encode($data);
    $url = SSO_AUTH_API."?m=sso&c=sso&a=get_user_info&app_id=".SSO_APP_ID."&data=".$data;
    $ret = file_get_contents($url);
    $ret = _decode($ret,SSO_APP_KEY);
    $ret = unserialize($ret);
    return $ret;
}

function sync_logout($data){
    $data = base64_decode($data);
    $data = _decode($data,SSO_APP_KEY);
    $data = unserialize($data);
    if($data['session_id']){
        $session_path = session_save_path();
        $file = $session_path.'/sess_'.$data['session_id'];
        if(file_exists($file)){
           return unlink($file);
        }
    }
}

