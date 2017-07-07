<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
.alertbox{
width: 500px;
  height: 300px;
  background-color: white;
  z-index: 100;
  margin: 110px 0px 0px 300px;
  position: absolute;
  border:solid 5px #787878;"
}
.top{
width:auto;
height:50px;
background-color: #4A5A78;
}
.topwz{
font-size:16px;
color:white;
text-align:left;
padding-top: 10px;
padding-left: 10px;
float:left;
}
.toptc{
text-align:right;
font-size:26px;
color:white;
float:right;
padding-top: 10px;
padding-right: 10px;
}

.main{
font-size: 14px;
  text-align: left;
  margin-top: 30px;
  margin-left: 30px;
}
.our{
font-size: 14px;
  text-align: left;
  margin-top: 30px;
  margin-left: 30px;
  color:blue;
}

.but{
	width: 150px;
  height: 40px;
  display: inline-block;
  text-align: center;
  margin: 59px 20px 1px 165px;
  border: solid 1px #787878;
  border-radius: 7px;
  font-size: 16px;
  line-height: 39px;
}

 #hidebg { position:absolute;left:0px;top:0px; 
      background-color:#000; 
      width:100%;  /*宽度设置为100%，这样才能使隐藏背景层覆盖原页面*/ 
      filter:alpha(opacity=60);  /*设置透明度为60%*/ 
      opacity:0.6;  /*非IE浏览器下设置透明度为60%*/ 
      display:none; 
      z-Index:2;} 
</style>
<div id="hidebg"></div> 
<div class="ncc-main">
  <div class="ncc-title">
  <div class="alertbox" id="alertbox" style="display:none;">
  <div class="top">
  <div class="topwz">登录其他平台支付 </div>
   <div class="toptc" id="toptc">X</div>
  </div>
  
  <div class="main">请您在新打开的支付平台页面进行支付，支付完成前请不要关闭该窗口</div>
  <div class="our"><a style="color:blue;" href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>">选择其他支付方式</a></div>
  <a class="but"  href="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING']; ?>">已完成支付</a>
  </div>
    <h3><?php echo $lang['cart_index_payment'];?></h3>
    <h5>订单详情内容可通过查看<a href="index.php?act=member_order" target="_blank">我的订单</a>进行核对处理。</h5>
  </div>
  <form action="index.php?act=payment&op=real_order" method="POST" id="buy_form" target="">
    <input type="hidden" name="pay_sn" value="<?php echo $output['pay_info']['pay_sn'];?>">
    <input type="hidden" id="payment_code" name="payment_code" value="">
    <div class="ncc-receipt-info">
      <div class="ncc-receipt-info-title">
        <h3><?php echo $output['order_remind'];?>
          <?php if ($output['pay_amount_online'] > 0) {?>
          在线支付金额：<strong>￥<?php echo $output['pay_amount_online'];?></strong>
          <?php } ?>
          </h3>
      </div>
      <table class="ncc-table-style">
        <thead>
          <tr>
            <th class="w50"></th>
            <th class="w200 tl">订单号</th>
            <th class="tl w150">支付方式</th>
            <th class="tl">金额</th>
            <th class="w150">物流</th>
          </tr>
        </thead>
        <tbody>
          <?php if (count($output['order_list'])>1) {?>
          <tr>
            <th colspan="20">由于您的商品由不同商家发出，此单将分为<?php echo count($output['order_list']);?>个不同子订单配送！</th>
          </tr>
          <?php }?>
          <?php foreach ($output['order_list'] as $key => $order) {?>
          <tr>
            <td></td>
            <td class="tl"><?php echo $order['order_sn']; ?></td>
            <td class="tl"><?php echo $order['payment_state'];?></td>
            <td class="tl">￥<?php echo $order['order_amount'];?></td>
            <td>快递</td>
          </tr>
          <?php  }?>
        </tbody>
      </table>
    </div>
    <div class="ncc-receipt-info">
      <?php if (!isset($output['payment_list'])) {?>
      <?php }else if (empty($output['payment_list'])){?>
      <div class="nopay"><?php echo $lang['cart_step2_paymentnull_1']; ?> <a href="index.php?act=member_message&op=sendmsg&member_id=<?php echo $output['order']['seller_id'];?>"><?php echo $lang['cart_step2_paymentnull_2'];?></a> <?php echo $lang['cart_step2_paymentnull_3'];?></div>
      <?php } else {?>
      <div class="ncc-receipt-info-title">
        <h3>支付选择</h3>
      </div>
      <div class="pay-tip">
          <p>给网银在线支付的客户的温馨提示：</p>
          <div>
              <p>选择网银在线支付过程，可能会因为您本身所持银行卡的限额，导致支付超出限额支付失败的情况。</p>
              <p style="color: rgb(255,102,0)">如出现支付失败，请换用其他银行卡进行尝试，或使用其他付款方式。</p>
              <p>具体各银行在线付款限额请向您所持银行卡发卡银行咨询。</p>
          </div>
      </div>
      <ul class="ncc-payment-list">
        <?php foreach($output['payment_list'] as $val) { ?>
        <li payment_code="<?php echo $val['payment_code']; ?>">
          <label for="pay_<?php echo $val['payment_code']; ?>">
          <i></i>
          <div class="logo" for="pay_<?php echo $val['payment_id']; ?>"> <img src="<?php echo SHOP_TEMPLATES_URL?>/images/payment/<?php echo $val['payment_code']; ?>_logo.gif" /> </div>
          </label>
        </li>
        <?php } ?>
      </ul>
      <?php } ?>
    </div>
    <?php if($output["pd_amount"]>0){?>
        <div class="predeposit-box" id="predeposit_box" style="display: none;">
                                     支付密码:<input type="password" id="paypwd" name="paypwd" />(余额:<span><?php echo $output["pd_amount"];?></span>)
        </div>
    <?php } ?>
    <?php if ($output['pay_amount_online'] > 0) {?>
    <div class="ncc-bottom tc mb50"><a href="javascript:void(0);" id="next_button" class="ncc-btn ncc-btn-green"><i class="icon-shield"></i>确认提交支付</a></div>
    <?php }?>
  </form>
