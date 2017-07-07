<?php
/**
 * 任务计划 - 小时执行的任务
 *
 *
 *
 *
 
 */
defined('InShopNC') or exit('Access Invalid!');

class mobileaddressControl extends BaseCronControl {
    /**
     * 默认方法
     */
    public function indexOp() {
        //更新全文搜索内容
        $this->_xs_update();
    }

    /**
     * 批量更新手机归属地
     */
    public function _xs_update() {
        $M = Model('member');
        $condition = array();
        $condition['mobile_address'] = '';
        /*$condition['LENGTH(member_name)'] = 11;*/

        $memberslist = $M->getMemberList($condition,"member_id,member_name", 0, 'member_id desc', '20');

        require_once(BASE_DATA_PATH .'/api/utils/mobile_address.php');
        foreach ($memberslist as $member){
            if(preg_match('/1\d{10}$/',$member["member_name"]))
            {
                $address=get_mobile_address($member["member_name"]);

                $result = $M->editMember(array('member_id'=>$member["member_id"]),array('mobile_address'=>$address));
                /*if (!$result) {
                    $this->log("更新用户“".$member["member_name"]."”号码归属地失败".$address);
                }*/
                sleep(1);
            }
            else
                $result = $M->editMember(array('member_id'=>$member["member_id"]),array('mobile_address'=>'.'));
        }
    }
}
