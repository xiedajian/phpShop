<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 15-10-27
 * Time: 上午9:27
 */


function user_ipvp_to_umall($member){
    if(empty($member['member_name'])||empty($member['member_mobile'])||empty($member['member_passwd'])||empty($member['member_id'])){
        return null;
    }
    $user = array();
    $user['nickname'] = $member['member_name'];
    $user['phone'] = $member['member_mobile'];
    $user['password'] = $member['member_passwd'];
    $user['ipvp_member_id'] = $member['member_id'];
    return $user;
}

function user_umall_to_ipvp($user){
    if(empty($user['phone'])||empty($user['nickname'])||empty($user['password'])||empty($user['group'])){
        return null;
    }
    $member = array();
    $member["member_mobile"] = $user['phone'];
    $member["member_name"] = $user['nickname'];
    $member["member_truename"] = $user['nickname'];
    $member["member_passwd"] = $user['password'];
    $member["member_mobile_bind"] = 1;
    $member['register_client']='1';
    $member['member_group']= $user['group'];
    //获取手机IP归属地
    require_once(BASE_DATA_PATH .'/api/utils/mobile_address.php');
    $member["mobile_address"] = get_mobile_address($user['phone']);
    if($user['sync']){
        if($user['password']){
            $member['member_passwd'] = $user['password'];
        }
    }
    return $member;
}

function data_encode($data){
    $data = json_encode($data);
    $data = base64_encode($data);
    return $data;
}
function data_decode($data){
    $data = base64_decode($data);
    $data = json_decode($data,true);
    return $data;
}



function add_umall_user($member){
    $url = UMALL_SITE_URL.'/user.php?c=ipvp&a=ipvp_register_api';
    $ret = file_get_contents($url.'&data='.data_encode($member));
    //umall 输出bug 处理
    $b = strpos($ret,'{');
    $end = strrpos($ret,'}');
    $ret = substr($ret,$b,$end-$b+1);
    $ret = json_decode($ret,true);
    return $ret;
}

function add_umall_store($store){
    $url = UMALL_SITE_URL.'/user.php?c=ipvp&a=ipvp_addstore_api';
    $ret = file_get_contents($url.'&data='.data_encode($store));
    //umall 输出bug 处理
    $b = strpos($ret,'{');
    $end = strrpos($ret,'}');
    $ret = substr($ret,$b,$end-$b+1);
    $ret = json_decode($ret,true);
    return $ret;
}