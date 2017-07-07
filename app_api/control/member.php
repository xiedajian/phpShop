<?php

/**
 * Created by PhpStorm.
 * User: ygy
 * Date: 2016/8/1
 * Time: 11:07
 */


class memberControl extends BaseAPPControl {

    public function __construct(){
        parent::__construct();
    }


    //发送验证短信
    public function sendCodeOp(){
        $countDownSeconds = 60;//发送等待时间
        $type = $_GET['type'];
        if(empty($type)){
            $type = 'auth';
        }
        $tpl = array(
            'modify_mobile'    =>  'modify_mobile',
            'auth'      =>  'authenticate'
        );
        if(empty($tpl[$type])){
            $this->output('模板错误',1);
        }
        $model_member = Model('member');
        $member_info = $model_member->getMemberInfoByID($this->currentUser['member_id'],'member_email,member_mobile');
        $mobile = $member_info['member_mobile'];
        $verify_code = rand(100,999).rand(100,999);
        header('code:'.$verify_code);
        $data = array();
        $data['auth_code'] = $verify_code;
        $data['send_acode_time'] = TIMESTAMP;
        if(!$model_member->getMemberCommonInfo(array('member_id'=>$this->currentUser['member_id'],'send_acode_time'=>array('lt',$data['send_acode_time']-60)))){
            exit(json_encode(array('code'=>'1','msg'=>'验证码发送太频繁，请等待60秒后再发送')));
        }
        $update = $model_member->editMemberCommon($data,array('member_id'=>$this->currentUser['member_id']));
        $model_tpl = Model('mail_templates');
        $tpl_info = $model_tpl->getTplInfo(array('code'=>$tpl[$type]));

        $param = array();
        $param['send_time'] = date('Y-m-d H:i',TIMESTAMP);
        $param['verify_code'] = $verify_code;
        $param['site_name']	= C('site_name');
        $message = ncReplaceText($tpl_info['content'],$param);
        $sms = new Sms();
        $send_succ = $sms->send($member_info["member_mobile"],$message);

        $result = array();
        $result['mobile'] = $mobile;
        $result['countDownSeconds'] = $countDownSeconds;

        $this->output($send_succ?'发送成功':'发送失败',$send_succ?0:1,$result);

    }
    public function checkCodeOp(){
        $model_member = Model('member');
        if (!in_array($this->POST['type'],array('modify_pwd','modify_mobile','modify_email','modify_paypwd','pd_cash'))) {
            $this->output('参数错误',1);
        }
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->currentUser['member_id']));
        if (empty($member_common_info) || !is_array($member_common_info)) {
            $this->output('验证失败',1);
        }
        if ($member_common_info['auth_code'] != $this->POST['auth_code'] || TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
            $this->output('验证码错误',1);
        }
        $data = array();
        $data['auth_code'] = '';
        $data['send_acode_time'] = 0;
        $update = $model_member->editMemberCommon($data,array('member_id'=>$this->currentUser['member_id']));
        if (!$update) {
            $this->output('系统发生错误，如有疑问请与管理员联系',1);
        }
        $_SESSION['auth_'.$this->POST['type']] = TIMESTAMP;
        $this->output('',0);

        $this->output('验证失败',1);
    }


