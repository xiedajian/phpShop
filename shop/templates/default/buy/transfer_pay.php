<style type="text/css">
   .transfer-remark{padding-top:10px;}
   .transfer-remark h1{font-size:30px;color:#333;}
   .transfer-remark h1 span{color:#fe2f97;}
   
   .transfer-content{border:1px dashed #fe2f97;position:relative;margin-top:20px;padding:20px 0;font-size:15px;}
   .transfer-content>label{position:absolute;left:12px;top:-12px;font-size:16px;display:block;background-color:#FFF;color:#333;}
   .transfer-content >dl{line-height:40px;clear:both;}
   .transfer-content >dl>dt{float:left;width:200px;text-align:left;padding-left:100px;}
   .transfer-content >dl>dd{float:left;}
   .transfer-font-color{color:#fe2f97;}
   .btn-change-pay-way,.btn-change-pay-way:hover{background:#949494;padding:8px 50px;border-radius:5px;color:#FFF;font-size:14px;text-decoration: inherit;}
   .btn-change-go-center,.btn-change-go-center:hover{background:#42bdeb;padding:8px 50px;border-radius:5px;color:#FFF;font-size:14px;text-decoration: inherit;}
   .transfer-contect{text-align:right;padding:50px 0 10px 0;}
   .transfer-contect a{background:url(templates/default/images/btn_contect_qq.png) no-repeat;display:inline-block;width:147px;height:38px;}
   
   .footer-btns {padding:50px 0;text-align:center;}
</style>

<div class="ncc-main">
  <div class="ncc-title">
    <h3><?php echo $lang['cart_index_payment'];?></h3>
    <h5>订单详情内容可通过查看<a href="index.php?act=member_order" target="_blank">我的订单</a>进行核对处理。</h5>
  </div>
  <div class="transfer-remark">
     <h1>您已选择线下转账汇款<span><?php echo $output["order_info"]["api_pay_amount"];?></span>元</h1>
     </br>
     <h1>汇款完成后请将纸质或电子凭证截图发给我们的客服人员~</h1>
  </div>
  <div class="transfer-contect">
     <a href="tencent://message/?uin=2785299647"></a>
  </div>
  <div class="transfer-content">
     <label>收款方信息</label>
     <dl>
       <dt>对公基本账户名</dt>
       <dd><?php echo $output["payment_info"]["payment_config"]["account_name"];?></dd>
     </dl>
     <dl>
       <dt>账户</dt>
       <dd class="transfer-font-color"><?php echo $output["payment_info"]["payment_config"]["account_no"];?></dd>
     </dl>
     <dl>
       <dt>开户行</dt>
       <dd><?php echo $output["payment_info"]["payment_config"]["account_bank"];?></dd>
     </dl>
     <dl>
       <dt>对公支付宝账户</dt>
       <dd class="transfer-font-color"><?php echo $output["payment_info"]["payment_config"]["alipay_account"];?></dd>
     </dl>
     <dl style="clear:both;"></dl>
  </div>
  <div class="footer-btns">
     <a class="btn-change-pay-way" href="index.php?act=buy&op=pay&pay_sn=<?php echo $output["order_info"]["pay_sn"];?>">返回,更换支付方式</a>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <a class="btn-change-go-center" href="index.php?act=member&op=home">知道了,返回会员中心</a>
  </div>
 </div>