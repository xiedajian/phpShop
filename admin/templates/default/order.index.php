<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['order_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=order" <?php if($_GET['order_state']!='0'){ ?>class="current"<?php } ?> ><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="index.php?act=order&op=index&order_state=0" <?php if($_GET['order_state']=='0'){ ?>class="current"<?php } ?> ><span>已取消订单</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" action="index.php" name="formSearch" id="formSearch">
    <input type="hidden" name="act" value="order" />
    <input type="hidden" name="op" value="index" />
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
         <th><label><?php echo $lang['order_number'];?></label></th>
         <td><input class="txt2" type="text" name="order_sn" value="<?php echo $_GET['order_sn'];?>" /></td>
         <th>供应商</th>
         <td><input class="txt2" type="text" name="store_name" value="<?php echo $_GET['store_name'];?>" /></td>
        
         <th><label><?php echo $lang['order_state'];?></label></th>
          <td colspan="1"><select name="order_state" class="querySelect">
              <option value=""><?php echo $lang['nc_please_choose'];?></option>
              <option value="10" <?php if($_GET['order_state'] == '10'){?>selected<?php }?>><?php echo $lang['order_state_new'];?></option>
              <option value="20" <?php if($_GET['order_state'] == '20'){?>selected<?php }?>><?php echo $lang['order_state_pay'];?></option>
              <option value="30" <?php if($_GET['order_state'] == '30'){?>selected<?php }?>><?php echo $lang['order_state_send'];?></option>
              <option value="40" <?php if($_GET['order_state'] == '40'){?>selected<?php }?>><?php echo $lang['order_state_success'];?></option>
              <option value="0" <?php if($_GET['order_state'] == '0'){?>selected<?php }?>><?php echo $lang['order_state_cancel'];?></option>
            </select></td>
         <th>会员帐号</th>
         <td><input class="txt2" type="text" name="member_name" value="<?php echo $_GET['member_name'];?>" /></td>
        </tr>
        <tr>
          <th><label for="query_start_time"><?php echo $lang['order_time_from'];?></label></th>
          <td><input class="txt date" type="text" value="<?php echo $_GET['query_start_time'];?>" id="query_start_time" name="query_start_time">
            <label for="query_start_time">~</label>
            <input class="txt date" type="text" value="<?php echo $_GET['query_end_time'];?>" id="query_end_time" name="query_end_time"/></td>
            
            <th><label for="payment_time_start">付款时间</label></th>
            <td><input class="txt date" type="text" value="<?php echo $_GET['payment_time_start'];?>" id="payment_time_start" name="payment_time_start">
                <label for="payment_time_end">~</label>
                <input class="txt date" type="text" value="<?php echo $_GET['payment_time_end'];?>" id="payment_time_end" name="payment_time_end"/></td>
            <th>付款方式</th>
         <td>
            <select name="payment_code" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option <?php if($_GET['payment_code'] == $val['payment_code']){?>selected<?php }?> value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
            <option <?php if($_GET['payment_code'] == 'wxpay'){?>selected<?php }?> value="wxpay">微信支付</option> 
            </select>
         </td>
            
             <td>
          <select name="search_field_name" >
              <option <?php if($output['search_field_name'] == 'member_org_name'){ ?>selected='selected'<?php } ?> value="member_org_name">采购方</option>
              <option <?php if($output['search_field_name'] == 'member_truename'){ ?>selected='selected'<?php } ?> value="member_truename">采购人姓名</option>
               <option <?php if($output['search_field_name'] == 'member_mobile'){ ?>selected='selected'<?php } ?> value="member_mobile">采购人手机号</option>
            </select></td>
          <td><input type="text" value="<?php echo $output['search_field_value'];?>" name="search_field_value" class="txt2"></td>
         <!-- <th><?php //echo $lang['buyer_name'];?></th>
         <td><input class="txt-short" type="text" name="buyer_name" value="<?php //echo $_GET['buyer_name'];?>" /></td>  -->
         
        <!-- <th>付款方式</th>
         <td>
            <select name="payment_code" class="w100">
            <option value=""><?php echo $lang['nc_please_choose'];?></option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option <?php if($_GET['payment_code'] == $val['payment_code']){?>selected<?php }?> value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
            <option <?php if($_GET['payment_code'] == 'wxpay'){?>selected<?php }?> value="wxpay">微信支付</option> 
            </select>
         </td> --> 
            <!-- <th><label for="payment_time_start">付款时间</label></th>
            <td><input class="txt date" type="text" value="<?php echo $_GET['payment_time_start'];?>" id="payment_time_start" name="payment_time_start">
                <label for="payment_time_end">~</label>
                <input class="txt date" type="text" value="<?php echo $_GET['payment_time_end'];?>" id="payment_time_end" name="payment_time_end"/></td> -->
            <th>
          <td><a href="javascript:void(0);" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
              <?php if( $_GET['order_sn'] != '' or $_GET['store_name'] != '' or $_GET['order_state'] != '' or $_GET['member_name'] != '' or $_GET['query_start_time'] != '' or $_GET['query_end_time'] != '' or $_GET['payment_time_start'] != ''  or $_GET['payment_time_end'] != ''  or $_GET['payment_code'] != ''or $output['search_field_value'] != '' ){?>
            <a href="index.php?act=order&op=index" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?>
            </td>
        </tr>
      </tbody>
    </table>
  </form>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li><?php echo $lang['order_help1'];?></li>
            <li><?php echo $lang['order_help2'];?></li>
            <li><?php echo $lang['order_help3'];?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <div style="text-align:right;">
      <a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&op=export_step1"><span><?php echo $lang['nc_export'];?>Excel</span></a>
      <a class="btns" target="_blank" href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&op=export_detail_step1"><span><?php echo $lang['nc_export'];?>订单详情</span></a>
  </div>

  <table class="table tb-type2 nobdb">
    <thead>
      <tr class="thead">
        <th><?php echo $lang['order_number'];?></th>
        <th>供应商</th>
        <th>采购方</th>
        <th>采购人姓名</th>
        <th>采购人手机号</th>
        <th class="align-center"><?php echo $lang['order_time'];?></th>
        <th class="align-center"><?php echo $lang['order_total_price'];?></th>
        <!--<th class="align-center"><?php echo $lang['payment'];?></th>-->
        <th class="align-center">付款时间</th>
        <th class="align-center"><?php echo $lang['order_state'];?></th>
        <th class="align-center">备注</th>
        <th class="align-center"><?php echo $lang['nc_handle'];?></th>
      </tr>
    </thead>
    <tbody>
      <?php if(count($output['order_list'])>0){?>
      <?php foreach($output['order_list'] as $order){?>
      <tr class="hover" id='orderid_<?php echo $order['order_id']; ?>'>
        <td><?php echo $order['order_sn'];?></td>
        <td><?php echo $order['store_name'];?></td>
        <td><?php echo $order['member_org_name'];?></td>
        <td><?php echo $order['member_truename'];?></td>
        <td><?php echo $order['member_mobile'];?></td>
        <td class="nowrap align-center"><?php echo date('Y-m-d H:i:s',$order['add_time']);?></td>
        <td class="align-center"><font style="color:red;"><?php echo $order['order_amount'];?></font>
        <?php $pay_amount=$order['order_amount']-$order['rcb_amount']-$order['pd_amount']; ?>
         <?php if(in_array($order['payment_code'],array('alipay','tenpay','chinabank','transfer','wxpay','predeposit','fuiou'))&&$pay_amount>0){ ?></br><font style="color:green;"><?php echo orderPaymentName($order['payment_code']).'付款：'.number_format($pay_amount,2);?></font> <?php } ?>
         <?php if($order['rcb_amount']>0){ ?> </br><font style="color:blue;"><?php echo '充值卡支付：'.$order['rcb_amount']; ?></font><?php } ?>
         <?php if($order['pd_amount']>0){ ?> </br><font style="color:blue;"><?php echo '余额支付：'.$order['pd_amount']; ?></font><?php } ?>
        </td>
          <td class="align-center"><?php if($order['payment_time'] > 10){echo date('Y-m-d H:i:s',$order['payment_time']);}?></td>
          <td class="align-center"><?php echo orderState($order);?></td>
          <td class="align-center" style="max-width: 100px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;" title="<?php echo $order['remark'];?>"><?php echo $order['remark'];?></td>
        <td class="w144 align-center"><a href="index.php?act=order&op=show_order&order_id=<?php echo $order['order_id'];?>"><?php echo $lang['nc_view'];?></a>

        <!-- 取消订单 -->
    		<?php if($order['if_cancel']) {?>
        	| <a href="javascript:void(0)" onclick="if(confirm('<?php echo $lang['order_confirm_cancel'];?>')){location.href='index.php?act=order&op=change_state&state_type=cancel&order_id=<?php echo $order['order_id']; ?>'}">
        	<?php echo $lang['order_change_cancel'];?></a>
        	<?php }?>

        	<!-- 收款 -->
    		<?php if($order['if_system_receive_pay']) {?>
	        	| <a href="index.php?act=order&op=change_state&state_type=receive_pay&order_id=<?php echo $order['order_id']; ?>">
	        	<?php echo $lang['order_change_received'];?></a>
    		<?php }?>


            |<a order_id="<?php echo $order['order_id']; ?>" content="<?php echo $order['remark']; ?>" href="javascript:void(0)" class="remark" style="white-space: nowrap">备注</a>
        	</td>
      </tr>
      <?php }?>
      <?php }else{?>
      <tr class="no_data">
        <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
      </tr>
      <?php }?>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td colspan="15" id="dataFuncs"><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
      </tr>
    </tfoot>
  </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">

$(function(){
    $('#query_start_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_time').datepicker({dateFormat: 'yy-mm-dd'});
    $('#payment_time_start').datepicker({dateFormat: 'yy-mm-dd'});
    $('#payment_time_end').datepicker({dateFormat: 'yy-mm-dd'});
    $('#ncsubmit').click(function(){
    	$('input[name="op"]').val('index');$('#formSearch').submit();
    });
    if(parent&&parent.window.order_view_id){
       $("#orderid_"+parent.window.order_view_id).css("background-color","#f9f78d");
       delete parent.window.order_view_id;
    }

    $('.remark').click(function(){
        var order_id = $(this).attr('order_id');
        var content = $(this).attr('content');
        var _uri = "<?php echo ADMIN_SITE_URL;?>/index.php?act=order&op=edit_remark&id="+order_id+'&content='+content;
        CUR_DIALOG = ajax_form('remark', '备注：', _uri, 350);
    });
});
</script> 
