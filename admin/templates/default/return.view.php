<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3><?php echo $lang['return_manage'];?></h3>
      <ul class="tab-base">
        <li><a href="index.php?act=return&op=return_manage"><span><?php echo '待处理';?></span></a></li>
        <li><a href="index.php?act=return&op=return_all"><span><?php echo '所有记录';?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo $lang['nc_view'];?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <table class="table tb-type2 order">
      <tbody>

      <tr>
          <td colspan="2" class="required" style="background-color: #F3FBFE"><?php echo '退货信息'.$lang['nc_colon'];?></td>
      </tr>
        <tr>
          <td colspan="2" class="required">
              <ul>
                  <li><?php echo '商品名称'.$lang['nc_colon'];?><?php echo $output['return']['goods_name']; ?></li>
                  <li><?php echo '退款金额'.$lang['nc_colon'];?><?php echo ncPriceFormat($output['return']['refund_amount']); ?></li>
                  <li><?php echo '退货原因'.$lang['nc_colon'];?><?php echo $output['return']['reason_info']; ?></li>
                  <li><?php echo '退货说明'.$lang['nc_colon'];?><?php echo $output['return']['buyer_message']; ?></li>
              </ul>
          </td>
        </tr>


        <tr>
            <td colspan="2" class="required" style="background-color: #F3FBFE"><?php echo '退货物流'.$lang['nc_colon'];?></td>
        </tr>
        <tr>
            <td colspan="2" class="required">
                <ul>
                    <li><?php echo '物流公司'.$lang['nc_colon'];?><?php echo $output['express_list'][$output['return']['express_id']]['e_name']; ?></li>
                    <li><?php echo '物流编号'.$lang['nc_colon'];?><?php echo $output['return']['invoice_no']; ?></li>
                    <li></li>
                    <li><div id="search_deliver" style="cursor: pointer;color: #09C;">查看物流</div></li>
                </ul>
                <div style="margin: 16px;display: none" id="deliver_view" shipping_code="<?php echo $output['return']['invoice_no']; ?>" return_express_id="<?php echo $output['return']['express_id']; ?>">

                </div>
            </td>
        </tr>


        <tr>
          <td colspan="2" class="required" style="background-color: #F3FBFE"><?php echo '凭证上传'.$lang['nc_colon'];?></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform">
        <?php if (is_array($output['pic_list']) && !empty($output['pic_list'])) { ?>
          <?php foreach ($output['pic_list'] as $key => $val) { ?>
          <?php if(!empty($val)){ ?>
          <a href="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>" class="nyroModal" rel="gal">
            <img width="64" height="64" class="show_image" src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_PATH.'/refund/'.$val;?>"></a>
          <?php } ?>
          <?php } ?>
        <?php } ?>
            </td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><?php echo '卖家审核'.$lang['nc_colon'];?></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['state_array'][$output['return']['seller_state']];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><?php echo $lang['refund_seller_message'].$lang['nc_colon'];?></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['return']['seller_message']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php if ($output['return']['seller_state'] == 2) { ?>
        <tr>
          <td colspan="2" class="required"><?php echo '平台确认'.$lang['nc_colon'];?></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['admin_array'][$output['return']['refund_state']];?></td>
          <td class="vatop tips"></td>
        </tr>
        <tr>
          <td colspan="2" class="required"><?php echo $lang['refund_message'].$lang['nc_colon'];?></td>
        </tr>
        <tr class="noborder">
          <td class="vatop rowform"><?php echo $output['return']['admin_message']; ?></td>
          <td class="vatop tips"></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15" ><a href="JavaScript:void(0);" class="btn" onclick="history.go(-1)"><span><?php echo $lang['nc_back'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
$(function(){
    $('.nyroModal').nyroModal();

    $('#search_deliver').click(function(){
        var box = $('#deliver_view');
        if(box.attr('shipping_code')&&box.attr('return_express_id')){
            $.get('index.php?act=order&op=delivery_view&shipping_code='+box.attr('shipping_code')+'&express_id='+box.attr('return_express_id'),function(data,state){
                if(state=='success'){
                    box.attr('shipping_code','');
                    box.html(data);
                }else{
                    alert('查询失败');
                }

            });
        }
        if(box.css('display')=='none'){
            box.show();
        }else{
            box.hide();
        }
    });
});
</script>