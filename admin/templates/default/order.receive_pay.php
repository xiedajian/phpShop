<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
.ui-timepicker-div .ui-widget-header { margin-bottom: 8px; }  
.ui-timepicker-div dl { text-align: left; }  
.ui-timepicker-div dl dt { height: 25px; margin-bottom: -25px; }  
.ui-timepicker-div dl dd { margin: 0 10px 10px 65px; }  
.ui-timepicker-div td { font-size: 90%; }  
.ui-tpicker-grid-label { background: none; border: none; margin: 0; padding: 0; }  
</style>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['order_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=<?php echo $_GET['act'];?>&op=index"><span><?php echo $lang['manage'];?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span>确认收款</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="post" name="form1" id="form1" action="index.php?act=<?php echo $_GET['act'];?>&op=change_state&state_type=receive_pay&order_id=<?php echo intval($_GET['order_id']);?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" value="<?php echo getReferer();?>" name="ref_url">
    <table class="table tb-type2">
      <tbody>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">订单编号<?php echo $lang['nc_colon'];?> </label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['order_info']['order_sn'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php if ($_GET['act'] == 'order') { ?>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">支付单号<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['order_info']['pay_sn'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php } ?>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">订单总金额 <?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['order_info']['order_amount'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php if($output['order_info']['rcb_amount']>0){?>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">充值卡支付<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['order_info']['rcb_amount'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php } ?>
        <?php if($output['order_info']['pd_amount']>0){?>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">余额支付<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['order_info']['pd_amount'];?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php } ?>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">付款时间<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input readonly id="payment_time" class="" name="payment_time" value="<?php echo date('Y-m-d H:i:s',time()); ?>" type="text" /></td>
          <td class="vatop tips"></td>
        </tr>
        <tr class="noborder">
          <td colspan="2" class="required"><label for="site_name">付款方式 <?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
            <select name="payment_code" class="querySelect">
            <option value="transfer">线下转账</option>
            <?php foreach($output['payment_list'] as $val) { ?>
            <option value="<?php echo $val['payment_code']; ?>"><?php echo $val['payment_name']; ?></option>
            <?php } ?>
            </select>
          </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><label for="closed_reason">第三方支付平台交易号<?php echo $lang['nc_colon'];?></label></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><input type="text" style="width:300px;" class="txt2" name="trade_no" id="trade_no" maxlength="40"></td>
          <td class="vatop tips"><span class="vatop rowform">支付宝等第三方支付平台交易号</span></td>
        </tr>
      </tbody>
      <tfoot id="submit-holder">
        <tr class="tfoot">
          <td colspan="2" ><a href="JavaScript:void(0);" id="ncsubmit" class="btn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui-timepicker-addon/jquery-ui-timepicker-addon.min.js"></script> 
<script type="text/javascript">
$(function(){
   // $('#payment_time').datepicker({dateFormat: 'yy-mm-dd',maxDate: '<?php //echo date('Y-m-d',TIMESTAMP);?>'});
    $('#payment_time').datetimepicker({
	   showSecond: true,
	   showMillisec: true,
	   timeFormat: 'HH:mm:ss',
	   maxDate: '<?php echo date('Y-m-d',TIMESTAMP);?>'
   });
    $('#ncsubmit').click(function(){
    	if($("#form1").valid()){
        	if (confirm("操作提醒：\n该操作不可撤销\n提交前请务必确认是否已收到付款\n继续操作吗?")){
        	}else{
        		return false;
        	}
        	$('#form1').submit();
    	}
    });

    jQuery.validator.addMethod("times", function(value, element) { 
        var add_time=<?php echo $output['order_info']['add_time']; ?>;
        var ofrm1 = parent.document.getElementById("workspace").contentWindow.document;
       var pay_time=ofrm1.getElementById("payment_time").value;
       var timestamp = Date.parse(pay_time);
       timestamp = timestamp / 1000;
        if(timestamp>add_time){
        	return true;
            }else{
                return false;
            }    
  }, "你的下单时间为"+"<?php echo date('Y-m-d H:i:s',$output['order_info']['add_time']); ?>"+"，付款时间不能早于下单时间！"); 
	$("#form1").validate({
		errorPlacement: function(error, element){
			error.appendTo(element.parent().parent().prev().find('td:first'));
        },
        rules : {
        	payment_time : {
                required : true,
                times:true
            },
            payment_code : {
                required : true
            },
            trade_no    :{
                required : true
            }       
        },
        messages : {
        	payment_time : {
                required : '请填写付款准确时间',
                times:'你的下单时间为'+'<?php echo date('Y-m-d H:i:s',$output['order_info']['add_time']); ?>'+'，付款时间不能早于下单时间！'
            },
            payment_code : {
                required : '请选择付款方式'
            },
            trade_no : {
                required : '请填写第三方支付平台交易号'
            }
        }
	});
});
</script> 