/*    public function checkMobileChangeOp(){
        $model_member = Model('member');
        $member_common_info = $model_member->getMemberCommonInfo(array('member_id'=>$this->currentUser['member_id']));
        if (empty($member_common_info) || !is_array($member_common_info)) {
            $this->output('验证失败',1);
        }
        if ($member_common_info['auth_code'] != $this->POST['auth_code'] || TIMESTAMP - $member_common_info['send_acode_time'] > 1800) {
            $this->output('验证码错误',1);
        }
        $data = array();
        $data['auth_code'] = '';
        $data['send_acode_time'] = 0;
        $update = $model_member->editMemberCommon($data,array('member_id'=>$this->currentUser['member_id']));
        if (!$update) {
            $this->output('系统发生错误，如有疑问请与管理员联系',1);
        }
        $update = $model_member->editMember(array('member_id'=>$this->currentUser['member_id']),array('member_mobile'=>$this->POST['phone'],array('member_mobile_bind'=>1)));
        //修改绑定手机，修改token
        $userinfo = Model('member')->getMemberInfo(array('member_id'=>$this->currentUser['member_id']));
        //获取该用户的token
        $this->edit_token($this->currentUser['member_id'],$userinfo);
        if (!$update) {
            $this->returnJson(0, 'failed','绑定失败');
        }else{
            $this->returnJson(1, 'success','绑定成功');
        }
    }*/
    //修改绑定手机
    public function edit_bind_mobileOp(){
        $POST=$this->POST;
        $mobile=$POST['mobile'];
        $model_member = Model('member');
        $update = $model_member->editMember(array('member_id'=>$this->currentUser['member_id']),array('member_mobile'=>$mobile,'member_name'=>$mobile,'member_mobile_bind'=>1));
        //修改绑定手机，修改token
        $userinfo = $model_member->getMemberInfo(array('member_id'=>$this->currentUser['member_id']));
        //获取该用户的token
        $this->edit_token($this->currentUser['member_id'],$userinfo);
        if (!$update) {
            $this->output('failed',1,'绑定失败');
        }else{
            $this->output('success',0,'绑定成功');
        }
    }
    //修改登录密码
    public function editPasswordOp()
    {
        $POST=$this->POST;
        $old_pwd=$POST['old_pwd'];
        $new_pwd=$POST['new_pwd'];
        if (empty($old_pwd) || empty($new_pwd)){
            $this->output('参数错误',10);
        }
        $member_id=intval( $this->currentUser['member_id']);
        $model_member = Model('member');
        $member_passwd=$model_member->getMemberInfoByID($member_id, 'member_passwd');
        if (md5($old_pwd)!=$member_passwd['member_passwd']){
            $this->output('现登录密码输入错误',1);
        }
        $res=$model_member->editPassword($member_id,'', $new_pwd);
        $this->output($res>0?'修改成功':'修改密码失败',$res>0?0:1);
    }
    //验证登录密码
    public function check_pwdOp()
    {
        $POST=$this->POST;
        $old_pwd=$POST['old_pwd'];
        if (empty($old_pwd)){
            $this->output('参数错误',10);
        }
        $member_id=intval( $this->currentUser['member_id']);
        $model_member = Model('member');
        $member_passwd=$model_member->getMemberInfoByID($member_id, 'member_passwd');
        if (md5($old_pwd)!=$member_passwd['member_passwd']){
            $this->output('现登录密码输入错误',1);
        }
        $this->output('密码正确',0);
    }
    //重置登录密码
    public function resetPasswordOp()
    {
//        if ($_SESSION['auth_modify_pwd'] && TIMESTAMP - $_SESSION['auth_modify_pwd'] < 1800) {
//            unset($_SESSION['auth_modify_pwd']);
            $model_member = Model('member');
            $update = $model_member->editMember(array('member_id' => $this->currentUser['member_id']), array('member_passwd' => md5($this->POST['password'])));
            $this->output($update !== false ? '修改成功' : '修改失败', $update ? 0 : 1);
//        }
//        $this->output('验证失败', 1);
    }

    /**
     * 申请提现
     */
    public function applyForWithdrawOp(){
        $this->POST['money'] = floatval($this->POST['money']);
        $this->POST['money'] = number_format($this->POST['money'],2,'.','');

        $model_pd = Model('predeposit');
        $member_model = Model('member');
        $member = $member_model->getMemberInfo(array('member_id'=>$this->currentUser['member_id']),'available_predeposit');
        if(empty($member)||$member['available_predeposit']<$this->POST['money']){
            $this->output('提现金额错误',1);
        }
        //判断支付密码
        if($member_model->getMemberCount(array('member_id'=>$this->currentUser['member_id'],'member_paypwd'=>md5($this->POST['pass'])))!=1){
            $this->output('支付密码错误',1);
        }
        //判断账户
        if($this->POST['type']===true){
            //已有账户
            $id = intval($this->POST['id']);
            if($id){
                $account = $member_model->table('withdraw_account')->where(array('id'=>$id))->find();
            }
            if(empty($account)){
                $this->output('请选择提现账户',1);
            }
        }else{
            if(empty($this->POST['bank_name'])){ $this->output('收款银行不能为空',1);}
            if(empty($this->POST['account'])){ $this->output('收款账号不能为空',1);}
            if(empty($this->POST['name'])){ $this->output('开户人不能为空',1);}
            $account = $model_pd->getWithdrawAccount(array('member_id'=>$this->currentUser['member_id'],'account'=>$this->POST['account']));
            if(empty($account)){
                //添加账户
                $account['bank_name'] = $this->POST['bank_name'];
                $account['account'] = $this->POST['account'];
                $account['name'] = $this->POST['name'];
                $account['member_id'] = $this->currentUser['member_id'];
	            /*$info = $this->_cardCheck($account['account']);
                $account['bank_no'] = $info['bank_no'];
                $account['type'] = $info['type'];*/
	            $info = Model('bankList')->getbankInfo($account['account']);
                $account['bank_no'] = $info['stylecode'];
                $account['type'] = $info['name'].$info['cardtype'];

                $account['add_time'] = time();
                $result = Model('predeposit')->addWithdrawAccount($account);
            }
        }

        //添加提现申请
        try {
            $model_pd->beginTransaction();
            $pdc_sn = $model_pd->makeSn();
            $data = array();
            $data['pdc_sn'] = $pdc_sn;
            $data['pdc_member_id'] = $this->currentUser['member_id'];
            $data['pdc_member_name'] = $this->currentUser['member_name'];
            $data['pdc_amount'] = $this->POST['money'];
            $data['pdc_bank_name'] = $account['bank_name'];
            $data['pdc_bank_no'] = $account['account'];
            $data['pdc_bank_user'] = $account['name'];
            $data['pdc_add_time'] = TIMESTAMP;
            $data['pdc_payment_state'] = 0;
            $insert = $model_pd->addPdCash($data);
            if (!$insert) {
                throw new Exception(Language::get('predeposit_cash_add_fail'));
            }
            //冻结可用预存款
            $data = array();
            $data['member_id'] = $this->currentUser['member_id'];
            $data['member_name'] = $this->currentUser['member_name'];
            $data['amount'] = $this->POST['money'];
            $data['order_sn'] = $pdc_sn;
            $model_pd->changePd('cash_apply',$data);
            $model_pd->commit();
            $this->output('申请成功',0);
        } catch (Exception $e) {
            $model_pd->rollback();
            $this->output('申请失败',1);
        }
    }

    /**
     * 添加提现账户
     * @param $data
     */
    public function addWithdrawAccountOp(){
        $error = '';
        do{
            if(empty($this->POST['bank_name'])){
                $error = '收款银行不能为空';break;
            }
            if(empty($this->POST['account'])){
                $error = '账号不能为空';break;
            }
            if(empty($this->POST['name'])){
                $error = '开户人不能为空';break;
            }

        }while(false);
        $exist = Model('predeposit')->getWithdrawAccount(array('member_id'=>$this->currentUser['member_id'],'account'=>$this->POST['account']));
        if(!empty($exist)){
            $error = '账户已存在';
        }
        if($error){
            $this->output($error,10);
        }
        /*$info = $this->_cardCheck($this->POST['account']);*/

        $data = array();
        $data['member_id'] = $this->currentUser['member_id'];
        $data['bank_name'] = $this->POST['bank_name'];
        $data['account'] =strval($this->POST['account']);
        $data['name'] = $this->POST['name'];
        $data['add_time'] = time();


	    $info = Model('bankList')->getbankInfo($this->POST['account']);
	    $data['bank_no'] = $info['stylecode'];
	    $data['type'] = $info['name'].$info['cardtype'];

        $result = Model('predeposit')->addWithdrawAccount($data);

	    if(sizeof($data['account']) == 19)
		    $data['account'] = '**** **** **** ***'.substr($data['account'],-4,1).' '.substr($data['account'],-3,3);
	    else
            $data['account'] = '**** **** **** '.substr($data['account'],-4,4);
        $data['id'] = $result;
        $this->output($result>0?'添加成功':'添加失败',$result>0?0:1,$data);
    }

    /**
     * 删除提现账户
     * @param array $condition
     * @return mixed
     */
    public function delWithdrawAccountOp(){
        $id = intval($this->POST['id']);
        if(empty($id)){
        }
        $condition = array();
        $condition['member_id'] = $this->currentUser['member_id'];
        $condition['id'] = $id;
        $result = Model('predeposit')->delWithdrawAccount($condition);
        $this->output($result>0?'删除成功':'删除失败',$result>0?0:1);
    }

    /**
     * 查询提现账户列表
     * @param $member_id
     * @return array
     */
    public function getWithdrawAccountListOp(){
        $result = Model('predeposit')->getWithdrawAccountList($this->currentUser['member_id']);
        foreach($result as &$val){
	        if(strlen($val['account']) == 19)
		        $val['account'] = '**** **** **** ***'.substr($val['account'],-4,1).' '.substr($val['account'],-3,3);
	        else
		        $val['account'] = '**** **** **** '.substr($val['account'],-4,4);
        }
        $this->output('',$result ===false?1:0,$result);
    }

    /**
     * 判断银行卡类型
     * @param $cardNo   卡号
     * @return array
     */
    private function _cardCheck($cardNo){
        require_once('banklist.php');
        $bank = '';
        $type = '';
        $bank_no = 0;
        if(isset($bankList[substr($cardNo,0,6)])){
            $bank = $bankList[substr($cardNo,0,6)];
        }else if(isset($bankList[substr($cardNo,0,8)])){
            $bank = $bankList[substr($cardNo,0,8)];
        }else if(isset($bankList[substr($cardNo,0,4)])){
            $bank = $bankList[substr($cardNo,0,4)];
        }else if(isset($bankList[substr($cardNo,0,5)])){
            $bank = $bankList[substr($cardNo,0,5)];
        }
        if(!empty($bank)){
            $bank_name = mb_substr($bank,0,mb_strpos($bank,'-'));
            $type = mb_substr($bank,mb_strrpos($bank,'-')+1);
            foreach($bank_no_list as $key=>$val){
                if(mb_strrpos($bank_name,$val)!==false){
                    $bank_no = $key;break;
                }
            }
        }
        return array(
            'type'  =>  $type,
            'bank_no'   =>  $bank_no
        );
    }

    /**
     * 修改支付密码
     */
    public function savePayPasswordOp(){
//        if($_SESSION['auth_modify_paypwd']&&TIMESTAMP - $_SESSION['auth_modify_paypwd']<1800){
            unset($_SESSION['auth_modify_paypwd']);
            $model_member = Model('member');
            $update	= $model_member->editMember(array('member_id'=>$this->currentUser['member_id']),array('member_paypwd'=>md5($this->POST['password'])));
            $this->output($update!==false?'修改成功':'修改失败',$update?0:1);
//        }
//        $this->output('验证失败',1);
    }

    /**
     * 是否已设置支付密码
     */
    public function isSetPayPasswordOp(){
        $pass = Model('member')->getMemberInfo(array('member_id'=>$this->currentUser['member_id']),'member_paypwd');
        $set = !empty($pass['member_paypwd'])?1:0;
        $this->output($pass!==false?'':'请求失败',$pass!==false?0:1,array('set'=>$set));
    }

    /**
     * 获取余额
     */
    public function getMemberPredepositOp(){
        $pass = Model('member')->getMemberInfo(array('member_id'=>$this->currentUser['member_id']),'available_predeposit');
        $this->output($pass!==false?'':'请求失败',$pass!==false?0:1,$pass);
    }

	/**
	 * 查询提现账户列表
	 * @param $member_id
	 * @return array
	 */
	public function getWithdrawCashOp(){
		$result = Model('predeposit')->getWithdrawAccountList($this->currentUser['member_id']);
		$balance = Model('member')->getMemberInfo(array('member_id'=>$this->currentUser['member_id']),'available_predeposit');
		foreach($result as &$val){
			$val['account'] = '**** **** **** '.substr($val['account'],-4,4);
		}

        $data = array(
            'list'=>$result,
        );
		if($balance!==false)
            $data['available_predeposit']=$balance['available_predeposit'];

		$this->output('', $result === false ? 1 : 0, $data);
	}

    /**
     * 账户明细
     */
    public function pd_logOp(){
        //order_pay下单支付预存款,
        //order_freeze下单冻结预存款,
        //order_cancel取消订单解冻预存款,
        //order_comb_pay下单支付被冻结的预存款,
        //recharge充值,
        //cash_apply申请提现冻结预存款,
        //cash_pay提现成功,
        //cash_del取消提现申请，解冻预存款,
        //refund退款
        //sys_add_money管理员调节预存款
        //seller_refund商家退款支出
        //seller_money卖出商品收入
        //pindan_rebates拼单返现
        //sys_del_money管理员调节预存款【减少】


        $condition = array();
        if($_GET['keyword']){
            $condition['lg_desc'] = array('like','%'.$_GET['keyword'].'%');
        }
        $model_pd = Model('predeposit');
        $condition['lg_member_id'] = $this->currentUser['member_id'];
        $list = $model_pd->getPdLogList($condition,20,'*','lg_id desc');
        $weekarray=array("日","一","二","三","四","五","六");
        $returnList = array();
        foreach($list as $val){
            //时间处理
            $time = $val['lg_add_time'];
            if(date('Y-m-d',$time)==date('Y-m-d',time())){
                $val['time'][0] = '今天';
                $val['time'][1] = date('H:i',$time);
            }else if(date('Y-m-d',$time)==date('Y-m-d',strtotime('-1 day',time()))){
                $val['time'][0] = '昨天';
                $val['time'][1] = date('H:i',$time);
            }else{
                $val['time'][0] = '周'.$weekarray[date("w",$time)];
                $val['time'][1] = date('m-d',$time);
            }
            //金额处理
            $val['money'] = ($val['lg_av_amount']>=0?'+':'').$val['lg_av_amount'];
            //说明处理

            //提现状态处理
            $val['tx_type'] = '';
            if(in_array($val['lg_type'],array('cash_pay','cash_del','cash_apply'))&&$val['lg_type'] != 'cash_del'){
                if(preg_match('/\d{18}/',$val['lg_desc'],$matches)){
                    $tx = $model_pd->table('pd_cash')->where(array('pdc_sn'=>$matches[0]))->find();
                    $val['tx_type'] = $tx['pdc_payment_state']==1?'提现成功':'提现中';
                }
            }
            //月份处理
            if(date('Y-m',$time)==date('Y-m',time())){
                $returnList['本月'][0] = '本月';
                $returnList['本月'][1][] = $val;
            }else{
                $returnList[trim(date('Y-m',$time)).'月'][0] = trim(date('m',$time)).'月';
                $returnList[trim(date('Y-m',$time)).'月'][1][] = $val;
            }
        }
        $data = array(
            'pageCount' =>  $model_pd->gettotalpage(),
            'list'  =>  $returnList
        );
        $this->output('',0,$data);
    }

    /**
     * 账户明细详情
     */
    public function pd_detailOp(){
        $id = intval($_GET['id']);
        $model_pd = Model('predeposit');
        $detail = $model_pd->table('pd_log')->where(array('lg_id'=>$id))->find();
        if(empty($detail)){
            $this->output('参数错误',1);
        }
        $pageType = array(
            1=>'cash_pay,cash_del,cash_apply',//提现
            2=>'sys_add_money,sys_del_money',//预存款
            3=>'order_pay,order_freeze,order_cancel,order_comb_pay',//下单
            4=>'refund,seller_refund',//退款
        );
        $type = 0;
        foreach($pageType as $key=>$val){
            if(strpos($val,$detail['lg_type'])!==false){
                $type = $key;break;
            }
        }
        //返回数据
        $data = array();
        $data['type'] = $type;
        $data['title'] = '';
        $data['money'] = ($detail['lg_av_amount']>0?'+':'').$detail['lg_av_amount'];
        $data['detail'] = '';
        $data['lines'] = array();

        switch($type){
            case 1:
                $data['title'] = '申请提现';
                if($detail['lg_type']=='cash_pay'){
                    $data['detail'] = '提现成功';
                }else if($detail['lg_type']=='cash_del'){
                    $data['detail'] = '申请失败，取消提现';
                }else if($detail['lg_type']=='cash_apply'){
                    $data['detail'] = '申请提现，冻结预存款';
                }
                $data['lines'][] = '创建时间：'.date('Y-m-d H:i:s',$detail['lg_add_time']);
                if(preg_match('/\d{18}/',$detail['lg_desc'],$matches)){
                    $no = $matches[0];
                }else{
                    $no = '';
                }
                $data['lines'][] = '提现单号：'.$no;

                break;
            case 2:
                $data['title'] = '预存款调节';
                $data['detail'] = $detail['lg_type']=='sys_add_money'?'已存入账户余额':'已从账户余额支出';
                $data['lines'][] = '创建时间：'.date('Y-m-d H:i:s',$detail['lg_add_time']);
                if(preg_match('/\d{18}/',$detail['lg_desc'],$matches)){
                    $no = $matches[0];
                }else{
                    $no = '';
                }
                $data['lines'][] = '充值单号：'.$no;
                break;
            case 3:
                $data['title'] = '下单完成付款';
                $data['detail'] = '已从账户余额支出';
                if(preg_match('/\d{16}/',$detail['lg_desc'],$matches)){
                    $no = $matches[0];
                }else{
                    $no = '';
                }
                if($no){
                    $order = $model_pd->table('order')->where(array('order_sn'=>$no))->find();
                    if($order){
                        $goods = $model_pd->table('order_goods')->where(array('order_id'=>$order['order_id']))->find();
                        $payment_list = Model('payment')->getPaymentOpenList();
                        $data['lines'][] = '付款方式：'.$payment_list[$order['payment_code']]['payment_name'];
                        $data['lines'][] = '商品说明：'.$goods['goods_name'].'等多件';
                        $data['lines'][] = '创建时间：'.date('Y-m-d H:i:s',$order['add_time']);
                        $data['lines'][] = '付款时间：'.date('Y-m-d H:i:s',$order['payment_time']);
                        $data['lines'][] = '订单号：'.$no;
                        $data['lines'][] = '支付号：'.$order['pay_sn'];
                    }
                }
                break;
            case 4:
                $data['title'] = '退款';
                $data['detail'] = '已存入账户余额';
                $data['lines'][] = '创建时间：'.date('Y-m-d H:i:s',$detail['lg_add_time']);
                if(preg_match('/\d{16}/',$detail['lg_desc'],$matches)){
                    $no = $matches[0];
                }else{
                    $no = '';
                }
                $data['lines'][] = '订单号：'.$no;
                break;
        }

        $this->output('',0,$data);
    }

	/**
	 * 查询提现账户列表
	 * @param $member_id
	 * @return array
	 */
	public function getblanklistOp(){
		$result = Model('blankList')->getBlankList();
		print_r($result);
	}
}