</div>

<script type="text/javascript">
$(function(){
    $('.ncc-payment-list > li').on('click',function(){
    	$('.ncc-payment-list > li').removeClass('using');
        $(this).addClass('using');
        $('#payment_code').val($(this).attr('payment_code'));
        if($(this).attr('payment_code')=='chinabank'||$(this).attr('payment_code')=='fuiou'){
            $('.pay-tip').slideDown('fast');
        }else{
            $('.pay-tip').slideUp('fast');
        }
        <?php if($output["pd_amount"]>0){?>
	        if($(this).attr('payment_code')=='predeposit'){
	            $("#predeposit_box").show();
	        }else{
	        	$("#predeposit_box").hide();
	        }
        <?php } ?>
    });
    $('#next_button').on('click',function(){
        if ($('#payment_code').val() == '') {
        	showDialog('请选择支付方式', 'error','','','','','','','','',2);return false;
        }
        if($('#payment_code').val()=='predeposit'&&$('#paypwd').val()==''){
        	showDialog('请输入支付密码', 'error','','','','','','','','',2);return false;
         }
        if($('#payment_code').val() == 'alipay' || $('#payment_code').val() == 'chinabank'||$('#payment_code').val() == 'fuiou'){
        	 $('#buy_form').attr("target","_blank");
            }else{
            	 $('#buy_form').attr("target","");  
            }
        $('#buy_form').submit();
        if($('#payment_code').val() == 'alipay' || $('#payment_code').val() == 'chinabank'||$('#payment_code').val() == 'fuiou'){
       	 setTimeout(show,1000);
      	  function show(){      
        		document.getElementById("alertbox").style.display="block";
        		 hidebg.style.display="block";  //显示隐藏层 
        		   hidebg.style.height=document.body.clientHeight+"px";  //设置隐藏层的高度为当前页面高度 
       	 }  
      }
    });
});

$('#toptc').on('click',function(){
	document.getElementById("alertbox").style.display="none";
	 document.getElementById("hidebg").style.display="none"; 
});
</script>