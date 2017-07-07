<?php defined('InShopNC') or exit('Access Invalid!');?>
<style>
</style>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<div class="alert alert-block mt10">
  <ul class="mt5">
    <li>1、可以对待发货的订单进行发货操作，发货时可以设置收货人和发货人信息，填写一些备忘信息，选择相应的物流服务，打印发货单。</li>
    <li>2、已经设置为发货中的订单，您还可以继续编辑上次的发货信息。</li>
    <li>3、如果因物流等原因造成买家不能及时收货，您可使用点击延迟收货按钮来延迟系统的自动收货时间。</li>
  </ul>
</div>
<form method="get" action="index.php" target="_self">
  <table class="search-form">
    <input type="hidden" name="act" value="store_deliver" />
    <input type="hidden" name="op" value="take_apart_list" />

    <tr>
        <td></td>

      <th><?php echo $lang['store_order_add_time'];?></th>
      <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label>
        &nbsp;&#8211;&nbsp;
        <input id="query_end_date" class="text w70" type="text" name="query_end_date" value="<?php echo $_GET['query_end_date']; ?>" /><label class="add-on"><i class="icon-calendar"></i></label></td>


        <th><?php echo $lang['store_order_buyer'];?></span></th>
      <td class="w100"><input type="text" class="text w80" name="buyer_name" value="<?php echo trim($_GET['buyer_name']); ?>" /></td>
      <th><?php echo $lang['store_order_order_sn'];?></th>
      <td class="w160"><input type="text" class="text w150" name="order_sn" value="<?php echo trim($_GET['order_sn']); ?>" /></td>
        <th style="width: 10px;"></th>
        <td class="w100"><select name="sub_order_state">
                <option value="0" <?php if($_GET['sub_order_state']==0)echo 'selected="selected"';?>>子单状态</option>
                <option value="1" <?php if($_GET['sub_order_state']==1)echo 'selected="selected"';?>>未发货</option>
                <option value="2" <?php if($_GET['sub_order_state']==2)echo 'selected="selected"';?>>已发货</option>
            </select></td>
      <td class="w70 tc"><label class="submit-border">
          <input type="submit" class="submit"value="<?php echo $lang['store_order_search'];?>" />
        </label>

      </td>
        <td class="w70"> <a href="index.php?<?php echo $_SERVER['QUERY_STRING'];?>&op=export_step1" style="background-color: #F5F5F5;cursor: pointer;text-align: center;height: 28px;width: 64px;display: block;text-decoration: none;line-height: 28px;border: 1px solid #E6E6E6;color: #000000">导出</a></td>
    </tr>
  </table>
