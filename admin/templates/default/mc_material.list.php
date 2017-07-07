<?php defined('InShopNC') or exit('Access Invalid!');?>
<?php if(!empty($output['materials_list']) && is_array($output['materials_list'])){ ?>


    <ul class="dialog-goodslist-s2">
      <?php foreach($output['materials_list'] as $k => $v){ ?>
      <li>
        <div  onclick="select_materials(<?php echo $v['id'];?>);" class="goods-pic"><span class="ac-ico"></span><span class="thumb size-200x124"><i></i>
            <img select_meterials_id="<?php echo $v['id'];?>" title="<?php echo $v['title'];?>" src="<?php  $pic=json_decode($v['thumb'],true); echo $pic[0];?>"
                 onload="javascript:DrawImage(this,200,124);" /></span></div>
    <!--    <div class="goods-name"><a href="--><?php //echo SHOP_SITE_URL."/index.php?act=goods&goods_id=".$v['goods_id'];?><!--" target="_blank">--><?php //echo $v['title'];?><!--</a></div>-->
        <div class="materials-name"><?php echo $v['title'];?></a></div>
      </li>
      <?php } ?>
      <div class="clear"></div>
    </ul>
    <div class="pagination"> <?php echo $output['show_page'];?></div>
<?php } else { ?>
    <p class="no-record"><?php echo $lang['nc_no_record'];?></p>
<?php } ?>
<div class="clear"></div>
<script type="text/javascript">
	$('#show_materials_list .demo').ajaxContent({
		target:'#show_materials_list'
	});
</script>