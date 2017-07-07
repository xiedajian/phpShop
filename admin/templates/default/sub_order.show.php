<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/seller_center.css" rel="stylesheet" type="text/css">

<div style="margin-left: 10px;text-align: center;border: 1px solid #E6E6E6;width: 100px;line-height: 30px;margin-bottom: 20px;cursor: pointer" onclick="history.back()">返回</div>

<table class="ncsc-default-table order deliver" style="width: 98%;margin-left: 10px">
   <?php $order = $output['order_info'];?>
    <tbody>
    <tr>
        <th colspan="21"  style="background-color: #FEFDDC">
          <span class="ml5">
              <?php echo '订单编号'.$lang['nc_colon'];?>
              <strong><?php echo $order['order_sn']; ?></strong>
          </span>
            <span><?php echo '下单时间'.$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$order['add_time']); ?></em></span>
            <?php if (!empty($order['extend_order_common']['shipping_time'])) {?>
            <span><?php echo '发货时间'.$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$order['extend_order_common']['shipping_time']); }?></em></span> <span class="fr mr10">

        </th>
    </tr>
    <?php $i = 0; ?>
    <?php foreach($output['sub_order_list'] as $sub_order) { ?>
        <?php $i++; ?>
        <tr>
            <th colspan="21" style="background-color: #FAFAFA">
                <span class="ml5">子单号：<strong><?php echo $sub_order['sub_order_sn']; ?></strong></span>
                <span><?php echo '拆单时间'.$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$sub_order['addtime']); ?></em></span>
                <?php if($sub_order['send_time']>0){?>
                    <span><?php echo '发货时间'.$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$sub_order['send_time']); ?></em></span>
                <?php }?>
            </th>
        </tr>
        <?php $goods_index=0; foreach($sub_order['goods_info'] as $good_id=>$good_num){?>
            <tr>
                <td class="bdl w10"></td>
                <td class="w50"><div class="pic-thumb"><a href="<?php echo $order['extend_order_goods'][$good_id]['goods_url'];?>" target="_blank"><img src="<?php echo $order['extend_order_goods'][$good_id]['image_60_url']; ?>" onMouseOver="toolTip('<img src=<?php echo $order['extend_order_goods'][$good_id]['image_240_url'];?>>')" onMouseOut="toolTip()" /></a></div></td>
                <td class="tl">
                    <dl class="goods-name">
                        <dt><a target="_blank" href="<?php echo $order['extend_order_goods'][$good_id]['goods_url'];?>"><?php echo $order['extend_order_goods'][$good_id]['goods_name']; ?></a></dt>
                        <dd><strong>￥<?php echo $order['extend_order_goods'][$good_id]['goods_price']; ?></strong>&nbsp;x&nbsp;<em><?php echo $good_num; ?></em>件</dd>
                    </dl>
                </td>
                <!-- S 合并TD -->
                <?php if ((count($sub_order['goods_info']) > 1 && $goods_index == 0) || (count($sub_order['goods_info']) == 1)){?>
                    <td class="bdl bdr order-info w500" rowspan="<?php echo count($sub_order['goods_info']);?>">

                        <?php if($sub_order['sub_order_state']!=2){?>

                            <dl>
                                <dt>身份信息：</dt>
                                <dd>
                                    <div class="alert alert-info m0">
                                    <p><i class="icon-user"></i><?php echo $sub_order['buyer_info']['buyer_name']?><span class="ml30" title="<?php echo '';?>"><?php echo $sub_order['buyer_info']['buyer_id_card'];?></span></p>
                                    </div>
                                </dd>
                            </dl>
                            <dl>
                                <dt><?php echo '收货信息'.$lang['nc_colon'];?></dt>
                                <dd>
                                    <div class="alert alert-info m0">

                                        <p><i class="icon-user"></i><?php echo $sub_order['reciver_info']['reciver_name']?><span class="ml30" title="<?php echo '电话';?>"><i class="icon-phone"></i><?php echo $sub_order['reciver_info']['phone'];?></span></p>
                                        <p class="mt5" title="<?php echo $lang['store_deliver_buyer_address'];?>"><i class="icon-map-marker"></i><?php echo $sub_order['reciver_info']['area_info'].' '.$sub_order['reciver_info']['address'];?></p>
                                        <?php if ($order['extend_order_common']['order_message'] != '') {?>
                                            <p class="mt5" title="<?php echo $lang['store_deliver_buyer_address'];?>"><i class="icon-map-marker"></i><?php echo $order['extend_order_common']['order_message'];?></p>
                                        <?php } ?>
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
                        <?php if($sub_order['remark']!=''){?>
                            <dl>
                                <dt><?php echo '子单备注'.$lang['nc_colon'];?></dt>
                                <dd>
                                    <div class="alert alert-info m0">
                                        <?php echo $sub_order['remark'];?>
                                    </div>
                                </dd>
                            </dl>
                        <?php }?>


                        <dl>
                            <dt></dt>
                            <dd>


                                <?php if($sub_order['sub_order_state']==2){?><span>已退款</span><?php }?>
                            </dd>
                        </dl>
                    </td>


                <?php } ?>
                <!-- E 合并TD -->
            </tr>
            <?php $goods_index++;}?>
    <?php } ?>
    <!-- S 赠品列表 -->
    <?php if (!empty($order['zengpin_list'])) { ?>
        <tr>
            <td class="bdl w10"></td>
            <td colspan="4" class="tl" style="border-right: 1px solid #E6E6E6;">
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
    <!-- E 赠品列表 -->

    </tbody>


</table>