</form>
<table class="ncsc-default-table order deliver">
  <?php if (is_array($output['order_list']) and !empty($output['order_list'])) { ?>
  <?php foreach($output['order_list'] as $order_id => $order) {if(!$output['sub_order_list'][$order_id])continue;?>
  <tbody>
    <tr>
      <td colspan="21" class="sep-row"></td>
    </tr>
    <tr>
      <th colspan="21"  style="background-color: #FFCCE8;border-color: #FFCCE8;height: 30px;">
          <span class="order-star"></span>
          <span class="ml5">
              <?php echo $lang['store_order_order_sn'].$lang['nc_colon'];?>
              <strong><?php echo $order['order_sn']; ?></strong>
          </span>
          <span><?php echo $lang['store_order_add_time'].$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$order['add_time']); ?></em></span>
        <?php if (!empty($order['extend_order_common']['shipping_time'])) {?>
        <span><?php echo '发货时间'.$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$order['extend_order_common']['shipping_time']); }?></em></span> <span class="fr mr10">
        <?php if ($order['shipping_code'] != ''){?>
        <a href="index.php?act=store_deliver&op=search_deliver&order_sn=<?php echo $order['order_sn']; ?>" class="ncsc-btn-mini"><i class="icon-compass"></i><?php echo $lang['store_order_show_deliver'];?></a>
        <?php }?>
              <a href="index.php?act=store_deliver&op=take_apart_edit&order_id=<?php echo $order['order_id']; ?>" class="ncsc-btn-mini" style="background-color: #009900;color: #ffffff"><i class="icon-compass"></i>编辑拆单</a>

              <?php if($order['order_state']==30){?>
                  <a href="javascript:void(0)" style="border-color:#F89406 !important;" class="ncsc-btn-mini ncsc-btn-orange ml5 fr" uri="index.php?act=store_deliver&op=delay_receive&order_id=<?php echo $order['order_id']; ?>" dialog_width="480" dialog_title="延迟收货" nc_type="dialog" dialog_id="seller_order_delay_receive" id="order<?php echo $order['order_id']; ?>_action_delay_receive" /><i class="icon-time"></i></i>延迟收货</a>
              <?php }?>
          </span>
      </th>
    </tr>
    <tr>
        <th  colspan="21" style="border-left: 1px solid #FFCCE8;border-right: 1px solid #FFCCE8;border-bottom: none;background-color: #ffffff !important;">
            <div style="padding: 0px 15px 10px 15px;">
                <dl style="border-bottom: 1px dashed red;padding: 5px 15px;">
                    <dt style="display: inline-block">买家：</dt>
                    <dd style="display: inline-block"><?php echo $output['store_list'][$order['buyer_id']]['org_name'];?>&nbsp;&nbsp;,&nbsp;&nbsp;
                        <?php echo $order['buyer_name'];?>&nbsp;&nbsp;,&nbsp;&nbsp;<?php echo $output['store_list'][$order['buyer_id']]['member_mobile'];?>
                    </dd>
                    </dl>
                <dl style="border-bottom: 1px dashed red;padding: 5px 15px;">
                    <dt style="display: inline-block">买家留言：</dt>
                    <dd style="display: inline-block"><?php echo $order['extend_order_common']['order_message'];?></dd>
                </dl>
                <dl style="padding: 5px 15px;">
                    <dt><strong>交易备注：</strong></dt>
                    <dd style="padding: 10px 0px">
                        <?php if($output['order_log'][$order_id]){?>
                        <ul>
                            <?php foreach($output['order_log'][$order_id] as $val){?>
                            <li>
                                <?php echo $val['log_role']; ?> <?php echo $val['log_user']; ?>&emsp;<?php echo '于';?>&emsp;<?php echo date("Y-m-d H:i:s",$val['log_time']); ?>&emsp;<?php echo $val['log_msg']; ?>
                            </li>
                            <?php }?>
                        </ul>
                        <?php }?>
                    </dd>

                </dl>
            </div>
        </th>
    </tr>
    <?php $i = 0; ?>
    <?php foreach($output['sub_order_list'][$order_id] as $sub_order) { ?>
    <?php $i++; ?>
    <tr>
        <th colspan="21" style="background-color: #F2F2F2;border-left:  1px solid #FFCCE8;border-right:  1px solid #FFCCE8;border-top: none;border-bottom: none">
             <span class="ml5">子单号：<strong><?php echo $sub_order['sub_order_sn']; ?></strong></span>
             <span><?php echo '拆单时间'.$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$sub_order['addtime']); ?></em></span>
            <?php if($sub_order['send_time']>0){?>
             <span><?php echo '发货时间'.$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$sub_order['send_time']); ?></em></span>
            <?php }?>
        </th>
    </tr>

    <?php $goods_index=0; foreach($sub_order['goods_info'] as $good_id=>$good_num){?>
    <tr>
      <td class="bdl w10" style="border-left:  1px solid #FFCCE8;"></td>
      <td class="w50"><div class="pic-thumb"><a href="<?php echo $order['extend_order_goods'][$good_id]['goods_url'];?>" target="_blank"><img src="<?php echo $order['extend_order_goods'][$good_id]['image_60_url']; ?>" onMouseOver="toolTip('<img src=<?php echo $order['extend_order_goods'][$good_id]['image_240_url'];?>>')" onMouseOut="toolTip()" /></a></div></td>
      <td class="tl">
         <dl class="goods-name">
            <dt><a target="_blank" href="<?php echo $order['extend_order_goods'][$good_id]['goods_url'];?>"><?php echo $order['extend_order_goods'][$good_id]['goods_name']; ?></a></dt>
            <dd><strong>￥<?php echo $order['extend_order_goods'][$good_id]['goods_price']; ?></strong>&nbsp;x&nbsp;<em><?php echo $good_num; ?></em>件</dd>
         </dl>
      </td>
      <!-- S 合并TD -->
      <?php if ((count($sub_order['goods_info']) > 1 && $goods_index == 0) || (count($sub_order['goods_info']) == 1)){?>
      <td class="bdl bdr order-info w500" rowspan="<?php echo count($sub_order['goods_info'])+1;?>" style="border-right: 1px solid #FFCCE8;border-bottom: none">

              <?php if($sub_order['sub_order_state']!=2){?>

                  <dl>
                      <dt><?php echo '身份信息'.$lang['nc_colon'];?></dt>
                      <dd>
                          <div class="alert alert-info m0">
                              <p><i class="icon-user"></i>
                                  <?php echo $sub_order['buyer_info']['buyer_name']?><span class="ml30">
                                      <?php echo $sub_order['buyer_info']['buyer_id_card'];?></span></p>

                          </div>
                      </dd>
                  </dl>
        <dl>
          <dt><?php echo '收货信息'.$lang['nc_colon'];?></dt>
          <dd>
            <div class="alert alert-info m0">
              <p><i class="icon-user"></i><?php echo $sub_order['reciver_info']['reciver_name']?><span class="ml30" title="<?php echo '电话';?>"><i class="icon-phone"></i><?php echo $sub_order['reciver_info']['phone'];?></span></p>
              <p class="mt5" title="<?php echo $lang['store_deliver_buyer_address'];?>"><i class="icon-map-marker"></i><?php echo $sub_order['reciver_info']['area_info'].' '.$sub_order['reciver_info']['address'];?></p>
              <p class="mt5" title="<?php echo $lang['store_deliver_buyer_address'];?>"><i class="icon-map-marker"></i><?php echo $sub_order['reciver_info']['postcode'];?></p>

            </div>
          </dd>
        </dl>
                    <?php }?>

          <?php if($sub_order['sub_order_state']==1){?>
        <dl>
          <dt><?php echo '物流'.$lang['nc_colon'];?></dt>
          <dd>
              <div class="alert alert-info m0">
                  <?php echo $sub_order['e_name'].','.$sub_order['shipping_code'];?>
              </div>
          </dd>
        </dl>
         <?php }?>
          <dl>
              <dt></dt>
              <dd>

                  <?php if($sub_order['sub_order_state']==0){?>
                      <span><a style="margin-left: 20px;" onclick="get_confirm('子订单的全部商品将会退款处理？','index.php?act=store_deliver&op=sub_order_refund&order_id=<?php echo $order['order_id'];?>&sub_id=<?php echo $sub_order['sub_order_id'];?>')" href="javascript:void(0)" class="ncsc-btn-mini ncsc-btn-acidblue fr">退款</a></span>
                    <?php }?>
                <?php if($sub_order['sub_order_state']==1){?>
                  <span><a style="margin-left: 20px;" href="index.php?act=store_deliver&op=sub_search_deliver&order_id=<?php echo $order['order_id'];?>&sub_id=<?php echo $sub_order['sub_order_id'];?>" class="ncsc-btn-mini ncsc-btn-acidblue fr">查看物流</a></span>
                <?php }?>
                <?php if($sub_order['sub_order_state']==2){?><span>已退款</span><?php }else{?>
                    <span><a style="margin-left: 20px;" href="index.php?act=store_deliver&op=sub_order_edit&order_id=<?php echo $order['order_id'];?>&sub_id=<?php echo $sub_order['sub_order_id'];?>" class="ncsc-btn-mini ncsc-btn-acidblue fr">编辑发货</a></span>
                    <?php }?>
              </dd>
          </dl>
        </td>


      <?php } ?>
      <!-- E 合并TD -->
    </tr>

    <?php $goods_index++;}?>
        <tr>
            <td class="bdl w10" style="border-left:  1px solid #FFCCE8;border-bottom: none"></td>
            <td class="w50" style="border-bottom: none">备注:</td>
            <td class="tl" style="border-bottom: none">
                <input type="hidden" value="<?php echo $sub_order['remark'];?>">
                <textarea class="edit-remark" style="height: 60px;width: 90%;padding: 5px;resize: vertical" sub_id="<?php echo $sub_order['sub_order_id'];?>"><?php echo $sub_order['remark'];?></textarea>
            </td>
        </tr>
    <?php } ?>

    <!-- S 赠品列表 -->
    <?php if (!empty($order['zengpin_list'])) { ?>
        <tr>
            <td class="bdl w10" style="border-left: 1px solid #FFCCE8;border-right: 1px solid #FFCCE8;border-bottom: none"></td>
            <td colspan="4" class="tl" style="border-left: 1px solid #FFCCE8;border-right: 1px solid #FFCCE8;border-bottom: none">
                <div class="ncsc-goods-gift">赠品：
                    <ul>
                        <?php foreach ($order['zengpin_list'] as $k => $zengpin_info) { ?>
                            <li><a title="赠品：<?php echo $zengpin_info['goods_name'];?> * <?php echo $zengpin_info['goods_num'];?>" href="<?php echo $zengpin_info['goods_url'];?>" target="_blank"><img src="<?php echo $zengpin_info['image_60_url'];?>" onMouseOver="toolTip('<img src=<?php echo $zengpin_info['image_240_url'];?>>')" onMouseOut="toolTip()"/></a></li>
                        <?php } ?>
                    </ul>
                </div>
            </td>
        </tr>
    <?php } ?>
    <tr>
        <td colspan="21" style="border-bottom: 1px solid #FFCCE8;height: 0px;padding: 0px;"></td>
    </tr>
    <!-- E 赠品列表 -->
    <?php } } else { ?>
    <tr>
      <td colspan="21" class="norecord"><div class="warning-option"><i class="icon-warning-sign"></i><span><?php echo $lang['no_record'];?></span></div></td>
    </tr>
    <?php } ?>
  </tbody>
  <tfoot>
    <?php if (!empty($output['order_list'])) { ?>
    <tr>
      <td colspan="21"><div class="pagination"><?php echo $output['show_page']; ?></div></td>
    </tr>
    <?php } ?>
  </tfoot>
</table>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
$(function(){
    $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.edit-remark').blur(function(){
        var sub_id = $(this).attr('sub_id');
        var remark = $(this).val().trim();
        var old = $(this).parent().find('input');
        if(old.val()!=remark){
            $.ajax({
                type:'post',
                url:"index.php?act=store_deliver&op=edit_sub_remark",
                data:{remark:remark,sub_id:sub_id},
                dataType:'json',
                success:function(result){
                    if(result<=0){
                        alert('编辑备注失败！');
                    }else{
                        old.val(remark);
                    }
                }
            });
        }

    });
});
</script> 
