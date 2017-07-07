<?php
/**
 * 我的订单
 *
 *
 *
 *

 */

//use Shopnc\Tpl;

defined('InShopNC') or exit('Access Invalid!');

include_once dirname(__FILE__).'/../api/payment/fuiou/fuiou.php';

class bankControl extends mobileMemberControl {

    public function __construct(){
        parent::__construct();
    }

    /**
     * 添加新卡
     */
    public function add_cardOp(){
        $uid = $this->member_info['member_id'];
        $name = $_REQUEST['name'];
        $sfz = $_REQUEST['sfz'];
        $no = $_REQUEST['card_no'];
        $payClass = new fuiou();
        $ret = $payClass->queryCard($no);
        if($ret['error']!=0){
            echo json_encode($ret);exit;
        }
        if(!$this->isCreditNo($sfz)){
            echo json_encode(array('error'=>1,'errorMsg'=>'身份证号码不正确'));exit;
        }

        if(Model('bank_card')->table('bank_card')->where(array('member_id'=>$uid,'bank_card_no'=>$no))->find()){
            echo json_encode(array('error'=>1,'errorMsg'=>'该卡已存在'));exit;
        }
        $data = array();
        $data['member_id'] = $uid;
        $data['name'] = $name;
        $data['idcard'] = $sfz;
        $data['bank_name'] = $ret['Cnm'];
        $data['bank_card_no'] = $no;
        $data['type'] = $ret['Ctp'];
        $data['bank_no'] = $ret['InsCd'];
        $data['add_time'] = time();
        if($card_id = Model('bank_card')->table('bank_card')->insert($data)){
            echo json_encode(array('error'=>0,'errorMsg'=>'添加成功','id'=>$card_id));exit;
        }else{
            echo json_encode(array('error'=>1,'errorMsg'=>'添加失败','id'=>$card_id));exit;
        }
    }

    public function del_cardOp(){
        $id = intval($_REQUEST['id']);
        $uid = $this->member_info['member_id'];
        echo Model('bank_card')->table('bank_card')->where(array('id'=>$id,'member_id'=>$uid))->delete()>0?1:0;
        exit;
    }

    public function card_listOp(){
        $type_txt = array(
            '01' =>  '借记卡',
            '02' =>  '信用卡',
            '03' =>  '准贷记卡',
            '04' =>  '富友卡',
            '05' =>  '非法卡号',
        );
        $uid = $this->member_info['member_id'];
        $card_list = Model('bank_card')->table('bank_card')->where(array('member_id'=>$uid))->select();
        foreach($card_list as &$val){
            $val['type_txt'] = $type_txt[$val['type']];
            $val['card_no'] = $this->card_no($val['bank_card_no']);
        }
        output_data(array('card_list'=>$card_list));
    }

    public function check_cardOp(){
        $cardNo = $_REQUEST['no'];
        $uid = $this->member_info['member_id'];
        if(Model('bank_card')->table('bank_card')->where(array('member_id'=>$uid,'bank_card_no'=>$cardNo))->find()){
            echo json_encode(array('error'=>1,'errorMsg'=>'该卡已存在'));exit;
        }
        $payClass = new fuiou();
        $ret = $payClass->queryCard($cardNo);
        echo json_encode($ret);
        exit;
    }

    private function card_no($card_no){
//        $card_no = str_repeat('*',strlen($card_no)-4).substr($card_no,-4,4);
//        return substr($card_no,0,4).' '.substr($card_no,4,4).' '.substr($card_no,8,4).' '.substr($card_no,12,4).' '.substr($card_no,16);
        //$card_no = '<div style="position: absolute;top: 3px;right: 42px;">**** **** ****&nbsp;</div><div style="">'.substr($card_no,-4,4).'</div>';
        $card_no = substr($card_no,-4,4);
        return $card_no;
    }

    /**
     * 身份证号码是否正确
     * @param $vStr
     * @return bool
     */
    private function isCreditNo($vStr)
    {
        $vCity = array(
            '11','12','13','14','15','21','22',
            '23','31','32','33','34','35','36',
            '37','41','42','43','44','45','46',
            '50','51','52','53','54','61','62',
            '63','64','65','71','81','82','91'
        );

        if (!preg_match('/^([\d]{17}[xX\d]|[\d]{15})$/', $vStr)) return false;

        if (!in_array(substr($vStr, 0, 2), $vCity)) return false;

        $vStr = preg_replace('/[xX]$/i', 'a', $vStr);
        $vLength = strlen($vStr);

        if ($vLength == 18)
        {
            $vBirthday = substr($vStr, 6, 4) . '-' . substr($vStr, 10, 2) . '-' . substr($vStr, 12, 2);
        } else {
            $vBirthday = '19' . substr($vStr, 6, 2) . '-' . substr($vStr, 8, 2) . '-' . substr($vStr, 10, 2);
        }

        if (date('Y-m-d', strtotime($vBirthday)) != $vBirthday) return false;
        if ($vLength == 18)
        {
            $vSum = 0;

            for ($i = 17 ; $i >= 0 ; $i--)
            {
                $vSubStr = substr($vStr, 17 - $i, 1);
                $vSum += (pow(2, $i) % 11) * (($vSubStr == 'a') ? 10 : intval($vSubStr , 11));
            }

            if($vSum % 11 != 1) return false;
        }

        return true;
    }

}
