<?php defined('InShopNC') or exit('Access Invalid!');?>
<script type="text/javascript">
$(document).ready(function(){
		$('#salelog_demo').find('.demo').ajaxContent({
			event:'click', //mouseover
			loaderType:"img",
			loadingMsg:"<?php echo SHOP_TEMPLATES_URL;?>/images/transparent.gif",
			target:'#salelog_demo'
		});

});
</script>

<table width="100%" border="0" cellpadding="0" cellspacing="0" class="mt10">
  <thead>
    <tr>
      <th class="w200">采购方</th>
      <?php if($output['show_price']['show_price']){?><th class="w100">采购价</th><?php }?>
      <th class="">采购数量</th>
      <th class="w200">采购时间</th>
    </tr>
  </thead>
  <?php if(!empty($output['sales']) && is_array($output['sales'])){?>
  <tbody>
    <?php foreach($output['sales'] as $key=>$sale){?>
    <tr>
      <td><?php echo mb_strlen($sale['member_org_name'],'UTF-8')<=1?$sale['member_org_name'].'***':mb_substr($sale['member_org_name'],0,1,'UTF-8').'***'.mb_substr($sale['member_org_name'],mb_strlen($sale['member_org_name'],'UTF-8')-1,1,'UTF-8');?></td>
        <?php if($output['show_price']['show_price']){?>
        <td><em class="price"><?php echo $lang['currency'].$sale['goods_price'];?></em> <i style="color:red;"><?php echo $output['order_type'][$sale['goods_type']];?></i></td>
        <?php }?>
      <td><?php echo $sale['goods_num'];?></td>
      <td><time><?php echo date('Y-m-d H:i:s', $sale['add_time']);?></time></td>
    </tr>
    <?php }?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="10" class="tr" ><div class="pagination"> <?php echo $output['show_page'];?> </div></td>
    </tr>
  </tfoot>
  <?php }else{?>
  <tbody>
    <tr>
      <td colspan="10" class="ncs-norecord"><?php echo $lang['no_record'];?></td>
    </tr>
  </tbody>
  <?php }?>
</table>
