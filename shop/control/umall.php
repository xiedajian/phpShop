<?php
/**
 * 前台登录 退出操作
 *
 *
 *
 ***/


defined('InShopNC') or exit('Access Invalid!');

class umallControl extends BaseHomeControl {

    public function showmsgOp(){
        showMessage($_REQUEST['msg'],$_REQUEST['ref_url']?$_REQUEST['ref_url']:'');
    }
    public  function  umall_updateUser_apiOp(){
        $data = $_REQUEST['data'];
        $member = data_decode($data);
        var_dump($member);
        //通过member_
        $member_id = $member['ipvp_member_id'];
        //更新该用户的密码
        $member_model=Model("member");
        $ret = $member_model->editMember(array('member_id'=>$member_id),array('member_passwd'=>$member['password']));
        if($ret){
            echo json_encode(array('status' => 0, 'msg' => '修改成功'));
            exit;
        }else{
            echo json_encode(array('status' => 2, 'msg' => '修改失败'));
            exit;
        }
    }

    //同步umall用户注册到ipvp
    public function umall_register_apiOp(){
    	
    	
        $data = $_REQUEST['data'];
        $member = data_decode($data);
        $sync = $member['sync'];//只关联用户不添加
        $member = user_umall_to_ipvp($member);
        if(!$member){
            echo json_encode(array('status' => 1, 'msg' => '参数错误'));
            exit;
        }
        $member_model=Model("member");
        $condition = array();
        $condition['member_name|member_mobile'] =$member['member_mobile'];
        $member_info = $member_model->getMemberInfo($condition,'member_id');

        if ($member_info) {
            $data = array();
            $data['member_group']=$member['member_group'];
            if($member['member_passwd']){
                $data['member_passwd'] = $member['member_passwd'];
            }

            if($member_model->editMember(array('member_id'=>$member_info['member_id']),$data)){
                echo json_encode(array('status' => 0, 'msg' => '注册成功','member_id'=>$member_info['member_id']));
                exit;
            }
        }
        if($sync){
            echo json_encode(array('status' => 5, 'msg' => '没找到关联数据'));exit;
        }
        $member_id = $member_model->addMemberf($member);
        if(!$member_id){
            echo json_encode(array('status' => 2, 'msg' => '注册失败'));
            exit;
        }
        // 添加默认相册
        $insert['ac_name']      = '买家秀';
        $insert['member_id']    = $member_id;
        $insert['ac_des']       = '买家秀默认相册';
        $insert['ac_sort']      = 1;
        $insert['is_default']   = 1;
        $insert['upload_time']  = TIMESTAMP;
        Model('sns_album')->addSnsAlbumClass($insert);
        //返回member_id
        echo json_encode(array('status' => 0, 'msg' => '注册成功','member_id'=>$member_id));
    }

    //umall ipvp 已有用户同步
    public function data_syncOp(){
        set_time_limit(0);
        echo '<meta charset="utf-8">';
        $member_model=Model("member");
        $member_list = $member_model->table('member')->limit(99999)->select();

        echo '同步用户总数：'.count($member_list).'</br>';

        $succ_count = 0;
        $member_ids = array();
        foreach($member_list as $member){
            $user = array();
            $user['member_id'] = $member['member_id'];
            $user['member_name'] = $member['member_name'];
            $user['member_mobile'] = $member['member_mobile'];
            $user['member_passwd'] = $member['member_passwd'];
            $ret = add_umall_user($user);
            if($ret['status']!==0){
                echo '用户'.$member['member_id'].'同步失败，status='.$ret['status'].',msg='.$ret['msg'].'</br>';
            }else{
                if(!$ret['sync']){//新增用户
                    $member_ids[] = $member['member_id'];
                }
                $succ_count++;
            }
        }
        echo 'ipvp==>umall用户同步成功：'.$succ_count.'条</br>';

        //同步店铺
        echo '同步店铺总数：'.count($member_list).'</br>';
        $succ_count = 0;
        $store_list = $member_model->table('store')->field('member_id,store_name,store_id')->limit(99999)->select();
        foreach($store_list as $store){
            if(in_array($store['member_id'],$member_ids)){
                $data = array();
                $data['ipvp_member_id'] = $store['member_id'];
                $data['company'] = $store['store_name'];
                $ret = add_umall_store($data);
                if($ret['status']!==0){
                    echo '店铺'.$store['store_id'].'同步失败，status='.$ret['status'].',msg='.$ret['msg'].'</br>';
                }else{
                    $succ_count++;
                }
            }
        }
        echo 'ipvp==>umall店铺同步成功：'.$succ_count.'条</br>';exit;

    }

}
