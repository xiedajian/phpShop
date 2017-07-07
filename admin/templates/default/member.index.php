<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['member_index_manage']?></h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_manage']?></span></a></li>
        <li><a href="index.php?act=member&op=member_add" ><span><?php echo $lang['nc_new']?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="member" name="act">
    <input type="hidden" value="member" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>
          <!--//zmr>v30-->
          <select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'member_name'){ ?>selected='selected'<?php } ?> value="member_name"><?php echo $lang['member_index_name']?></option>
              <option <?php if($output['search_field_name'] == 'member_email'){ ?>selected='selected'<?php } ?> value="member_email"><?php echo $lang['member_index_email']?></option>
              
               <option <?php if($output['search_field_name'] == 'member_mobile'){ ?>selected='selected'<?php } ?> value="member_mobile">手机号码</option>
               
              <option <?php if($output['search_field_name'] == 'member_truename'){ ?>selected='selected'<?php } ?> value="member_truename"><?php echo $lang['member_index_true_name']?></option>
              <option <?php if($output['search_field_name'] == 'org_name'){ ?>selected='selected'<?php } ?> value="org_name">认证店铺名称</option>
            </select></td>
          <td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt"></td>
          <td><select name="search_sort" >
              <option value=""><?php echo $lang['nc_sort']?></option>
              <option <?php if($output['search_sort'] == 'member_login_time desc'){ ?>selected='selected'<?php } ?> value="member_login_time desc"><?php echo $lang['member_index_last_login']?></option>
              <option <?php if($output['search_sort'] == 'member_login_num desc'){ ?>selected='selected'<?php } ?> value="member_login_num desc"><?php echo $lang['member_index_login_time']?></option>
            </select></td>
          <td><select name="search_state" >
              <option <?php if($_GET['search_state'] == ''){ ?>selected='selected'<?php } ?> value=""><?php echo $lang['member_index_state']; ?></option>
              <option <?php if($_GET['search_state'] == 'no_informallow'){ ?>selected='selected'<?php } ?> value="no_informallow"><?php echo $lang['member_index_inform_deny']; ?></option>
              <option <?php if($_GET['search_state'] == 'no_isbuy'){ ?>selected='selected'<?php } ?> value="no_isbuy"><?php echo $lang['member_index_buy_deny']; ?></option>
              <option <?php if($_GET['search_state'] == 'no_isallowtalk'){ ?>selected='selected'<?php } ?> value="no_isallowtalk"><?php echo $lang['member_index_talk_deny']; ?></option>
              <option <?php if($_GET['search_state'] == 'no_memberstate'){ ?>selected='selected'<?php } ?> value="no_memberstate"><?php echo $lang['member_index_login_deny']; ?></option>
            </select></td>
          <td style="display: none" ><select name="search_grade" >
              <option value='-1'>会员级别</option>
              <?php if ($output['member_grade']){?>
              	<?php foreach ($output['member_grade'] as $k=>$v){?>
              	<option <?php if(isset($_GET['search_grade']) && $_GET['search_grade'] == $k){ ?>selected='selected'<?php } ?> value="<?php echo $k;?>"><?php echo $v['level_name'];?></option>
              	<?php }?>
              <?php }?>
            </select></td>
            <td><select name="search_level" >

                    <option value='0'>会员类型</option>
                    <option value='1' <?php if(isset($_GET['search_level']) && $_GET['search_level'] == 1){ ?>selected='selected'<?php } ?>>普通会员</option>
                    <option value='2' <?php if(isset($_GET['search_level']) && $_GET['search_level'] == 2){ ?>selected='selected'<?php } ?>>VIP会员</option>

                </select></td>
            <td><select name="auth_statu" >
              <option <?php if($_GET['auth_statu'] == ''){ ?>selected='selected'<?php } ?> value="">认证状态</option>
              <option <?php if($_GET['auth_statu'] == '0'){ ?>selected='selected'<?php } ?> value="0">认证中</option>
              <option <?php if($_GET['auth_statu'] == '1'){ ?>selected='selected'<?php } ?> value="1">认证通过</option>
              <option <?php if($_GET['auth_statu'] == '2'){ ?>selected='selected'<?php } ?> value="2">认证失败</option>
              <option <?php if($_GET['auth_statu'] == '-1'){ ?>selected='selected'<?php } ?> value="-1">未申请认证</option>
            </select></td>
            <td><select name="register_client" >
              <option <?php if($_GET['register_client'] == ''){ ?>selected='selected'<?php } ?> value="">注册来源</option>
              <option <?php if($_GET['register_client'] == '0'){ ?>selected='selected'<?php } ?> value="0">PC网页</option>
              <option <?php if($_GET['register_client'] == '1'){ ?>selected='selected'<?php } ?> value="1">手机WAP</option>
            </select></td>
             <td><select name="is_zhiyoubao" >
              <option <?php if($_GET['is_zhiyoubao'] == ''){ ?>selected='selected'<?php } ?> value="">是否加入直邮宝</option>
              <option <?php if($_GET['is_zhiyoubao'] == '0'){ ?>selected='selected'<?php } ?> value="0">否</option>
              <option <?php if($_GET['is_zhiyoubao'] == '1'){ ?>selected='selected'<?php } ?> value="1">是</option>
            </select></td>
            <td><select name="member_group" >
                    <option <?php if($_GET['member_group'] == ''){ ?>selected='selected'<?php } ?> value="">会员组</option>
                    <option <?php if($_GET['member_group'] == '1'){ ?>selected='selected'<?php } ?> value="1">宝宝店会员</option>
                    <option <?php if($_GET['member_group'] == '4'){ ?>selected='selected'<?php } ?> value="4">供应商会员</option>
                    <option <?php if($_GET['member_group'] == '2'){ ?>selected='selected'<?php } ?> value="2">分销商会员</option>
                    <option <?php if($_GET['member_group'] == '10'){ ?>selected='selected'<?php } ?> value="10">测试会员</option>
                </select></td>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?act=member&op=member" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?>
          </td>

        </tr>
      </tbody>
    </table>
  </form>

  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo $lang['member_index_help1'];?></li>
            <li><?php echo $lang['member_index_help2'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="form_member">
    <input type="hidden" name="form_submit" value="ok" />
      <div style="text-align:right;"><a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&op=export_step1"><span><?php echo $lang['nc_export'];?>Excel</span></a></div>
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th colspan="2"><?php echo $lang['member_index_name']?></th>
          <th class="align-center">所属店铺</th>
          <th class="align-center"><span fieldname="logins" nc_type="order_by"><?php echo $lang['member_index_login_time']?></span></th>
          <th class="align-center"><span fieldname="last_login" nc_type="order_by"><?php echo $lang['member_index_last_login']?></span></th>
          <th class="align-center"><?php echo $lang['member_index_prestore'];?></th>
          <th class="align-center">会员类型</th>
          <th class="align-center">加入直邮宝</th>
          <th class="align-center"><?php echo $lang['member_index_login']; ?></th>
          <th class="align-center"><?php echo $lang['nc_handle']; ?></th>
        </tr>
      <tbody>
        <?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
        <?php foreach($output['member_list'] as $k => $v){ ?>
        <tr class="hover member">
          <td class="w24"><input type="checkbox" name='del_id[]' value="<?php echo $v['member_id']; ?>" class="checkitem"></td>
          <td class="w48 picture"><div class="size-44x44"><span class="thumb size-44x44"><i></i><img src="<?php if ($v['member_avatar'] != ''){ echo UPLOAD_SITE_URL.DS.ATTACH_AVATAR.DS.$v['member_avatar'];}else { echo UPLOAD_SITE_URL.'/'.ATTACH_COMMON.DS.C('default_user_portrait');}?>?<?php echo microtime();?>"  onload="javascript:DrawImage(this,44,44);"/></span>
          
          
          </div></td>
          <td>
              <div class="name">
                  <strong><?php echo $v['member_name']; ?></strong>
                  <?php if($v['member_truename']){?>(<?php echo $lang['member_index_true_name']?>: <?php echo $v['member_truename']; ?>)<?php }?>
                  <?php if($v['is_test']){?><div class="member_icon_test" title="测试会员"></div><?php }else{?>
                  <?php if($v['member_group']>0){?><div class="member_icon<?php echo $v['member_group'];?>" title="<?php if($v['member_group']==1)echo '宝宝店会员';else if($v['member_group']==4)echo '供应商';else if($v['member_group']==2)echo '分销商会员';?>"></div><?php }?>
                    <?php }?>
              </div>
            <p class="smallfont"><?php echo $lang['member_index_reg_time']?>:&nbsp;<?php echo $v['member_time']; ?></p>
              <p class="smallfont">注册来源:&nbsp;<?php echo $v['register_client']==0?"PC网页":($v['register_client']==1?"手机WAP":""); ?></p>
              <div class="im">
               <?php if($v['member_mobile']){ ?>
                <span>手机号：<?php echo $v['member_mobile']; ?><?php echo empty($v["mobile_address"])?"":"(".$v["mobile_address"].")";?></span></br>
               <?php } ?>
                <?php if($v['member_email'] != ''){ ?>
                <span class="email">
                <a href="mailto:<?php echo $v['member_email']; ?>" class=" yes" title="<?php echo $lang['member_index_email']?>:<?php echo $v['member_email']; ?>"><?php echo $v['member_email']; ?></a><?php echo $v['member_email']; ?></span>
                <?php }?>
              </div></td>
          <td class="align-center">
            <?php if(!empty($v["org_id"])){?>
               <a target="_blank" href="index.php?act=member_org&op=member_org_view&org_id=<?php echo $v["org_id"];?>"><?php echo $v["org_name"];?></a>
               </br>
               <span><?php echo $v["auth_statu"]==='1'?"<font color='green'>已认证</font>":($v["auth_statu"]==='2'?"<font color='red'>认证失败</font>":"<font color='blue'>认证中</font>");?></span>
            <?php }else{ ?>
               <font color='red'>未申请认证</font>
            <?php } ?>
          </td>
          <td class="align-center"><?php echo $v['member_login_num']; ?></td>
          <td class="w150 align-center"><p><?php echo $v['member_login_time']; ?></p>
            <p><?php echo $v['member_login_ip']; ?></p></td>

          <td class="align-center"><p><?php echo $lang['member_index_available'];?>:&nbsp;<strong class="red"><?php echo $v['available_predeposit']; ?></strong>&nbsp;<?php echo $lang['currency_zh']; ?></p>
            <p><?php echo $lang['member_index_frozen'];?>:&nbsp;<strong class="red"><?php echo $v['freeze_predeposit']; ?></strong>&nbsp;<?php echo $lang['currency_zh']; ?></p>
          </td>
          <td class="align-center"><?php if($v['member_level']==1)echo '普通会员';else if($v['member_level']==2) echo 'VIP会员';?></td>
          <td class="align-center"><?php if($v['is_zhiyoubao']==1)echo '是';else  echo '否';?></td>
          <td class="align-center"><?php echo $v['member_state'] == 1?$lang['member_edit_allow']:$lang['member_edit_deny']; ?></td>
          <td class="align-center"><a href="index.php?act=member&op=member_edit&member_id=<?php echo $v['member_id']; ?>"><?php echo $lang['nc_edit']?></a> | <a href="index.php?act=notice&op=notice&member_name=<?php echo ltrim(base64_encode($v['member_name']),'='); ?>"><?php echo $lang['member_index_to_message'];?></a></td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"><?php echo $lang['nc_no_record']?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot class="tfoot">
        <?php if(!empty($output['member_list']) && is_array($output['member_list'])){ ?>
        <tr>
        <td class="w24"><input type="checkbox" class="checkall" id="checkallBottom"></td>
          <td colspan="16">
          <label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['nc_ensure_del']?>')){$('#form_member').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('member');$('#formSearch').submit();
    });
});
</script>
