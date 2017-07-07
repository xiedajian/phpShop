<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>供应商</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?act=store&op=store_joinin" ><span><?php echo $lang['pending'];?></span></a></li>
        <li><a href="index.php?act=store&op=reopen_list" ><span>续签申请</span></a></li>
        <li><a href="index.php?act=store&op=store_bind_class_applay_list" ><span>经营类目申请</span></a></li>
        <li><a href="index.php?act=store&op=newshop_add" ><span>新增供应商</span></a></li>
        <li><a href="index.php?act=store&op=store_import" ><span>CSV导入供应商</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
  <input type="hidden" value="store" name="act">
  <input type="hidden" value="store" name="op">
  <table class="tb-type1 noborder search">
  <tbody>
    <tr><th><label><?php echo $lang['belongs_level'];?></label></th>
      <td><select name="grade_id">
          <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
          <?php if(!empty($output['grade_list']) && is_array($output['grade_list'])){ ?>
          <?php foreach($output['grade_list'] as $k => $v){ ?>
          <option value="<?php echo $v['sg_id'];?>" <?php if($output['grade_id'] == $v['sg_id']){?>selected<?php }?>><?php echo $v['sg_name'];?></option>
          <?php } ?>
          <?php } ?>
        </select></td>

        <th><label for="owner_and_name"><?php echo $lang['store_user'];?></label></th>
      <td><input type="text" value="<?php echo $output['owner_and_name'];?>" name="owner_and_name" id="owner_and_name" class="txt"></td><td></td><th><label>店铺类型</label></th>
        <td>
            <select name="store_type">
                <option value=""><?php echo $lang['nc_please_choose'];?>...</option>
                <?php if(!empty($output['store_type']) && is_array($output['store_type'])){ ?>
                <?php foreach($output['store_type'] as $k => $v){ ?>
                <option value="<?php echo $k;?>" <?php if($_GET['store_type'] == $k){?>selected<?php }?>><?php echo $v;?></option>
                <?php } ?>
                <?php } ?>
            </select>
        </td>
        <th>结算方式</th>
        <td>
            <select name="settlement">
                <option value="0">请选择</option>
                <option value="1"<?php if($output['settlement']==1)echo ' selected="selected"';?>>一付款即可提现</option>
                <option value="2"<?php if($output['settlement']==2)echo ' selected="selected"';?>>一发货即可提现</option>
                <option value="3"<?php if($output['settlement']==3)echo ' selected="selected"';?>>确认收货即可提现</option>
            </select>
        </td>
      <th><label for="store_name">供应商</label></th>
      <td><input type="text" value="<?php echo $output['store_name'];?>" name="store_name" id="store_name" class="txt"></td>
        <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
        <?php if($output['owner_and_name'] != '' or $output['store_name'] != '' or $output['grade_id'] != ''){?>
        <a href="index.php?act=store&op=store" class="btns " title="<?php echo $lang['nc_cancel_search'];?>"><span><?php echo $lang['nc_cancel_search'];?></span></a>
        <?php }?></td>
    </tr></tbody>
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
            <li><?php echo $lang['store_help1'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <form method="post" id="store_form">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2">
      <thead>
        <tr class="thead">
          <th>供应商</th>
          <th>供应商账号</th>
          <th>供应商卖家账号</th>
            <th>结算方式</th>
          <th class="align-center"><?php echo $lang['belongs_level'];?></th>
          <th class="align-center"><?php echo $lang['period_to'];?></th>
          <th class="align-center"><?php echo $lang['state'];?></th>
          <th class="align-center"><?php echo $lang['operation'];?></th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['store_list']) && is_array($output['store_list'])){ ?>
        <?php foreach($output['store_list'] as $k => $v){ ?>
        <tr class="hover edit <?php echo getStoreStateClassName($v);?>" data-store-id="<?php echo $v['store_id'];?>">
          <td>
              <a href="<?php echo urlShop('show_store','index', array('store_id'=>$v['store_id']));?>" >
                <?php echo $v['store_name'];?>
            </a>
          </td>
          <td><?php echo $v['member_name'];?></td>
          <td><?php echo $v['seller_name'];?></td>
            <td>
                <select class="set-settlement">
                    <option>请选择</option>
                    <option value="1"<?php if($v['settlement']==1)echo ' selected="selected"';?>>一付款即可提现</option>
                    <option value="2"<?php if($v['settlement']==2)echo ' selected="selected"';?>>一发货即可提现</option>
                    <option value="3"<?php if($v['settlement']==3)echo ' selected="selected"';?>>确认收货即可提现</option>
                </select>
            </td>
          <td class="align-center"><?php echo $output['search_grade_list'][$v['grade_id']];?></td>
          <td class="nowarp align-center"><?php echo $v['store_end_time']?date('Y-m-d', $v['store_end_time']):$lang['no_limit'];?></td>
          <td class="align-center w72"><?php echo $v['store_state']?$lang['open']:$lang['close'];?></td>
        <td class="align-center" style="width:250px;">
             <a href="<?php echo SHOP_SITE_URL.DS.'index.php?act=seller_login&op=sys_login&sn='.encrypt($v['seller_name']);?>" target="_blank">超级登录</a>&nbsp;&nbsp;
            <a href="index.php?act=store&op=store_joinin_detail&member_id=<?php echo $v['member_id'];?>">查看</a>&nbsp;&nbsp;<a href="index.php?act=store&op=store_edit&store_id=<?php echo $v['store_id']?>"><?php echo $lang['nc_edit'];?></a>&nbsp;&nbsp;
                <a href="index.php?act=store&op=store_bind_class&store_id=<?php echo $v['store_id']?>">经营类目</a>
            <?php if (getStoreStateClassName($v) != 'open' && cookie('remindRenewal'.$v['store_id']) == null) {?><a href="<?php echo urlAdmin('store', 'remind_renewal', array('store_id'=>$v['store_id']));?>">提醒续费</a><?php }?>&nbsp;&nbsp; <a href="index.php?act=store&op=del&id=<?php echo $v['store_id']?>">删除</a>
            </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td></td>
          <td colspan="16">
            <div class="pagination"><?php echo $output['page'];?></div></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script>
$(function(){
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('store');$('#formSearch').submit();
    });

    $('.set-settlement').change(function(){
        var id = $(this).closest('tr').data('store-id');
        $.post('index.php?act=store&op=edit_settlement',{id:id,val:$(this).val()},function(result){
            if(result<=0){
                confirm("修改失败");
                location.reload();
            }
        });
    });
});
</script>
