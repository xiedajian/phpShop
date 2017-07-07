<?php
/**
 * 会员管理
 *
 *
 *
 ***/

defined('InShopNC') or exit('Access Invalid!');

class memberControl extends SystemControl{
	const EXPORT_SIZE = 2000;
	public function __construct(){
		parent::__construct();
		Language::read('member');
	}

	/**
	 * 会员管理
	 */
	public function memberOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
				/**
		 * 删除
		 */
		if (chksubmit()){
			/**
			 * 判断是否是管理员，如果是，则不能删除
			 */
			/**
			 * 删除
			 */
			if (!empty($_POST['del_id'])){
				if (is_array($_POST['del_id'])){
					foreach ($_POST['del_id'] as $k => $v){
						$v = intval($v);
						$rs = true;//$model_member->del($v);
						if ($rs){
							//删除该会员商品,店铺
							//获得该会员店铺信息
							$member = $model_member->getMemberInfo(array(
								'member_id'=>$v
							));
							//删除用户
							$model_member->del($v);
						}
					}
				}
				showMessage($lang['nc_common_del_succ']);
			}else {
				showMessage($lang['nc_common_del_fail']);
			}
		}
		//会员级别
		$member_grade = $model_member->getMemberGradeArr();

        $condition['member_group'] = array('neq',2);//不是分销商
		if ($_GET['search_field_value'] != '') {
    		switch ($_GET['search_field_name']){
    			case 'member_name':
    				$condition['member_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'member_email':
    				$condition['member_email'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
				//zmr>v30
				case 'member_mobile':
    				$condition['member_mobile'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'member_truename':
    				$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    			case 'org_name':
    				$condition['member_org.org_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
    				break;
    		}
		}
		switch ($_GET['search_state']){
			case 'no_informallow':
				$condition['inform_allow'] = '2';
				break;
			case 'no_isbuy':
				$condition['is_buy'] = '0';
				break;
			case 'no_isallowtalk':
				$condition['is_allowtalk'] = '0';
				break;
			case 'no_memberstate':
				$condition['member_state'] = '0';
				break;
		}
		//会员等级
		$search_grade = intval($_GET['search_grade']);
		if ($search_grade >= 0 && $member_grade){
		    $condition['member_exppoints'] = array(array('egt',$member_grade[$search_grade]['exppoints']),array('lt',$member_grade[$search_grade+1]['exppoints']),'and');
		}
        //1普通会员。2vip会员
        $search_level = intval($_GET['search_level']);
        if($search_level>0){
            $condition['member_level'] = $search_level;
        }
		//排序
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
		    $order = 'member_id desc';
		}
		//认证状态
		$auth_statu=$_GET['auth_statu'];
		if ($auth_statu!=''){
			if($auth_statu=='-1'){
		      $condition['member.org_id'] =0;
			}else{
			  $condition['member_org.auth_statu'] =$auth_statu;
			}
		}
		$register_client=$_GET['register_client'];
		if ($register_client!=''){
		   $condition['register_client'] =$register_client;
		}
		//是否加入直邮宝
		if($_GET['is_zhiyoubao']!=''){
			$condition['is_zhiyoubao'] =$_GET['is_zhiyoubao'];
		}
        //会员组，测试会员和正常会员
        if(intval($_GET['member_group'])>0){
            if(intval($_GET['member_group']==10)){
                $condition['is_test'] = 1;
            }else{
                $condition['member_group'] = intval($_GET['member_group']);
            }
        }
		//$member_list = $model_member->getMemberList($condition, '*', 10, $order);
		$member_list = $model_member->getMemberJoinOrgList($condition, 'member.*,member_org.org_name,member_org.auth_statu', 10, $order);
		
		$org_ids=array();		
		//整理会员信息
		if (is_array($member_list)){
			foreach ($member_list as $k=> $v){
				$member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
				$member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
				$member_list[$k]['member_grade'] = ($t = $model_member->getOneMemberGrade($v['member_exppoints'], false, $member_grade))?$t['level_name']:'';
				if($v["org_id"]){
				   $org_ids[]=$v["org_id"];
				}
			}
		}
		
		//获取会员组织架构
// 		if(!empty($org_ids)){
// 			$member_org_list=$model_member->getMemberOrgList(array("org_id"=>array("in",$org_ids)));
// 			$member_org_array=array();
// 			foreach ($member_org_list as $v){
// 				$member_org_array[$v["org_id"]]=$v;
// 			}
// 			foreach ($member_list as $k=> $v){
// 				$member_list[$k]["member_org"]=$member_org_array[$v["org_id"]];
// 			}
// 		}
		

		Tpl::output('member_grade',$member_grade);
		Tpl::output('search_sort',trim($_GET['search_sort']));
		Tpl::output('search_field_name',trim($_GET['search_field_name']));
		Tpl::output('search_field_value',trim($_GET['search_field_value']));
		Tpl::output('member_list',$member_list);
		Tpl::output('page',$model_member->showpage());
		Tpl::showpage('member.index');
	}

	/**
	 * 会员修改
	 */
	public function member_editOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			array("input"=>$_POST["member_email"], "require"=>"true", 'validator'=>'Email', "message"=>$lang['member_edit_valid_email']),
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$update_array = array();
				$update_array['member_id']			= intval($_POST['member_id']);
				if (!empty($_POST['member_passwd'])){
					$update_array['member_passwd'] = md5($_POST['member_passwd']);
				}
				$update_array['member_email']		= $_POST['member_email'];
				$update_array['member_truename']	= $_POST['member_truename'];
				$update_array['member_sex'] 		= $_POST['member_sex'];
				$update_array['member_qq'] 			= $_POST['member_qq'];
				$update_array['member_ww']			= $_POST['member_ww'];
				$update_array['inform_allow'] 		= $_POST['inform_allow'];
				$update_array['is_buy'] 			= $_POST['isbuy'];
				$update_array['is_allowtalk'] 		= $_POST['allowtalk'];
				$update_array['member_state'] 		= $_POST['memberstate'];
				//zmr>v30
				$update_array['member_cityid']		= $_POST['city_id'];
			    $update_array['member_provinceid']	= $_POST['province_id'];
			    $update_array['member_areainfo']	= $_POST['area_info'];
				$update_array['member_mobile'] 		= $_POST['member_mobile'];
				$update_array['member_email_bind'] 		= intval($_POST['memberemailbind']);
				$update_array['member_mobile_bind'] 		= intval($_POST['membermobilebind']);
				//zmr>v30
                $update_array['member_level'] 		= intval($_POST['member_level']);
//                if(intval($_POST[''])==3){
//                    $update_array['member_group'] 		= intval($_POST['member_group']);
//                }else{
//                    $model_store_joinin = Model('store_joinin');
//                    if($model_store_joinin->isExist(array('member_id'=>$_POST['member_id'],'joinin_state'=>40))){
//                        $update_array['member_group'] = 2;
//                    }else{
//                        $update_array['member_group'] = 1;
//                    }
//                }
                $update_array['is_test'] 		= intval($_POST['is_test']);
				if (!empty($_POST['member_avatar'])){
					$update_array['member_avatar'] = $_POST['member_avatar'];
				}
				$result = $model_member->editMember(array('member_id'=>intval($_POST['member_id'])),$update_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_edit_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_edit&member_id='.intval($_POST['member_id']),
					'msg'=>$lang['member_edit_again'],
					),
					);
					$this->log(L('nc_edit,member_index_name').'[ID:'.$_POST['member_id'].']',1);
					showMessage($lang['member_edit_succ'],$url);
				}else {
					showMessage($lang['member_edit_fail']);
				}
			}
		}
		$condition['member_id'] = intval($_GET['member_id']);
		$member_array = $model_member->getMemberInfo($condition);
        $model_address = Model('address');
        $address_info = $model_address->getAddressList(array('member_id'=>$member_array['member_id']));

		Tpl::output('member_array',$member_array);
		Tpl::output('address_info',$address_info);
		Tpl::showpage('member.edit');
	}

	/**
	 * 新增会员
	 */
	public function member_addOp(){
		$lang	= Language::getLangContent();
		$model_member = Model('member');
		/**
		 * 保存
		 */
		if (chksubmit()){
			/**
			 * 验证
			 */
			$obj_validate = new Validate();
			$obj_validate->validateparam = array(
			    array("input"=>$_POST["member_name"], "require"=>"true", "message"=>$lang['member_add_name_null']),
			    array("input"=>$_POST["member_passwd"], "require"=>"true", "message"=>'密码不能为空'),
			    array("input"=>$_POST["member_email"], "require"=>"true", 'validator'=>'Email', "message"=>$lang['member_edit_valid_email'])
			);
			$error = $obj_validate->validate();
			if ($error != ''){
				showMessage($error);
			}else {
				$insert_array = array();
				$insert_array['member_name']	= trim($_POST['member_name']);
				$insert_array['member_passwd']	= trim($_POST['member_passwd']);
				$insert_array['member_email']	= trim($_POST['member_email']);
				$insert_array['member_truename']= trim($_POST['member_truename']);
				$insert_array['member_sex'] 	= trim($_POST['member_sex']);
				$insert_array['member_qq'] 		= trim($_POST['member_qq']);
				$insert_array['member_ww']		= trim($_POST['member_ww']);
                //默认允许举报商品
                $insert_array['inform_allow'] 	= '1';
				if (!empty($_POST['member_avatar'])){
					$insert_array['member_avatar'] = trim($_POST['member_avatar']);
				}

				$result = $model_member->addMember($insert_array);
				if ($result){
					$url = array(
					array(
					'url'=>'index.php?act=member&op=member',
					'msg'=>$lang['member_add_back_to_list'],
					),
					array(
					'url'=>'index.php?act=member&op=member_add',
					'msg'=>$lang['member_add_again'],
					),
					);
					$this->log(L('nc_add,member_index_name').'[	'.$_POST['member_name'].']',1);
					showMessage($lang['member_add_succ'],$url);
				}else {
					showMessage($lang['member_add_fail']);
				}
			}
		}
		Tpl::showpage('member.add');
	}

	/**
	 * ajax操作
	 */
	public function ajaxOp(){
		switch ($_GET['branch']){
			/**
			 * 验证会员是否重复
			 */
			case 'check_user_name':
				$model_member = Model('member');
				$condition['member_name']	= $_GET['member_name'];
				$condition['member_id']	= array('neq',intval($_GET['member_id']));
				$list = $model_member->getMemberInfo($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
				/**
			 * 验证邮件是否重复
			 */
			case 'check_email':
				$model_member = Model('member');
				$condition['member_email'] = $_GET['member_email'];
				$condition['member_id'] = array('neq',intval($_GET['member_id']));
				$list = $model_member->getMemberInfo($condition);
				if (empty($list)){
					echo 'true';exit;
				}else {
					echo 'false';exit;
				}
				break;
		}
	}

    /**
     * 导出
     *
     */
    public function export_step1Op(){
        $lang	= Language::getLangContent();
        $model_member = Model('member');
        $condition	= array();
		//会员级别
		$member_grade = $model_member->getMemberGradeArr();
		$condition['member_group'] = array('neq',2);//不是分销商
		if ($_GET['search_field_value'] != '') {
			switch ($_GET['search_field_name']){
				case 'member_name':
					$condition['member_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				case 'member_email':
					$condition['member_email'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				//zmr>v30
				case 'member_mobile':
					$condition['member_mobile'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				case 'member_truename':
					$condition['member_truename'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
				case 'org_name':
					$condition['member_org.org_name'] = array('like', '%' . trim($_GET['search_field_value']) . '%');
					break;
			}
		}
		switch ($_GET['search_state']){
			case 'no_informallow':
				$condition['inform_allow'] = '2';
				break;
			case 'no_isbuy':
				$condition['is_buy'] = '0';
				break;
			case 'no_isallowtalk':
				$condition['is_allowtalk'] = '0';
				break;
			case 'no_memberstate':
				$condition['member_state'] = '0';
				break;
		}
		//会员等级
		$search_grade = intval($_GET['search_grade']);
		if ($search_grade >= 0 && $member_grade){
			$condition['member_exppoints'] = array(array('egt',$member_grade[$search_grade]['exppoints']),array('lt',$member_grade[$search_grade+1]['exppoints']),'and');
		}
		//1普通会员。2vip会员
		$search_level = intval($_GET['search_level']);
		if($search_level>0){
			$condition['member_level'] = $search_level;
		}
		//排序
		$order = trim($_GET['search_sort']);
		if (empty($order)) {
			$order = 'member_id desc';
		}
		//认证状态
		$auth_statu=$_GET['auth_statu'];
		if ($auth_statu!=''){
			if($auth_statu=='-1'){
				$condition['member.org_id'] =0;
			}else{
				$condition['member_org.auth_statu'] =$auth_statu;
			}
		}
		$register_client=$_GET['register_client'];
		if ($register_client!=''){
			$condition['register_client'] =$register_client;
		}
		//是否加入直邮宝
		if($_GET['is_zhiyoubao']!=''){
			$condition['is_zhiyoubao'] =$_GET['is_zhiyoubao'];
		}
		//会员组，测试会员和正常会员
		if(intval($_GET['member_group'])>0){
			if(intval($_GET['member_group']==10)){
				$condition['is_test'] = 1;
			}else{
				$condition['member_group'] = intval($_GET['member_group']);
			}
		}
        if (!is_numeric($_GET['curpage'])){
            $count = $model_member->getMemerCountfilter($condition);
            $array = array();
            if ($count > self::EXPORT_SIZE ){	//显示下载链接
                $page = ceil($count/self::EXPORT_SIZE);
                for ($i=1;$i<=$page;$i++){
                    $limit1 = ($i-1)*self::EXPORT_SIZE + 1;
                    $limit2 = $i*self::EXPORT_SIZE > $count ? $count : $i*self::EXPORT_SIZE;
                    $array[$i] = $limit1.' ~ '.$limit2 ;
                }
                Tpl::output('list',$array);
                Tpl::output('murl','index.php?act=order&op=index');
                Tpl::showpage('export.excel');
            }else{	//如果数量小，直接下载
                $member_list = $model_member->getMemberJoinOrgList($condition, '*', self::EXPORT_SIZE, $order);
                $area_name_array = Model('area')->getAreaNames();
                $org_ids=array();
                //整理会员信息
                if (is_array($member_list)){
                    foreach ($member_list as $k=> $v){
                        $member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
                        $member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
                        $member_list[$k]['member_grade'] = ($t = $model_member->getOneMemberGrade($v['member_exppoints'], false, $member_grade))?$t['level_name']:'';
                        if($v["org_id"]){
                            $org_ids[]=$v["org_id"];
                        }
                    }
                }
                //获取会员组织架构
                if(!empty($org_ids)){
                    $member_org_list=$model_member->getMemberOrgList(array("org_id"=>array("in",$org_ids)));
                    $member_org_array=array();
                    foreach ($member_org_list as $v){
                        $v['area_name'] = $area_name_array[$v['province_id']];//.$area_name_array[$v['city_id']].$area_name_array[$v['district_id']];
	                    if($v['city_id']>0) {
		                    $v['area_name'] .=$area_name_array[$v['city_id']];
		                    if($v['district_id']>0){
			                    $v['area_name'] .=$area_name_array[$v['district_id']];
		                    }
	                    }
                        $member_org_array[$v["org_id"]]=$v;
                    }
                    foreach ($member_list as $k=> $v){
                        $member_list[$k]["member_org"]=$member_org_array[$v["org_id"]];
                    }
                }
                $this->createExcel($member_list);
            }
        }else{	//下载
            $limit1 = ($_GET['curpage']-1) * self::EXPORT_SIZE;
            $limit2 = self::EXPORT_SIZE;
            $area_name_array = Model('area')->getAreaNames();
            $member_list = $model_member->getMemberJoinOrgList($condition, '*', self::EXPORT_SIZE, $order,"{$limit1},{$limit2}");
            $org_ids=array();
            //整理会员信息
            if (is_array($member_list)){
                foreach ($member_list as $k=> $v){
                    $member_list[$k]['member_time'] = $v['member_time']?date('Y-m-d H:i:s',$v['member_time']):'';
                    $member_list[$k]['member_login_time'] = $v['member_login_time']?date('Y-m-d H:i:s',$v['member_login_time']):'';
                    $member_list[$k]['member_grade'] = ($t = $model_member->getOneMemberGrade($v['member_exppoints'], false, $member_grade))?$t['level_name']:'';
                    if($v["org_id"]){
                        $org_ids[]=$v["org_id"];
                    }
                }
            }
            //获取会员组织架构
            if(!empty($org_ids)){
                $member_org_list=$model_member->getMemberOrgList(array("org_id"=>array("in",$org_ids)));
                $member_org_array=array();
                foreach ($member_org_list as $v){
	                $v['area_name'] = $area_name_array[$v['province_id']];//.$area_name_array[$v['city_id']].$area_name_array[$v['district_id']];
	                if($v['city_id']>0) {
		                $v['area_name'] .=$area_name_array[$v['city_id']];
		                if($v['district_id']>0){
			                $v['area_name'] .=$area_name_array[$v['district_id']];
		                }
	                }
                    $member_org_array[$v["org_id"]]=$v;
                }
                foreach ($member_list as $k=> $v){
                    $member_list[$k]["member_org"]=$member_org_array[$v["org_id"]];
                }
            }
            $this->createExcel($member_list);
        }
    }

    /**
     * 生成excel
     *
     * @param array $data
     */
    private function createExcel($data = array()){
        $member_ids = array();
        foreach($data as $val){
            $member_ids[] = $val['member_id'];
        }
        $model_address = Model('address');
        $address = $model_address->getAddressList(array('member_id'=>array('in',$member_ids)));
        $address_list = array();
        foreach($address as $val){
            $address_list[$val['member_id']][] = $val;
        }

        Language::read('export');
        import('libraries.excel');
        $excel_obj = new Excel();
        $excel_data = array();
        //设置样式
        $excel_obj->setStyle(array('id'=>'s_title','Font'=>array('FontName'=>'宋体','Size'=>'12','Bold'=>'1')));
        //header
        //会员帐号、真实名称、注册手机号、注册时间、邮箱、可用余额、冻结余额、认证状态、店铺名称、门店数、法人、联系人姓名、联系人电话、联系人QQ、认证申请时间、认证时间、登录次数、最后登录时间
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'会员帐号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'真实名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'手机号');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'注册时间');

        $excel_data[0][] = array('styleid'=>'s_title','data'=>'邮箱');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'可用余额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'冻结余额');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'认证状态');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'登录次数');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'最后登录时间');

        $excel_data[0][] = array('styleid'=>'s_title','data'=>'店铺名称');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'门店数');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'法人');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'联系人姓名');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'联系人电话');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'联系人QQ');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'认证时间');
        $excel_data[0][] = array('styleid'=>'s_title','data'=>'报备地区');
	    $excel_data[0][] = array('styleid'=>'s_title','data'=>'手机归属地');
	    $excel_data[0][] = array('styleid'=>'s_title','data'=>'加入直邮宝');
	    $excel_data[0][] = array('styleid'=>'s_title','data'=>'收货地址');




        //data
        foreach ((array)$data as $k=>$v){
            $tmp = array();
            if( $v['auth_statu']==1){
                $auth_statu = '认证成功';
            }else if( $v['auth_statu']==2){
                $auth_statu = '认证失败';
            }else{
                $auth_statu = '未认证';
            }

            $tmp[] = array('data'=>$v['member_name']);
            $tmp[] = array('data'=>$v['member_truename']);
            $tmp[] = array('data'=>$v['member_name']);
            $tmp[] = array('data'=>$v['member_time']);

            $tmp[] = array('data'=>$v['member_email']);
            $tmp[] = array('data'=>$v['available_rc_balance']);
            $tmp[] = array('data'=>$v['freeze_rc_balance']);
            $tmp[] = array('data'=>$auth_statu);
            $tmp[] = array('data'=>$v['member_login_num']);
            $tmp[] = array('data'=>$v['member_old_login_time']?date('Y-m-d H:i:s',$v['member_old_login_time']):'');


            $tmp[] = array('data'=>$v['member_org']['org_name']);
            $tmp[] = array('data'=>$v['member_org']['store_count']);
            $tmp[] = array('data'=>$v['member_org']['corporate']);
            $tmp[] = array('data'=>$v['member_org']['conneter']);
            $tmp[] = array('data'=>$v['member_org']['conneter_tel']);
            $tmp[] = array('data'=>$v['member_org']['conneter_qq']);
            $tmp[] = array('data'=>$v['member_org']['auth_time']?date('Y-m-d H:i:s',$v['member_org']['auth_time']):'');
            $tmp[] = array('data'=>$v['member_org']['area_name']);
	        $tmp[] = array('data'=>$v['mobile_address']);
	        $tmp[] = array('data'=>$v['is_zhiyoubao']==1?"是":"");
            if($address_list[$v['member_id']]){
                foreach($address_list[$v['member_id']] as $addr){
                    $addr_info = $addr['area_info'].$addr['address'];
                    if($addr['tel_phone']){
                        $addr_info.=','.$addr['tel_phone'];
                    }
                    if($addr['mob_phone']){
                        $addr_info.=','.$addr['mob_phone'];
                    }
                    if($addr['postcode']){
                        $addr_info.=','.$addr['postcode'];
                    }
                    if($addr['is_default']==1){
                        $addr_info.=',默认地址';
                    }
                    $tmp[] = array('data'=>$addr_info);
                }
            }


            $excel_data[] = $tmp;
        }
        $excel_data = $excel_obj->charset($excel_data,CHARSET);
        $excel_obj->addArray($excel_data);
        $excel_obj->addWorksheet($excel_obj->charset('会员信息',CHARSET));
        $excel_obj->generateXML($excel_obj->charset('会员信息',CHARSET).$_GET['curpage'].'-'.date('Y-m-d-H',time()));

    }
}
