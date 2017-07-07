<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <table class="table tb-type2 order">
    <tbody>
      <tr class="space">
        <th colspan="2"><?php echo $lang['order_detail'];?></th>
      </tr>
      <tr>
        <th><?php echo $lang['order_info'];?></th>
      </tr>
      <tr>
        <td colspan="2"><ul>
            <li>
            <strong><?php echo $lang['order_number'];?>:</strong><?php echo $output['order_info']['order_sn'];?>
            ( 支付单号 <?php echo $lang['nc_colon'];?> <?php echo $output['order_info']['pay_sn'];?> )
            </li>
            <li><strong><?php echo $lang['order_state'];?>:</strong><?php echo orderState($output['order_info']);?></li>
            <li><strong><?php echo $lang['order_total_price'];?>:</strong><span class="red_common"><?php echo $lang['currency'].$output['order_info']['order_amount'];?> </span>
            	<?php if($output['order_info']['refund_amount'] > 0) { ?>
            	(<?php echo $lang['order_refund'];?>:<?php echo $lang['currency'].$output['order_info']['refund_amount'];?>)
            	<?php } ?></li>
            <li><strong><?php echo $lang['order_total_transport'];?>:</strong><?php echo $lang['currency'].$output['order_info']['shipping_fee'];?></li>
            <?php if(in_array($output['order_info']['payment_code'],array('alipay','tenpay','chinabank','transfer'))){ ?>
              <li><strong><?php echo orderPaymentName($output['order_info']['payment_code']).'付款：';?></strong><?php echo number_format($output['order_info']['order_amount']-$output['order_info']['rcb_amount']-$output['order_info']['pd_amount'],2); ?></li>
            <?php } ?>
            <?php if($output['order_info']['rcb_amount']>0){ ?>
              <li><strong>充值卡支付：</strong><?php echo $output['order_info']['rcb_amount'];?></li>
            <?php } ?>
            <?php if($output['order_info']['pd_amount']>0){ ?>
              <li><strong>余额支付：</strong><?php echo $output['order_info']['pd_amount'];?></li>
            <?php } ?>
            <?php if($output["order_total_rebates"]){?>
              <li><strong>返现金额：</strong><?php echo number_format($output['order_total_rebates'],2);?></li>
            <?php }?>
          </ul></td>
      </tr>
      <tr>
        <td><ul>
            <li><strong><?php echo $lang['buyer_name'];?><?php echo $lang['nc_colon'];?></strong><?php echo $output['order_info']['buyer_name'];?></li>
            <li><strong>供应商<?php echo $lang['nc_colon'];?></strong><?php echo $output['order_info']['store_name'];?></li>
            <li><strong><?php echo $lang['payment'];?><?php echo $lang['nc_colon'];?></strong><?php echo orderPaymentName($output['order_info']['payment_code']);?></li>
            <li><strong><?php echo $lang['order_time'];?><?php echo $lang['nc_colon'];?></strong><?php echo date('Y-m-d H:i:s',$output['order_info']['add_time']);?></li>
            <?php if(intval($output['order_info']['payment_time'])){?>
            <li><strong><?php echo $lang['payment_time'];?><?php echo $lang['nc_colon'];?></strong><?php echo date('Y-m-d H:i:s',$output['order_info']['payment_time']);?></li>
            <?php }?>
            <?php if(intval($output['order_info']['shipping_time'])){?>
            <li><strong><?php echo $lang['ship_time'];?><?php echo $lang['nc_colon'];?></strong><?php echo date('Y-m-d H:i:s',$output['order_info']['shipping_time']);?></li>
            <?php }?>
            <?php if(intval($output['order_info']['finnshed_time'])){?>
            <li><strong><?php echo $lang['complate_time'];?><?php echo $lang['nc_colon'];?></strong><?php echo date('Y-m-d H:i:s',$output['order_info']['finnshed_time']);?></li>
            <?php }?>
            <?php if($output['order_info']['extend_order_common']['order_message'] != ''){?>
            <li><strong><?php echo $lang['buyer_message'];?><?php echo $lang['nc_colon'];?></strong><?php echo $output['order_info']['extend_order_common']['order_message'];?></li>
            <?php }?>
          </ul></td>
      </tr>
      <tr>
        <th>收货人信息</th>
      </tr>
      <tr>
        <td><ul>
            <li><strong><?php echo $lang['consignee_name'];?><?php echo $lang['nc_colon'];?></strong><?php echo $output['order_info']['extend_order_common']['reciver_name'];?></li>
            <li><strong><?php echo $lang['tel_phone'];?><?php echo $lang['nc_colon'];?></strong><?php echo @$output['order_info']['extend_order_common']['reciver_info']['phone'];?></li>
            <li><strong><?php echo $lang['address'];?><?php echo $lang['nc_colon'];?></strong><?php echo @$output['order_info']['extend_order_common']['reciver_info']['address'];?></li>
            <li><strong>邮政编码<?php echo $lang['nc_colon'];?></strong><?php echo @$output['order_info']['extend_order_common']['reciver_info']['postcode'];?></li>
            <?php if($output['order_info']['shipping_code'] != ''){?>
            <li><strong><?php echo $lang['ship_code'];?><?php echo $lang['nc_colon'];?></strong><?php echo $output['order_info']['shipping_code'];?></li>
            <?php }?>
          </ul></td>
          </tr>
    <?php if (!empty($output['daddress_info'])) {?>
      <tr>
        <th>发货信息</th>
      </tr>
      <tr>
        <td><ul>
          <li><strong>发货人<?php echo $lang['nc_colon'];?></strong><?php echo $output['daddress_info']['seller_name']; ?></li>
          <li><strong><?php echo $lang['tel_phone'];?>:</strong><?php echo $output['daddress_info']['telphone'];?></li>
          <li><strong>发货地<?php echo $lang['nc_colon'];?></strong><?php echo $output['daddress_info']['area_info'];?>&nbsp;<?php echo $output['daddress_info']['address'];?>&nbsp;<?php echo $output['daddress_info']['company'];?></li>
          </ul></td>
          </tr>
    <?php } ?>
    <?php if (!empty($output['order_info']['extend_order_common']['invoice_info'])) {?>
      <tr>
      	<th>发票信息</th>
      </tr>
      <tr>
      <td><ul>
    <?php foreach ((array)$output['order_info']['extend_order_common']['invoice_info'] as $key => $value){?>
      <li><strong><?php echo $key.$lang['nc_colon'];?></strong><?php echo $value;?></li>
    <?php } ?>
          </ul></td>
      </tr>
    <?php } ?>
      <tr>
        <th><?php echo $lang['product_info'];?></th>
      </tr>
      <tr>
        <td><table class="table tb-type2 goods ">
            <tbody>
              <tr>
                <th></th>
                <th><?php echo $lang['product_info'];?></th>
                <th class="align-center">单价</th>
                <th class="align-center">实际支付额</th>
                <th class="align-center"><?php echo $lang['product_num'];?></th>
                <?php if($output["goods_2_rebates"]){?>
                 <th class="align-center">返现金额</th>
                <?php } ?>
                <th class="align-center">佣金比例</th>
                <th class="align-center">收取佣金</th>
              </tr>
              <?php foreach($output['goods_group'] as $common_goods){?>
                  <tr style="background-color: rgb(231,255,255)">
                      <td colspan="<?php echo $output["goods_2_rebates"]?"8":"7";?>">
                            此商品实际支付总额：<span class="red_common"><?php echo $lang['currency'].$common_goods['total_price'];?></span>
                            ，总数量：<span class="red_common"><?php echo $common_goods['total_num'];?></span>
                      </td>
                  </tr>
                  <?php foreach($common_goods as $goods){ if(is_array($goods)){?>
                      <tr>
                          <td class="w60 picture"><div class="size-56x56"><span class="thumb size-56x56"><i></i><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>" target="_blank"><img alt="<?php echo $lang['product_pic'];?>" src="<?php echo thumb($goods, 60);?>" /> </a></span></div></td>
                          <td class="w50pre">
                              <p><a href="<?php echo SHOP_SITE_URL;?>/index.php?act=goods&goods_id=<?php echo $goods['goods_id'];?>" target="_blank"><?php echo $goods['goods_name'];?></a></p>
                              <p><?php if(orderPayType($goods['goods_pay_type'],$goods['goods_type'])){?><span style="background-color: #FD6760;padding: 2px 4px;border-radius: 2px;color: #ffffff;line-height: 16px;"><?php echo orderPayType($goods['goods_pay_type'],$goods['goods_type']);?></span><?php }?></p></td>
                          <td class="w96 align-center"><span class="red_common"><?php echo $lang['currency'].$goods['goods_price'];?></span></td>
                          <td class="w96 align-center"><span class="red_common"><?php echo $lang['currency'].$goods['goods_pay_price'];?></span></td>
                          <td class="w96 align-center"><?php echo $goods['goods_num'];?></td>
                          <?php if($output["goods_2_rebates"]){?>
                             <td class="w96 align-center"><?php echo $output["goods_2_rebates"][$goods['goods_id']];?></td>
                          <?php } ?>
                          <td class="w96 align-center"><?php echo $goods['commis_rate'] == 200 ? '' : $goods['commis_rate'].'%';?></td>
                          <td class="w96 align-center"><?php echo $goods['commis_rate'] == 200 ? '' : ncPriceFormat($goods['goods_pay_price']*$goods['commis_rate']/100);?></td>
                      </tr>
                  <?php }} ?>
              <?php } ?>

            </tbody>
          </table></td>
      </tr>
    <!-- S 促销信息 -->
      <?php if(!empty($output['order_info']['extend_order_common']['promotion_info']) && !empty($output['order_info']['extend_order_common']['voucher_code'])){ ?>
      <tr>
      	<th>其它信息</th>
      </tr>
      <tr>
          <td>
        <?php if(!empty($output['order_info']['extend_order_common']['promotion_info'])){ ?>
        <?php echo $output['order_info']['extend_order_common']['promotion_info'];?>，
        <?php } ?>
        <?php if(!empty($output['order_info']['extend_order_common']['voucher_code'])){ ?>
        使用了面额为 <?php echo $lang['nc_colon'];?> <?php echo $output['order_info']['extend_order_common']['voucher_price'];?> 元的代金券，
         编码 : <?php echo $output['order_info']['extend_order_common']['voucher_code'];?>
        <?php } ?>
          </td>
      </tr>
      <?php } ?>
    <!-- E 促销信息 -->

    <?php if(is_array($output['refund_list']) and !empty($output['refund_list'])) { ?>
      <tr>
      	<th>退款记录</th>
      </tr>
      <?php foreach($output['refund_list'] as $val) { ?>
      <tr>
        <td>发生时间<?php echo $lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$val['admin_time']); ?>&emsp;&emsp;退款单号<?php echo $lang['nc_colon'];?><?php echo $val['refund_sn'];?>&emsp;&emsp;退款金额<?php echo $lang['nc_colon'];?><?php echo $lang['currency'];?><?php echo $val['refund_amount']; ?>&emsp;备注<?php echo $lang['nc_colon'];?><?php echo $val['goods_name'];?></td>
      </tr>
    <?php } ?>
    <?php } ?>
    <?php if(is_array($output['return_list']) and !empty($output['return_list'])) { ?>
      <tr>
      	<th>退货记录</th>
      </tr>
      <?php foreach($output['return_list'] as $val) { ?>
      <tr>
        <td>发生时间<?php echo $lang['nc_colon'];?><?php echo date("Y-m-d H:i:s",$val['admin_time']); ?>&emsp;&emsp;退货单号<?php echo $lang['nc_colon'];?><?php echo $val['refund_sn'];?>&emsp;&emsp;退款金额<?php echo $lang['nc_colon'];?><?php echo $lang['currency'];?><?php echo $val['refund_amount']; ?>&emsp;备注<?php echo $lang['nc_colon'];?><?php echo $val['goods_name'];?></td>
      </tr>
    <?php } ?>
    <?php } ?>



      <tr class="log" <?php if(empty($output['order_log']))echo 'style="display: none"';?>>
      	<th><?php echo $lang['order_handle_history'];?></th>
      </tr>
      <tr class="log" <?php if(empty($output['order_log']))echo 'style="display: none"';?>>
        <td>
          <ul  id="log_list">
              <?php if(is_array($output['order_log']) and !empty($output['order_log'])) { ?>
              <?php foreach($output['order_log'] as $val) { ?>
              <li style="width: 100%"><?php echo $val['log_role']; ?> <?php echo $val['log_user']; ?>&emsp;<?php echo $lang['order_show_at'];?>&emsp;<?php echo date("Y-m-d H:i:s",$val['log_time']); ?>&emsp;<?php echo $val['log_msg']; ?></li>
              <?php } ?>
              <?php } ?>
          </ul>
        </td>
      </tr>



    <tr>
        <th>备注：</th>
    </tr>
    <tr>
        <td>
            <ul>
                <li>
                    <div id="remark_txt"><?php echo $output['order_info']['remark'];?></div>
                    <textarea id="remark_box" style="width: 90%;height: 50px;display: none"></textarea>
                </li>
                <li><button id="remark_btn" state="display">编辑</button></li>
            </ul>
            <div>
            </div>

        </td>
        <td>

        </td>
    </tr>
    </tbody>
    <tfoot>
      <tr class="tfoot">
        <td><a href="JavaScript:void(0);" class="btn" onclick="history.go(-1);parent.window.order_view_id=<?php echo $output['order_info']['order_id']; ?>;"><span><?php echo $lang['nc_back'];?></span></a></td>
      </tr>
    </tfoot>
  </table>
</div>
<script>
    $(function(){
       $("#remark_btn").click(function(){
           var btn = $(this);
            var state = $(this).attr('state');
           if(state=='display'){
               $("#remark_txt").hide();
               $("#remark_box").show();
               $(this).html('保存');
               $(this).attr('state','edit');
           }else if(state=='edit'){
                var remark = $("#remark_box").val();
               $.post('index.php?act=order&op=edit_remark',{order_id:<?php echo $output['order_info']['order_id'];?>,remark:remark,form_submit:'ok',ajax:1},function(data,status){
                   if(data&&status=='success'){
                       $("#remark_txt").html(remark);
                       $("#remark_txt").show();
                       $("#remark_box").hide();
                       btn.attr('state','display');
                       btn.html('编辑');
                       $('#log_list').append(' <li style="width: 100%">'+data+'</li>');
                       if($('#log_list').closest('tr').css('display')=='none'){
                           $('.log').show();
                       }
                   }else{
                       alert('保存失败');
                   }
               });
           }else{
               alert('error');
           }
       });
    });
</script>