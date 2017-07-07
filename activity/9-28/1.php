<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_activity.css" rel="stylesheet" type="text/css">
<div style="text-align:center;padding-top:20px;">
<div class="box">
 <div class="zhiyoubox">
 
      <a href="<?php echo urlShop('zhiyoubao', 'index');?>" class="but_down"></a>
         <?php foreach($output['show_goods_class'] as $key=>$gc_list){?>
    	<?php if($gc_list['gc_name']=='直邮宝'){ ?>
      <a href="<?php echo urlShop('search', 'index', array('cate_id' =>$gc_list['gc_id']));?>" class="but_down2"></a>
      <?php } ?>
      <?php } ?>
</div>

<div class="zhiyouboxf">
      <a href="<?php echo urlShop('zhiyoubao', 'index');?>" class="but_down"></a>
        <?php foreach($output['show_goods_class'] as $key=>$gc_list){?>
    	<?php if($gc_list['gc_name']=='直邮宝'){ ?>
      <a href="<?php echo urlShop('search', 'index', array('cate_id' =>$gc_list['gc_id']));?>" class="but_down2"></a>
       <?php } ?>
      <?php } ?>
</div>
</div>
<img src="/activity/9-28/1.jpg" width="1200" height="5592" usemap="#Map" border="0" />
<map name="Map" id="Map">
  <area shape="rect" coords="0,0,110,260" href="http://www.ipvp.cn/shop/index.php?act=show_joinin&amp;op=index" target="_blank" />
</map>
</div>
