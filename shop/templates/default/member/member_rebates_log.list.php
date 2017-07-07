<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="wrap">
  <div class="tabmenu">
    <?php include template('layout/submenu'); ?>
    <a class="ncm-btn ncm-btn-orange" title="在线充值" href="index.php?act=predeposit&op=recharge_add" style="right: 207px;"><i class="icon-shield"></i>在线充值</a> <a class="ncm-btn ncm-btn-green" href="index.php?act=member_security&op=auth&type=pd_cash" style="right: 107px;"><i class="icon-money"></i>申请提现</a> <a class="ncm-btn ncm-btn-blue" href="index.php?act=predeposit&op=rechargecard_add"><i class="icon-shield"></i>充值卡充值</a> </div>
  <div class="alert"><span class="mr30">总返现：<strong class="mr5 red" style="font-size: 18px;"><?php echo $output['member_info']['pindan_rebates_total']; ?></strong><?php echo $lang['currency_zh'];?></span></div>
  <table class="ncm-default-table">
    <thead>
      <tr>
        <th class="w10"></th>
        <th class="w150 tl">返现时间</th>
        <th class="tl">订单号</th>
        <th class="tl">商品</th>
        <th class="w150 tl">返现金额(<?php echo $lang['currency_zh'];?>)</th>
      </tr>
    </thead>
    <tbody>
      <?php  if (count($output['list'])>0) { ?>
      <?php foreach($output['list'] as $v) { ?>
      <tr class="bd-line">
        <td></td>
        <td class="goods-time tl"><?php echo @date('Y-m-d H:i:s',$v['rebates_time']);?></td>
        <td class="tl"><a href="<?php echo urlShop("member_order","show_order",array("order_id"=>$v["order_id"]))?>"><?php echo $v["order_sn"];?></a></td>
        <td class="tl"><a href="<?php echo urlShop("goods","index",array("goods_id"=>$v["goods_id"]));?>"><?php echo $v["goods_name"];?></a></td>
        <td class="tl"><?php echo $v['total_rebates'];?>(<?php echo $v['goods_num'];?>×<?php echo $v['goods_rebates'];?>)</td>
      </tr>
      <?php } ?>
      <?php } else {?>
      <tr>
        <td colspan="20" class="norecord"><div class="warning-option"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></div></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php  if (count($output['list'])>0) { ?>
      <tr>
        <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?></div></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
