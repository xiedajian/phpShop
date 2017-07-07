<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_goods.css" rel="stylesheet" type="text/css">
<style type="text/css">
.ncs-goods-picture .levelB, .ncs-goods-picture .levelC { cursor: url(<?php echo SHOP_TEMPLATES_URL;?>/images/shop/zoom.cur), pointer;}
.ncs-goods-picture .levelD { cursor: url(<?php echo SHOP_TEMPLATES_URL;?>/images/shop/hand.cur), move\9;}
.postage-fee{
    background-color: rgb(0,153,0);
    color: white;
    border-radius: 13px;
    width: 65px;
    display: inline-block;
    text-align: center;
    margin-right: 5px;
}
</style>

<div id="content" class="wrapper pr">
    <input type="hidden" id="lockcompare" value="unlock" />
  <div class="ncs-detail<?php if ($output['store_info']['is_own_shop']) echo ' ownshop'; ?>">
    <!-- S 商品图片 -->
    <div id="ncs-goods-picture" class="ncs-goods-picture image_zoom"> </div>
    <!-- S 商品基本信息 -->
    <div class="ncs-goods-summary">
      <div class="name">
        <div style="display: inline-block;font-size: 16px;font-weight: bolder;color:black;font-family: 'Microsoft Yahei', '微软雅黑'"><?php  if($output['goods']['goods_tag']=='保税仓商品'){ ?>
                <em class="tm-promo-type">保税仓发货<s></s></em>
            <?php  }?><?php echo $output['goods']['common_goods_name']; ?></div>
        <strong><?php echo str_replace("\n", "<br>", $output['goods']['goods_jingle']);?></strong> </div>
      <div class="ncs-meta">
        <div class="rate"> <!-- S 描述相符评分 --><a href="#ncGoodsRate">商品评分</a>
          <div class="raty" data-score="<?php echo $output['goods_evaluate_info']['star_average'];?>"></div>
          <!-- E 描述相符评分 --> </div>
          <!-- S 商品参考价格 -->
          <dl>
              <dt><?php echo $lang['goods_index_goods_cost_price'];?><?php echo $lang['nc_colon'];?></dt>
              <dd class="cost-price"><strong><?php echo $lang['currency'].$output['goods']['goods_marketprice'];?></strong></dd>
          </dl>
          <!-- E 商品参考价格 -->
        <?php if(!$output['goods']['show_price']){?>
            <dl>
                <dt><?php echo $lang['goods_index_goods_price'];?><?php echo $lang['nc_colon'];?></dt>
                <dd><?php echo $output['goods']['show_price_tip'];?></dd>
            </dl>
        <?php }else{?>
        <!-- S 商品发布价格 -->
        <dl>
          <dt><?php echo $lang['goods_index_goods_price'];?><?php echo $lang['nc_colon'];?></dt>
          <dd class="<?php if($output['goods']['show_vip_price']) echo 'cost-';?>price">
            <?php if (isset($output['goods']['title']) && $output['goods']['title'] != '') {?>
            <span class="tag"><?php echo $output['goods']['title'];?></span>
            <?php }?>
            <?php if (isset($output['goods']['promotion_price']) && !empty($output['goods']['promotion_price'])) {?>
            <strong><?php echo $lang['currency'].$output['goods']['promotion_price'];?></strong><em>(原售价<?php echo $lang['nc_colon'];?><?php echo $lang['currency'].$output['goods']['goods_price'];?>)</em>
            <?php } else {?>
            <strong><?php echo $lang['currency'].$output['goods']['goods_price'];?></strong>
            <?php }?>
          </dd>
        </dl>
        <!-- E 商品发布价格 -->
        <!--  商品发布VIP价格 -->
          <?php if($output['goods']['show_vip_price']){?>
          <dl>
              <dt>会&nbsp;员&nbsp;价<?php echo $lang['nc_colon'];?></dt>
              <dd class="price"><strong><?php echo $lang['currency'].$output['goods']['goods_vip_price'];?></strong></dd>
          </dl>
          <?php }?>
        <!-- E 商品发布VIP价格 -->
        <!-- S 促销 -->
        <?php if (isset($output['goods']['promotion_type']) || $output['goods']['have_gift'] == 'gift') {?>
        <dl>
          <dt>促销信息：</dt>
          <dd class="promotion-info">
            <!-- S 限时折扣 -->
            <?php if ($output['goods']['promotion_type'] == 'xianshi') {?>
            <?php echo '直降：'.$lang['currency'].$output['goods']['down_price'];?>
            <?php if($output['goods']['lower_limit']) {?>
            <em><?php echo sprintf('最低%s件起',$output['goods']['lower_limit']);?></em>
            <?php } ?>
            <span><?php echo $output['goods']['explain'];?></span><br>
            <?php }?>
            <!-- E 限时折扣  -->
            <!-- S 抢购-->
            <?php if ($output['goods']['promotion_type'] == 'groupbuy') {?>
            <?php if ($output['goods']['upper_limit']) {?>
            <em><?php echo sprintf('最多限购%s件',$output['goods']['upper_limit']);?></em>
            <?php } ?>
            <span><?php echo $output['goods']['remark'];?></span><br>
            <?php }?>
            <!-- E 抢购 -->
            <!-- S 赠品 -->
            <?php if ($output['goods']['have_gift'] == 'gift') {?>
            <?php echo '赠品'?> <span>赠下方的热销商品，赠完即止</span>
            <?php }?>
            <!-- E 赠品 -->
          </dd>
        </dl>
        <?php }?>
        <!-- E 促销 -->
        <?php }?>
      </div>
      <div class="ncs-plus" style="padding: 5px 0px;">
        <!-- S 物流运费  预售商品不显示物流 -->
        <?php if ($output['goods']['is_virtual'] == 0) {?>
        <dl class="ncs-freight">
          <dt>
            <?php if ($output['goods']['goods_transfee_charge'] == 1){?>
            <?php echo $lang['goods_index_freight'].$lang['nc_colon'];?>
            <?php }else{?>
            <!-- 如果买家承担运费 -->
            <!-- 如果使用了运费模板 -->
            <?php if ($output['goods']['transport_id'] != '0'){?>
            <?php echo $lang['goods_index_trans_to'];?><a href="javascript:void(0)" id="ncrecive"><?php echo $lang['goods_index_trans_country'];?></a><?php echo $lang['nc_colon'];?>
            <div class="ncs-freight-box" id="transport_pannel">
              <?php if (is_array($output['area_list'])){?>
              <?php foreach($output['area_list'] as $k=>$v){?>
              <a href="javascript:void(0)" nctype="<?php echo $k;?>"><?php echo $v;?></a>
              <?php }?>
              <?php }?>
            </div>
            <?php }else{?>
            <?php echo $lang['goods_index_trans_zcountry'];?><?php echo $lang['nc_colon'];?>
            <?php }?>
            <?php }?>
          </dt>
          <dd id="transport_price">
            <?php if($output['goods']['promotion_type'] == 'groupbuy') { ?>
            <span><?php echo $lang['goods_index_groupbuy_no_shipping_fee'];?></span>
            <?php } else { ?>
            <?php if ($output['goods']['goods_freight'] == 0){?>
            <span id="nc_kd"><?php echo $lang['goods_index_trans_for_seller'];?></span>
            <?php }else{?>
            <!-- 如果买家承担运费 -->
            <span id="nc_kd">运费<?php echo $lang['nc_colon'];?><em><?php echo $output['goods']['goods_freight'];?></em><?php echo $lang['goods_index_yuan'];?></span>
            <?php }?>
            <?php }?>
          </dd>
          <dd style="color:red;display:none" id="loading_price">loading.....</dd>
        </dl>
        <?php }?>
          <?php if($output['goods']['goods_freight'] != 0&&$output['store_info']['store_free_price']>0){?>
          <dl>
              <dt>
                优&nbsp;惠：
              </dt>
              <dd>
                  <span class="postage-fee"><i class="icon-gift" style="padding-right: 3px;"></i>免运费</span>满<span style="font-weight: bold"><?php echo $output['store_info']['store_free_price'];?></span>免运费
              </dd>
          </dl>
          <?php }?>
        <!-- E 物流运费 --->

        <!-- S 赠品 -->
        <?php if ($output['goods']['have_gift'] == 'gift') {?>
        <dl>
          <dt>赠&#12288;&#12288;品：</dt>
          <dd class="goods-gift" id="ncsGoodsGift">
            <?php if (!empty($output['gift_array'])) {?>
            <ul>
              <?php foreach ($output['gift_array'] as $val){?>
              <li>
                <div class="goods-gift-thumb"><span><img src="<?php echo cthumb($val['gift_goodsimage'], '60', $output['goods']['store_id']);?>"></span></div>
                <a href="<?php echo urlShop('goods', 'index', array('goods_id' => $val['gift_goodsid']));?>" class="goods-gift-name" target="_blank"><?php echo $val['gift_goodsname']?></a><em>x<?php echo $val['gift_amount'];?></em> </li>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <!-- S 赠品 -->
      </div>
      <?php if(!empty($output["pindan_info"])){?>
         	<?php $scheduler_val=intval($output["pindan_info"]["current_num"]*100/$output["pindan_info"]["success_num"]); ?>
				<div class="pindan-info">
				   <div class="remark">拼单成功需达到<?php echo $output["pindan_info"]["success_num"]?>件,成功后每件将返利<span><?php echo $output["goods"]["pindan_goods_rebates"]?></span>元</div>
                   <div class="percentage-box">
                       <div class="percentage-value">
                         <span class="bg"></span>
                         <span class="txt"><?php echo $scheduler_val ?>%</span></div>
                       <div class="percentage-text">已拼单:<?php echo $output["pindan_info"]["current_num"];?>件</div>
                       <div style="clear:both;"></div>
                   </div>
                   <div class="scheduler-box">
                        <span class="scheduler" style="width:<?php echo $scheduler_val>100?100:$scheduler_val ?>%"></span>
                   </div>
                   <div id="pindan_time" time_down="<?php echo $output["pindan_info"]["time_down"] ?>" class="pindan-time">
                        距离本轮拼单结束&nbsp;&nbsp;<span>0</span><span>0</span>:<span>0</span><span>0</span>:<span>0</span><span>0</span>
                   </div>
				</div>
      <?php } ?>
      <?php if($output['goods']['goods_state'] != 10 && $output['goods']['goods_verify'] == 1){?>
      <div class="ncs-key">
        <!-- S 商品规格值-->
        <?php if (is_array($output['goods']['spec_name'])) { ?>
        <?php foreach ($output['goods']['spec_name'] as $key => $val) {?>
        <dl nctype="nc-spec">
          <dt><?php echo $val;?><?php echo $lang['nc_colon'];?></dt>
          <dd>
            <?php if (is_array($output['goods']['spec_value'][$key]) and !empty($output['goods']['spec_value'][$key])) {?>
            <ul nctyle="ul_sign">
              <?php foreach($output['goods']['spec_value'][$key] as $k => $v) {?>
              <?php if( $key == 1 ){?>
              <!-- 图片类型规格-->
              <li class="sp-img"><a href="javascript:void(0);" class="<?php if (isset($output['goods']['goods_spec'][$k])) {echo 'hovered';}?>" data-param="{valid:<?php echo $k;?>}" title="<?php echo $v;?>"><img src="<?php echo $output['spec_image'][$k];?>"/><?php echo $v;?></a></li>
              <?php }else{?>
              <!-- 文字类型规格-->
              <li class="sp-txt"><a href="javascript:void(0)" class="<?php if (isset($output['goods']['goods_spec'][$k])) { echo 'hovered';} ?>" data-param="{valid:<?php echo $k;?>}"><?php echo $v;?></a></li>
              <?php }?>
              <?php }?>
            </ul>
            <?php }?>
          </dd>
        </dl>
        <?php }?>
        <?php }?>
        <!-- E 商品规格值-->
        <?php if ($output['goods']['is_virtual'] == 1) {?>
        <dl>
          <dt>提货方式：</dt>
          <dd>
            <ul>
              <li class="sp-txt"><a href="javascript:void(0)" class="hovered">电子兑换券<i></i></a></li>
            </ul>
          </dd>
        </dl>
        <?php }?>
        <?php if ($output['goods']['is_virtual'] == 1) {?>
        <!-- 虚拟商品有效期 -->
        <dl>
          <dt>有&nbsp;效&nbsp;期：</dt>
          <dd>即日起 到 <?php echo date('Y-m-d H:i:s', $output['goods']['virtual_indate']);?></dd>
        </dl>
        <?php }else if ($output['goods']['is_presell'] == 1) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>预&#12288;&#12288;售：</dt>
          <dd><ul><li class="sp-txt"><a href="javascript:void(0)" class="hovered"><?php echo date('Y-m-d', $output['goods']['presell_deliverdate']);?>&nbsp;日发货<i></i></a></li></ul></dd>
        </dl>
        <?php }?>
        <?php if ($output['goods']['is_fcode']) {?>
        <!-- 预售商品发货时间 -->
        <dl>
          <dt>购买类型：</dt>
          <dd><ul><li class="sp-txt"><a href="javascript:void(0)" class="hovered">F码优先购买<i></i></a></li></ul></dd>
        </dl>
        <?php }?>
        <!-- S 购买数量及库存 -->
        <?php if ($output['goods']['goods_state'] != 0 && $output['goods']['goods_storage'] >= 0) {?>
        <dl>
          <dt>最小起订量<?php echo $lang['nc_colon'];?></dt>
          <dd><?php echo $output['goods']['goods_common_moq']; ?> </dd>
          <!-- <dd class="ncs-figure-input">
            
            <input type="text" name="" id="quantity" value="1" size="3" maxlength="6" class="text w30" <?php if ($output['goods']['is_fcode'] == 1) {?>readonly<?php }?>>
            <?php if ($output['goods']['is_fcode'] == 1) {?>
            <span style="margin-left: 5px;">（每个F码优先购买一件商品）</span>(<?php echo $lang['goods_index_stock'];?><em nctype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></em><?php echo $lang['nc_jian'];?>)
            <?php } else {?>
            <a href="javascript:void(0)" class="increase">+</a><a href="javascript:void(0)" class="decrease">-</a> <span>(<?php echo $lang['goods_index_stock'];?><em nctype="goods_stock"><?php echo $output['goods']['goods_storage']; ?></em><?php echo $lang['nc_jian'];?>
            <!-- 虚拟商品限购数 -->
            <?php if ($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_limit'] > 0) { ?>，每人次限购<strong>
              <!-- 虚拟抢购 设置了虚拟抢购限购数 该数小于原商品限购数 -->
              <?php echo ($output['goods']['promotion_type'] == 'groupbuy' && $output['goods']['upper_limit'] > 0 && $output['goods']['upper_limit'] < $output['goods']['virtual_limit']) ? $output['goods']['upper_limit'] : $output['goods']['virtual_limit'];?>
              </strong>件<?php } ?>
            )</span><?php } ?>
          </dd> -->
        </dl>
        <?php }?>
        <!-- E 购买数量及库存 -->
      </div>
      <!-- S 购买按钮 -->
        <div class="ncs-btn"><!-- S 提示已选规格及库存不足无法购买 -->
          <!--  <div nctype="goods_prompt" class="ncs-point">
            <?php if (!empty($output['goods']['goods_spec'])) {?>
            <span class="yes"><?php echo $lang['goods_index_you_choose'];?> <strong><?php echo implode($lang['nc_comma'], $output['goods']['goods_spec']);?></strong></span>
            <?php }?>
            <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>
            <span class="no"><i class="icon-exclamation-sign"></i>&nbsp;<?php echo $lang['goods_index_understock_prompt'];?></span>
            <?php }?>
          </div> -->
          <!-- E 提示已选规格及库存不足无法购买 -->
          <!-- S到货通知 -->
          <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>
          <a href="javascript:void(0);" nctype="arrival_notice" class="arrival" title="到货通知">（<i class="icon-bullhorn"></i>到货通知）</a>
          <?php }?>
          <!-- E到货通知 -->
          <div class="clear"></div>
          
          <!-- 预约 -->
          <?php if (($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) && $output['goods']['is_appoint'] == 1) {?>
          <div>销售时间：<?php echo date('Y-m-d H:i:s', $output['goods']['appoint_satedate']);?></div>
          <a href="javascript:void(0);" nctype="appoint_submit" class="addcart" title="立即预约">立即预约</a>
          <?php }?>
          <?php if ($output['goods']['cart'] == true) {?>
          <!-- 加入购物车-->
              <?php if($_SESSION['is_login']!=1||empty($_SESSION['member_id'])){ ?>
                  <a href="href="index.php?act=login" class="addcart"
                  title="<?php echo $lang['goods_index_add_to_cart'];?>"><i class="icon-shopping-cart"></i>加入进货单</a>
                  <?php } else { ?>
          <a href="javascript:void(0);" <?php if($output['goods']['show_price']){?>id="addcart_submit"<?php } ?> class="addcart
           <?php if ($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) {?>no-addcart<?php }?>"
             title="<?php echo $lang['goods_index_add_to_cart'];?>"><i class="icon-shopping-cart"></i>加入进货单</a>
          <?php } ?>
           <p class="add-to-cart-text" >注：点击加入进货单后，您可在进货单中统一选择规格及确定采购数量。</p>
            <?php } ?>

          <!-- S 加入购物车弹出提示框 -->
          <div class="ncs-cart-popup">
            <dl>
              <dt>添加成功到进货单<a title="<?php echo $lang['goods_index_close'];?>" onClick="$('.ncs-cart-popup').css({'display':'none'});">X</a></dt>
              <dd>进货单共有<strong id="bold_num"></strong> <?php echo $lang['goods_index_number_of_goods'];?> <?php echo $lang['goods_index_total_price'];?><?php echo $lang['nc_colon'];?><em id="bold_mly" class="saleP"></em></dd>
              <dd class="btns"><a href="javascript:void(0);" class="ncs-btn-mini ncs-btn-green" onClick="location.href='<?php echo SHOP_SITE_URL.DS?>index.php?act=cart'">查看进货单</a> <a href="javascript:void(0);" class="ncs-btn-mini" value="" onClick="$('.ncs-cart-popup').css({'display':'none'});">继续进货</a></dd>
            </dl>
          </div>
          <!-- E 加入购物车弹出提示框 -->

        </div>
        <!-- E 购买按钮 -->
      <?php }else{?>
      <div class="ncs-saleout">
        <dl>
          <dt><i class="icon-info-sign"></i><?php echo $lang['goods_index_is_no_show'];?></dt>
          <dd><?php echo $lang['goods_index_is_no_show_message_one'];?></dd>
          <dd><?php echo $lang['goods_index_is_no_show_message_two_1'];?>&nbsp;<a href="<?php echo urlShop('show_store', 'index', array('store_id'=>$output['goods']['store_id']), $output['store_info']['store_domain']);?>" class="ncs-btn-mini"><?php echo $lang['goods_index_is_no_show_message_two_2'];?></a>&nbsp;<?php echo $lang['goods_index_is_no_show_message_two_3'];?> </dd>
        </dl>
      </div>
      <?php }?>
      <!--E 商品信息 -->

    </div>
    <!-- E 商品图片及收藏分享 -->
    <div class="ncs-handle">
      <!-- S 分享 -->
     <!-- <a href="javascript:void(0);" class="share" nc_type="sharegoods" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><i></i><?php echo $lang['goods_index_snsshare_goods'];?><span>(<em nc_type="sharecount_<?php echo $output['goods']['goods_id'];?>"><?php echo intval($output['goods']['sharenum'])>0?intval($output['goods']['sharenum']):0;?>)</em></span></a>-->
      <!-- S 收藏 -->
      <a href="javascript:collect_goods('<?php echo $output['goods']['goods_id']; ?>','count','goods_collect');" class="favorite"><i></i><?php echo $lang['goods_index_favorite_goods'];?><span>(<em nctype="goods_collect"><?php echo $output['goods']['goods_collect']?></em>)</span></a>
      <!-- S 对比 -->
      <a href="javascript:void(0);" class="compare" nc_type="compare_<?php echo $output['goods']['goods_id'];?>" data-param='{"gid":"<?php echo $output['goods']['goods_id'];?>"}'><i></i>加入对比</a><!-- S 举报 -->
      <?php if($output['inform_switch']) { ?>
      <a href="<?php if ($_SESSION['is_login']) {?>index.php?act=member_inform&op=inform_submit&goods_id=<?php echo $output['goods']['goods_id'];?><?php } else {?>javascript:login_dialog();<?php }?>" title="<?php echo $lang['goods_index_goods_inform'];?>" class="inform"><i></i><?php echo $lang['goods_index_goods_inform'];?></a>
      <?php } ?>
      <!-- End -->
   </div> 
   <div style="position:absolute;left:818px;top:428px;" class="action-share bdsharebuttonbox bdshare-button-style0-16">
       <span>分享到：</span>
       <a class="bds_weixin" data-cmd="weixin" title="分享到微信"></a>
       <a class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a>
       <a class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a>
       <a class="bds_sqq" data-cmd="sqq" title="分享给QQ好友"></a>
   </div> 

    <!--S 店铺信息-->
     <div style=" position: absolute; z-index: 1; top: -1px; right: -1px;">
      <?php include template('store/info');?>
    </div>
    <!--E 店铺信息 -->
    <div class="clear"></div>
  </div>

    <!-- 样品-->
    <style>
        .sample-goods-warp{
            margin-top: 20px;
            margin-bottom: 20px;
            border: 1px solid #EEE;
            height: 102px;
            padding: 15px 0px 15px 80px;
        }
        .sample-goods-warp>div{float:left;height: 100px;}
        .sample-icon{width: 100px;height: 70px;border-radius: 50px;background-color: rgb(22,135,199);text-align: center;color: #ffffff;font-size: 14px;vertical-align: middle;padding-top: 30px;}
        .sample-rule{width: 390px;margin-left: 40px;}
        .sample-info{width: 270px;margin-left: 70px;}
        .sample-info p{margin-top: 10px;}
        .sample-buy{margin-left:40px;text-align: center;width: 200px;}
        .sample-buy-btn{padding: 10px 0px;background-color: #dddddd;cursor: pointer;color: #000000;margin: 30px auto;width: 120px;}

        .sample-reduce-btn{color: #ffffff;background: #D7D7D7 none repeat scroll 0% 0%;border: 1px solid #D7D7D7;cursor: pointer;width: 26px;}
        .sample-add-btn{color: #ffffff;background: #333 none repeat scroll 0% 0%;border: 1px solid #333;cursor: pointer;width: 26px;}
        .sample-goods-num{height:19px !important;line-height: 19px  !important;width: 30px;padding: 0px 3px !important;font-size: 12px;text-align: center;}
    </style>
    <?php if($output['sample_goods_info']['enable']==1){?>
    <div class="sample-goods-warp">
                <div>
                    <div class="sample-icon">支持<br>拿样</div>
                </div>
                <div class="sample-rule">
                    <?php echo $output['sample_goods_info']['goods_rules'];?>
                </div>

                <div class="sample-info">

                    <div class="ncs-plus">
                        <dl>
                            <dt style="width: auto">拿样价：</dt>
                            <dd style="width: auto"><span style="color:#F685B5 ">¥<?php echo $output['sample_goods_info']['goods_price'];?></span></dd>
                        </dl>
                        <dl>
                            <dt style="width: auto">拿样数量：</dt>
                            <dd style="width: auto;vertical-align: top">
                                <button class="sample-reduce-btn">-</button>
                                <input class="sample-goods-num" value="1" type="text" min="1" max="<?php echo $output['sample_goods_info']['num_limit'];?>">
                                <button class="sample-add-btn">+</button>
                                <span style="margin-left: 10px;color:#BBBBBB">最大数量为<?php echo $output['sample_goods_info']['num_limit'];?>件</span>
                            </dd>
                        </dl>
                        <dl class="ncs-freight">
                            <dt style="width: auto">
                                <?php if ($output['sample_goods_info']['transport_id'] != '0'){?>
                                    <?php echo $lang['goods_index_trans_to'];?><a href="javascript:void(0)" id="sample_ncrecive"><?php echo $lang['goods_index_trans_country'];?></a><?php echo $lang['nc_colon'];?>
                                    <div class="ncs-freight-box" id="sample_transport_pannel">
                                        <?php if (is_array($output['area_list'])){?>
                                            <?php foreach($output['area_list'] as $k=>$v){?>
                                                <a href="javascript:void(0)" nctype="<?php echo $k;?>"><?php echo $v;?></a>
                                            <?php }?>
                                        <?php }?>
                                    </div>
                                <?php }else{?>
                                    <?php echo $lang['goods_index_trans_zcountry'];?><?php echo $lang['nc_colon'];?>
                                <?php }?>
                            </dt>
                            <dd style="width: auto" id="sample_transport_price">
                                    <?php if ($output['sample_goods_info']['goods_freight'] == 0){?>
                                        <span id="sample_nc_kd"><?php echo $lang['goods_index_trans_for_seller'];?></span>
                                    <?php }else{?>
                                        <!-- 如果买家承担运费 -->
                                        <span id="sample_nc_kd">运费<?php echo $lang['nc_colon'];?><em><?php echo $output['sample_goods_info']['goods_freight'];?></em><?php echo $lang['goods_index_yuan'];?></span>
                                    <?php }?>
                            </dd>
                            <dd style="color:red;display:none" id="sample_loading_price">loading.....</dd>
                        </dl>
                    </div>
                </div>

                <div class="sample-buy" onclick="sample_buy()">
                    <form method="post" action="index.php?act=buy&op=buy_step1" id="sample_form">
                        <input type="hidden" name="ifcart" value="0">
                        <input type="hidden" name="sample_goods" value="1">
                        <input id="sample_goods_num" type="hidden" name="cart_id[]" value="<?php echo $_GET['goods_id'];?>|<?php echo $output['sample_goods_info']['num_limit'];?>">
                    </form>
                    <div class="sample-buy-btn">立即拿样</div>
                </div>
    </div>
    <?php }?>
    <script>
        function sample_buy(){
            var n = $('#sample_goods_num');
            n.val( n.val().substr(0,n.val().indexOf('|'))+'|'+$('.sample-goods-num').val());
            $('#sample_form').submit();
        }
        $(function(){
            $('.sample-reduce-btn').click(function(){
                var n = Number($('.sample-goods-num').val());
                if(isNaN(n)){
                    n=1;
                }
                n--;
                var min = $('.sample-goods-num').attr('min');
                if(n<min){
                    n=min;
                }
                $('.sample-goods-num').val(n);
            });
            $('.sample-add-btn').click(function(){
                var n = Number($('.sample-goods-num').val());
                if(isNaN(n)){
                    n=1;
                }
                n++;
                var max = $('.sample-goods-num').attr('max');
                if(n>max){
                    n=max;
                }
                $('.sample-goods-num').val(n);
            });
            $('.sample-goods-num').change(function(){
                var min= $(this).attr('min');
                var max= $(this).attr('max');
                var val = Number($(this).val());
                if(isNaN(val)){
                    val=min;
                }else if(val<min){
                    val=min;
                }else if(val>max){
                    val = max;
                }
                $(this).val(val);
            });
        });
    </script>
    <!-- 样品-->

  <div class="ncs-goods-layout expanded" >
    <div class="ncs-goods-main" id="main-nav-holder">
      <!-- S 优惠套装 -->
      <div class="ncs-promotion" id="nc-bundling" style="display:none;"></div>
      <!-- E 优惠套装 -->
      <div class="tabbar pngFix" id="main-nav">
        <div class="ncs-goods-title-nav">
          <ul id="categorymenu">
            <li class="current"><a id="tabGoodsIntro" href="#content"><?php echo $lang['goods_index_goods_info'];?></a></li>
            <li><a id="tabGoodsRate" href="#content"><?php echo $lang['goods_index_evaluation'];?><em>(<?php echo $output['goods_evaluate_info']['all'];?>)</em></a></li>
            <li><a id="tabGoodsTraded" href="#content"><?php echo $lang['goods_index_sold_record'];?>
             <em>(<?php echo $output['goods']['goods_salenum']; ?>)</em>
             </a></li>
            <li><a id="tabGuestbook" href="#content"><?php echo $lang['goods_index_goods_consult'];?></a></li>
          </ul>
          <div class="switch-bar"><a href="javascript:void(0)" id="fold">&nbsp;</a></div>
        </div>
      </div>
      <div class="ncs-intro">
        <div class="content bd" id="ncGoodsIntro">

          <!--S 满就送 -->
          <?php if($output['mansong_info']) { ?>
          <div class="nc-mansong">
            <div class="nc-mansong-ico"></div>
            <dl class="nc-mansong-content">
              <dt><?php echo $output['mansong_info']['mansong_name'];?>
                <time>( <?php echo $lang['nc_promotion_time'];?><?php echo $lang['nc_colon'];?><?php echo date('Y-m-d',$output['mansong_info']['start_time']).'--'.date('Y-m-d',$output['mansong_info']['end_time']);?> )</time>
              </dt>
              <dd>
                <?php foreach($output['mansong_info']['rules'] as $rule) { ?>
                <span><?php echo $lang['nc_man'];?><em><?php echo ncPriceFormat($rule['price']);?></em><?php echo $lang['nc_yuan'];?>
                <?php if(!empty($rule['discount'])) { ?>
                ， <?php echo $lang['nc_reduce'];?><i><?php echo ncPriceFormat($rule['discount']);?></i><?php echo $lang['nc_yuan'];?>
                <?php } ?>
                <?php if(!empty($rule['goods_id'])) { ?>
                ， <?php echo $lang['nc_gift'];?> <a href="<?php echo $rule['goods_url'];?>" title="<?php echo $rule['mansong_goods_name'];?>" target="_blank"> <img src="<?php echo cthumb($rule['goods_image'], 60);?>" alt="<?php echo $rule['mansong_goods_name'];?>"> </a>&nbsp;。
                <?php } ?>
                </span>
                <?php } ?>
              </dd>
              <dd class="nc-mansong-remark"><?php echo $output['mansong_info']['remark'];?></dd>
            </dl>
          </div>
          <?php } ?>
          <!--E 满就送 -->
          <?php if(is_array($output['goods']['goods_attr']) || isset($output['goods']['brand_name'])){?>
          <ul class="nc-goods-sort">
            <li>商家货号：<?php echo $output['goods']['goods_serial'];?></li>
            <?php if(isset($output['goods']['brand_name'])){echo '<li>'.$lang['goods_index_brand'].$lang['nc_colon'].$output['goods']['brand_name'].'</li>';}?>
            <?php if(is_array($output['goods']['goods_attr']) && !empty($output['goods']['goods_attr'])){?>
            <?php foreach ($output['goods']['goods_attr'] as $val){ $val= array_values($val);echo '<li>'.$val[0].$lang['nc_colon'].$val[1].'</li>'; }?>
            <?php }?>
          </ul>
          <?php }?>
          <div class="ncs-goods-info-content">
            <?php if (isset($output['plate_top'])) {?>
            <div class="top-template"><?php echo $output['plate_top']['plate_content']?></div>
            <?php }?>
            <div class="default"><?php echo $output['goods']['goods_body']; ?></div>
            <?php if (isset($output['plate_bottom'])) {?>
            <div class="bottom-template"><?php echo $output['plate_bottom']['plate_content']?></div>
            <?php }?>
          </div>
        </div>
      </div>
      <div class="ncs-comment">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGoodsRate">
          <div class="top">
            <div class="rate">
              <p><strong><?php echo $output['goods_evaluate_info']['good_percent'];?></strong><sub>%</sub>好评</p>
              <span>共有<?php echo $output['goods_evaluate_info']['all'];?>人参与评分</span></div>
            <div class="percent">
              <dl>
                <dt>好评<em>(<?php echo $output['goods_evaluate_info']['good_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['good_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt>中评<em>(<?php echo $output['goods_evaluate_info']['normal_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['normal_percent'];?>%"></i></dd>
              </dl>
              <dl>
                <dt>差评<em>(<?php echo $output['goods_evaluate_info']['bad_percent'];?>%)</em></dt>
                <dd><i style="width: <?php echo $output['goods_evaluate_info']['bad_percent'];?>%"></i></dd>
              </dl>
            </div>
            <div class="btns"><span>您可对已购商品进行评价</span>
              <p><a href="<?php if ($output['goods']['is_virtual']) { echo urlShop('member_vr_order', 'index');} else { echo urlShop('member_order', 'index');}?>" class="ncs-btn ncs-btn-red" target="_blank"><i class="icon-comment-alt"></i>评价商品</a></p>
            </div>
          </div>
          <div class="ncs-goods-title-nav">
            <ul id="comment_tab">
              <li data-type="all" class="current"><a href="javascript:void(0);"><?php echo $lang['goods_index_evaluation'];?>(<?php echo $output['goods_evaluate_info']['all'];?>)</a></li>
              <li data-type="1"><a href="javascript:void(0);">好评(<?php echo $output['goods_evaluate_info']['good'];?>)</a></li>
              <li data-type="2"><a href="javascript:void(0);">中评(<?php echo $output['goods_evaluate_info']['normal'];?>)</a></li>
              <li data-type="3"><a href="javascript:void(0);">差评(<?php echo $output['goods_evaluate_info']['bad'];?>)</a></li>
            </ul>
          </div>
          <!-- 商品评价内容部分 -->
          <div id="goodseval" class="ncs-commend-main"></div>
        </div>
      </div>
      <div class="ncg-salelog">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_sold_record'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGoodsTraded">
          <div class="top">
            <div class="price">
                <?php if($output['goods']['show_price']){?>
                    <?php echo $lang['goods_index_goods_price'];?>
                    <strong><?php echo $output['goods']['goods_price'];?></strong>
                    <?php echo $lang['goods_index_yuan'];?><span><?php echo $lang['goods_index_price_note'];?></span>
                <?php }else{?>
                    <?php echo $output['goods']['show_price_tip'];?>
                <?php }?>
            </div>
          </div>
          <!-- 成交记录内容部分 -->
          <div id="salelog_demo" class="ncs-loading"> </div>
        </div>
      </div>
      <div class="ncs-consult">
        <div class="ncs-goods-title-bar hd">
          <h4><a href="javascript:void(0);"><?php echo $lang['goods_index_goods_consult'];?></a></h4>
        </div>
        <div class="ncs-goods-info-content bd" id="ncGuestbook">
          <!-- 咨询留言内容部分 -->
          <div id="consulting_demo" class="ncs-loading"> </div>
        </div>
      </div>
      <?php if(!empty($output['goods_commend']) && is_array($output['goods_commend']) && count($output['goods_commend'])>1){?>
      <div class="ncs-recommend">
        <div class="title">
          <h4><?php echo $lang['goods_index_goods_commend'];?></h4>
        </div>
        <div class="content">
          <ul>
            <?php foreach($output['goods_commend'] as $goods_commend){?>
            <?php if($output['goods']['goods_id'] != $goods_commend['goods_id']){?>
            <li>
              <dl>
                <dt class="goods-name"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><?php echo $goods_commend['goods_name'];?><em><?php echo $goods_commend['goods_jingle'];?></em></a></dt>
                <dd class="goods-pic"><a href="<?php echo urlShop('goods', 'index', array('goods_id' => $goods_commend['goods_id']));?>" target="_blank" title="<?php echo $goods_commend['goods_jingle'];?>"><img src="<?php echo thumb($goods_commend, 240);?>" alt="<?php echo $goods_commend['goods_name'];?>"/></a></dd>
                <?php if($output['goods']['show_price']){?><dd class="goods-price"><?php echo $lang['currency'];?><?php echo $goods_commend['goods_price'];?></dd><?php }?>
              </dl>
            </li>
            <?php }?>
            <?php }?>
          </ul>
          <div class="clear"></div>
        </div>
      </div>
      <?php }?>
    </div>
    <div class="ncs-sidebar">
      <div class="ncs-sidebar-container">
        <div class="title">
          <h4>商品二维码</h4>
        </div>
        <div class="content">
          <div class="ncs-goods-code">
            <p><img src="<?php echo goodsQRCode($output['goods']);?>"  title="商品原始地址：<?php echo urlShop('goods', 'index', array('goods_id'=>$output['goods']['goods_id']));?>"></p>
            <span class="ncs-goods-code-note"><i></i>扫描二维码，手机查看分享</span> </div>
        </div>
      </div>
      <?php include template('store/callcenter');?>
      <?php include template('store/left');?>
    </div>
  </div>
</div>
<div style="display:none;" class="body-bg"></div>
<div style="display:none;" class="spec-goods-dialog">
   <div class="dialog-top">请选择商品规格及数量<span class="moq-text">该商品的混合起订量为<?php echo $output['goods']['goods_common_moq']; ?>包</span><i onclick="closeAddCartDialog()" class="icon-remove icon-2x dialog-close"></i></div>
   <div>
     <table>
       <thead>
         <tr><td style="width:30px;"><input type="checkbox" to="all" checked="checked"></td><td style="text-align:left;">规格</td><td style="width:80px;">价格</td><td style="width:80px;">库存量</td><td style="width:140px;">购买数量</td><td style="width:80px;">小计(元)</td></tr>
       </thead>
       <tbody>
          <?php $goods_count=0;$all_price=0;?>
          <?php foreach ($output['spec_list'] as $val){$goods_count++;$all_price+=$val["price"]*$val["moq"];?>
             <tr class="selected" data_goods_id='<?php echo $val["id"];?>'  data_goods_price='<?php echo $val["price"];?>'>
                 <td><input type="checkbox" checked="checked"></td>
                 <td class="g-name" style="text-align:left;"><?php echo $val["name"];?></td>
                 <td><?php echo $val["price"];?></td>
                 <td><?php echo $val["storage"];?></td>
                 <td><button type="button" min_value="<?php echo $val["moq"];?>" class="b-r-p reduce">-</button><input type="text" class="input_goods_moq"   value="<?php echo $val["moq"];?>"/><button type="button" class="b-r-p plus">+</button></td>
                 <td><?php echo number_format($val["price"]*$val["moq"],2);?></td>
             </tr>
          <?php }?>
       </tbody>
     </table>
   </div>
   <div class="dialog-footer">
     <span>该商品共计购买<span class="count-num"><?php echo $goods_count;?></span>种，合计<span class="count-price"><?php echo number_format($all_price,2);?></span>元</span></span>
     <a id="btn_add_to_cart" href="javascript:void(0)">加入进货单</a>
   </div>
   <div class="dialog-error"></div>
</div>
<form id="buynow_form" method="post" action="<?php echo SHOP_SITE_URL;?>/index.php">
  <input id="act" name="act" type="hidden" value="buy" />
  <input id="op" name="op" type="hidden" value="buy_step1" />
  <input id="cart_id" name="cart_id[]" type="hidden"/>
</form>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js" type="text/javascript"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.F_slider.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.raty/jquery.raty.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
/** 辅助浏览 **/
jQuery(function($){
	//产品图片
	$.getScript('<?php echo SHOP_RESOURCE_SITE_URL?>/js/ImageZoom.js', function(){
		var
		zoomController,
		zoomControllerUl,
		zoomControllerUlLeft = 0,
		shell = $('#ncs-goods-picture'),
		shellPanel = shell.parent(),
		heightNcsDetail = $('div[class="ncs-detail"]').height();
		heightOffset = 60,
		minGallerySize = [360, 360],
		imageZoom = new ImageZoom({
			shell: shell,
			basePath: '',
			levelASize: [60, 60],
			levelBSize: [320, 320],
			gallerySize: minGallerySize,
			onBeforeZoom: function(index, level){
				if(!zoomController){
					zoomController = shell.find('div.controller');
				}

				var
				self = this,
				duration = 320,
				width = minGallerySize[0],
				height = minGallerySize[1],
				zoomFx = function(){
					self.ops.gallerySize = [width, height];
					self.galleryPanel.stop().animate({width:width, height:height}, duration);
					shellPanel.stop().animate({height:height + heightOffset}, duration).css('overflow', 'visible');
					zoomController.animate({width:width-22}, duration);
					shell.stop().animate({width:width}, duration);
				};
				if(level !== this.level && this.level !== 0){
					if(this.level === 1 && level > 1){
						height = Math.max(480, shellPanel.height());
						width = shellPanel.width();
						zoomFx();
					}
					else if(level === 1){
						zoomFx();
						shellPanel.stop().animate({height:heightNcsDetail}, duration);
					}
				}
			},
			onZoom: function(index, level, prevIndex){
				shell.find('a.prev,a.next')[level<3 ? 'removeClass' : 'addClass']('hide');
				shell.find('a.close').css('display', [level>1 ? 'block' : 'none']);
			},
			items: [
	                <?php if (!empty($output['goods_image'])) {?>
	                <?php echo implode(',', $output['goods_image']);?>
	                <?php }?>
					]
		});
		shell.data('imageZoom', imageZoom);
	});

});
function showAddCartDialog(){
	$(".body-bg").show();
	var dialog=$(".spec-goods-dialog");
	dialog.show();
	var d_h=dialog.height(),w_h=$(window).height(),m_w_h=w_h*0.8;
	if(d_h>=m_w_h){ 
		var box=dialog.find("table").parent();
		
		box.height(m_w_h-90);
		box.css("overflow-y","scroll");
		dialog.css("top",((w_h-m_w_h)/2)+"px");
		dialog.width(710);
    }else{
    	dialog.css("top",((w_h-d_h)/2)+"px");
    }
}
function closeAddCartDialog(){
	$(".body-bg").hide();
	$(".spec-goods-dialog").hide();
}
function addCartDialogCountPrice(){
	var goods_count=0,all_price=0;
	$(".spec-goods-dialog tbody tr").each(function(){
		var tr=$(this);
		var price=parseFloat(tr.attr("data_goods_price")),num=parseInt(tr.find(".input_goods_moq").val()),
		row_price=price*num;
		tr.find("td:last").text(row_price.toFixed(2));
		if(tr.is(".selected")){goods_count++;all_price+=row_price;}
    });
    $(".spec-goods-dialog .dialog-footer .count-num").text(goods_count);
    $(".spec-goods-dialog .dialog-footer .count-price").text(all_price.toFixed(2));
}
/* 加入购物车 */
function list_addcart(goods_id,quantity,commonid) {
    var url = 'index.php?act=cart&op=add';
    $.getJSON(url, {'goods_id':goods_id, 'quantity':quantity,"commonid":commonid}, function(data) {
        if (data != null) {
            if (data.state) {
            	closeAddCartDialog();
                //animatenTop(current_add_cart_obj.offset().top, current_add_cart_obj.offset().left);
                // 头部加载购物车信息
                load_cart_information();
				$('#rtoolbar_cartlist').load('index.php?act=cart&op=ajax_load&type=html');
				if(data.text){
    				 alert(data.text);
    		    }
				$('#bold_num').html(data.num);
			    $('#bold_mly').html(price_format(data.amount));
			    $('.ncs-cart-popup').fadeIn('fast');
            } else {
                $(".spec-goods-dialog .dialog-error").text(data.msg);
                setTimeout(function(){
             	   $(".spec-goods-dialog .dialog-error").html("");
                },5000);
            }
        }
    });
}
    //收藏分享处下拉操作
//     jQuery.divselect = function(divselectid,inputselectid) {
//       var inputselect = $(inputselectid);
//       $(divselectid).mouseover(function(){
//           var ul = $(divselectid+" ul");
//           ul.slideDown("fast");
//           if(ul.css("display")=="none"){
//               ul.slideDown("fast");
//           }
//       });
//       $(divselectid).live('mouseleave',function(){
//           $(divselectid+" ul").hide();
//       });
//     };
   
$(function(){
	
	//赠品处滚条
	$('#ncsGoodsGift').perfectScrollbar();
    <?php if ($output['goods']['goods_state'] == 1 && $output['goods']['goods_storage'] > 0 ) {?>
    //选择弹出框
    $(".spec-goods-dialog table").bind('click',function(e){
        e.stopPropagation();
        var target=$(e.target),table=$(this);
        if(target.is("input[type='checkbox']")){
            if(target.attr("to")=="all"){
                if(target.attr("checked")){
            	  table.find("tbody input[type='checkbox']").attr("checked",true);
            	  table.find("tbody tr").addClass("selected");
                }else{
              	  table.find("tbody input[type='checkbox']").attr("checked",false);
            	  table.find("tbody tr").removeClass("selected");
                }
            }else{
            	if(target.attr("checked")){
                  target.closest("tr").addClass("selected");
            	}else{
                  target.closest("tr").removeClass("selected");
                }
            }
            addCartDialogCountPrice();
        }else if(target.is(".b-r-p")){
    	   if(target.is(".reduce")){
    		   var num=parseInt(target.next().val()),min_num=parseInt(target.attr("min_value"));
    		   if(num<=min_num){
        		   return;
        	   }
    		   target.next().val(--num);
    		   addCartDialogCountPrice();
    	   }else{
    		   target.prev().val(parseInt(target.prev().val())+1);
    		   addCartDialogCountPrice();
    	   }
        }
    });
    $('.spec-goods-dialog tbody tr .input_goods_moq').blur(function () {
        var n = parseInt($(this).val());
        if(isNaN(n)){
            $(this).val(1);
        }else {
            $(this).val(n);
        }
    });

    $("#btn_add_to_cart").bind('click',function(){
        var goods_ids=[],quantitys=[],all_quantitys=0,check_quantitys=<?php echo $output['goods']['goods_common_moq']; ?>;
    	$(".spec-goods-dialog tbody tr.selected").each(function(){
        	var tr=$(this);
    		goods_ids.push(tr.attr("data_goods_id"));
    		var n_q=parseInt(tr.find(".input_goods_moq").val());
    		quantitys.push(n_q);
    		all_quantitys+=n_q;
        });
        if(goods_ids.length==0){
           $(".spec-goods-dialog .dialog-error").text("您未选择购买的规格");
           setTimeout(function(){
        	   $(".spec-goods-dialog .dialog-error").html("");
           },5000);
           return;
        }
        if(all_quantitys<check_quantitys){
            $(".spec-goods-dialog .dialog-error").text("您选中规格的起订量总和未达到此商品"+check_quantitys+"的混批量");
            setTimeout(function(){
         	   $(".spec-goods-dialog .dialog-error").html("");
            },5000);
            return; 
        }
        list_addcart(goods_ids.join(','),quantitys.join(','),<?php echo $output['goods']['goods_commonid'];?>);
        //addcart(goods_ids.join(','),quantitys.join(','),'addcart_callback');
    });
    // 加入购物车
    $('#addcart_submit').click(function(){
    	showAddCartDialog();
    });
        <?php if (!($output['goods']['is_virtual'] == 1 && $output['goods']['virtual_indate'] < TIMESTAMP)) {?>
        // 立即购买
        $('a[nctype="buynow_submit"]').click(function(){
            buynow(<?php echo $output['goods']['goods_id']?>,checkQuantity());
        });
        <?php }?>
    <?php }?>
    // 到货通知
    <?php if ($output['goods']['goods_storage'] == 0 || $output['goods']['goods_state'] == 0) {?>
    $('a[nctype="arrival_notice"]').click(function(){
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        ajax_form('arrival_notice', '到货通知','<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id']));?>', 350);
        <?php }?>
    });
    <?php }?>
    <?php if (($output['goods']['goods_state'] == 0 || $output['goods']['goods_storage'] <= 0) && $output['goods']['is_appoint'] == 1) {?>
    $('a[nctype="appoint_submit"]').click(function(){
        <?php if ($_SESSION['is_login'] !== '1'){?>
        login_dialog();
        <?php }else{?>
        ajax_form('arrival_notice', '立即预约', '<?php echo urlShop('goods', 'arrival_notice', array('goods_id' => $output['goods']['goods_id'], 'type' => 2));?>', 350);
        <?php }?>
    });
    <?php }?>
    //浮动导航  waypoints.js
    $('#main-nav').waypoint(function(event, direction) {
        $(this).parent().parent().parent().toggleClass('sticky', direction === "down");
        event.stopPropagation();
    });
    // 规格选择
    $('dl[nctype="nc-spec"]').find('a').each(function(){
        $(this).click(function(){
            if ($(this).hasClass('hovered')) {
                return false;
            }
            $(this).parents('ul:first').find('a').removeClass('hovered');
            $(this).addClass('hovered');
            checkSpec();
        });
    });
    <?php if(!empty($output["pindan_info"])){?>
	    setInterval(function(){
	        var _this=$("#pindan_time");
	        var time_down=parseInt(_this.attr("time_down"));
	        if(time_down==0) return;
	        time_down--;
	        _this.attr("time_down",time_down);
	        var hour=parseInt(time_down/3600);
	        hour=hour>99?99:hour;
	        var minutes=parseInt((time_down%3600)/60),second=(time_down%3600)%60;
	        var time_str=hour>9?"<span>"+parseInt(hour/10)+"</span>"+"<span>"+(hour%10)+"</span>":"<span>0</span><span>"+hour+"</span>";
	        time_str+=":";
	        time_str+=minutes>9?"<span>"+parseInt(minutes/10)+"</span>"+"<span>"+(minutes%10)+"</span>":"<span>0</span><span>"+minutes+"</span>";
	        time_str+=":";
	        time_str+=second>9?"<span>"+parseInt(second/10)+"</span>"+"<span>"+(second%10)+"</span>":"<span>0</span><span>"+second+"</span>";
	        _this.html("距离本轮拼单结束&nbsp;&nbsp;"+time_str);
	    },1000);
    <?php } ?>
});

function checkSpec() {
    var spec_param = <?php echo json_encode($output['spec_list']);?>;
    var spec = new Array();
    $('ul[nctyle="ul_sign"]').find('.hovered').each(function(){
        var data_str = ''; eval('data_str =' + $(this).attr('data-param'));
        spec.push(data_str.valid);
    });
    spec1 = spec.sort(function(a,b){
        return a-b;
    });
    var spec_sign = spec1.join('|');
    $.each(spec_param, function(i, n){
        if (n.sign == spec_sign) {
            window.location.href = n.url;
        }
    });
}



// 立即购买js
function buynow(goods_id,quantity){
<?php if ($_SESSION['is_login'] !== '1'){?>
	login_dialog();
<?php }else{?>
    if (!quantity) {
        return;
    }
    <?php if ($_SESSION['store_id'] == $output['goods']['store_id']) { ?>
    alert('不能购买自己店铺的商品');return;
    <?php } ?>
    $("#cart_id").val(goods_id+'|'+quantity);
    $("#buynow_form").submit();
<?php }?>
}

$(function(){
    //样品地区运费
    $('#sample_transport_pannel>a').click(function(){
        var id = $(this).attr('nctype');
        if (id=='undefined') return false;
        var _self = this,tpl_id = '<?php echo $output['sample_goods_info']['transport_id'];?>';
        var url = 'index.php?act=goods&op=calc&rand='+Math.random();
        $('#sample_transport_price').css('display','none');
        $('#sample_loading_price').css('display','');
        $.getJSON(url, {'id':id,'tid':tpl_id}, function(data){
            if (data == null) return false;
            if(data != 'undefined') {$('#sample_nc_kd').html('运费<?php echo $lang['nc_colon'];?><em>' + data + '</em><?php echo $lang['goods_index_yuan'];?>');}else{'<?php echo $lang['goods_index_trans_for_seller'];?>';}
            $('#sample_transport_price').css('display','');
            $('#sample_loading_price').css('display','none');
            $('#sample_ncrecive').html($(_self).html());
        });
    });
    //选择地区查看运费
    $('#transport_pannel>a').click(function(){
    	var id = $(this).attr('nctype');
    	if (id=='undefined') return false;
    	var _self = this,tpl_id = '<?php echo $output['goods']['transport_id'];?>';
	    var url = 'index.php?act=goods&op=calc&rand='+Math.random();
	    $('#transport_price').css('display','none');
	    $('#loading_price').css('display','');
	    $.getJSON(url, {'id':id,'tid':tpl_id}, function(data){
	    	if (data == null) return false;
	        if(data != 'undefined') {$('#nc_kd').html('运费<?php echo $lang['nc_colon'];?><em>' + data + '</em><?php echo $lang['goods_index_yuan'];?>');}else{'<?php echo $lang['goods_index_trans_for_seller'];?>';}
	        $('#transport_price').css('display','');
	    	$('#loading_price').css('display','none');
	        $('#ncrecive').html($(_self).html());
	    });
    });
    $("#nc-bundling").load('index.php?act=goods&op=get_bundling&goods_id=<?php echo $output['goods']['goods_id'];?>', function(){
        if($(this).html() != '') {
            $(this).show();
        }
    });
    $("#salelog_demo").load('index.php?act=goods&op=salelog&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>&vr=<?php echo $output['goods']['is_virtual'];?>', function(){
        // Membership card
        $(this).find('[nctype="mcard"]').membershipCard({type:'shop'});
    });
	$("#consulting_demo").load('index.php?act=goods&op=consulting&goods_id=<?php echo $output['goods']['goods_id'];?>&store_id=<?php echo $output['goods']['store_id'];?>', function(){
		// Membership card
		$(this).find('[nctype="mcard"]').membershipCard({type:'shop'});
	});

/** goods.php **/
	// 商品内容部分折叠收起侧边栏控制
	$('#fold').click(function(){
  		$('.ncs-goods-layout').toggleClass('expanded');
	});
	// 商品内容介绍Tab样式切换控制
	$('#categorymenu').find("li").click(function(){
		$('#categorymenu').find("li").removeClass('current');
		$(this).addClass('current');
	});
	// 商品详情默认情况下显示全部
	$('#tabGoodsIntro').click(function(){
		$('.bd').css('display','');
		$('.hd').css('display','');
	});
	// 点击评价隐藏其他以及其标题栏
	$('#tabGoodsRate').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsRate').css('display','');
		$('.hd').css('display','none');
	});
	// 点击成交隐藏其他以及其标题
	$('#tabGoodsTraded').click(function(){
		$('.bd').css('display','none');
		$('#ncGoodsTraded').css('display','');
		$('.hd').css('display','none');
	});
	// 点击咨询隐藏其他以及其标题
	$('#tabGuestbook').click(function(){
		$('.bd').css('display','none');
		$('#ncGuestbook').css('display','');
		$('.hd').css('display','none');
	});
	//商品排行Tab切换
	$(".ncs-top-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-top-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});
	//信用评价动态评分打分人次Tab切换
	$(".ncs-rate-tab > li > a").mouseover(function(e) {
		if (e.target == this) {
			var tabs = $(this).parent().parent().children("li");
			var panels = $(this).parent().parent().parent().children(".ncs-rate-panel");
			var index = $.inArray(this, $(this).parent().parent().find("a"));
			if (panels.eq(index)[0]) {
				tabs.removeClass("current ").eq(index).addClass("current ");
				panels.addClass("hide").eq(index).removeClass("hide");
			}
		}
	});

//触及显示缩略图
	$('.goods-pic > .thumb').hover(
		function(){
			$(this).next().css('display','block');
		},
		function(){
			$(this).next().css('display','none');
		}
	);
    //评价列表
    $('#comment_tab').on('click', 'li', function() {
        $('#comment_tab li').removeClass('current');
        $(this).addClass('current');
        load_goodseval($(this).attr('data-type'));
    });
    load_goodseval('all');
    function load_goodseval(type) {
        var url = '<?php echo urlShop('goods', 'comments', array('goods_id' => $output['goods']['goods_id']));?>';
        url += '&type=' + type;
        $("#goodseval").load(url);
    }

    //记录浏览历史
	$.get("index.php?act=goods&op=addbrowse",{gid:<?php echo $output['goods']['goods_id'];?>});
	//初始化对比按钮
	initCompare();
});
/* 加入购物车后的效果函数 */

window._bd_share_config={
   common:{
     bdDesc:"",
     bdMini:"2",
     bdMiniList:false,
     bdPic:"<?php echo thumb($output['goods'],240); ?>",
     bdStyle:"0",
     bdText:"刚刚又在拼单网上采购了一批# <?php echo $output['goods']['goods_name']; ?> #够便宜，试试看好不好卖。"
   },
   share:[{
    bdCustomStyle:"<?php echo SHOP_TEMPLATES_URL;?>/css/share.css",
    bdSign:"On",
    bdStyle:"0",
    type:"share"
   }]
}
with(document)0[(getElementsByTagName('head')[0]||body).appendChild(createElement('script')).src='http://bdimg.share.baidu.com/static/api/js/share.js?cdnversion='+~(-new Date()/36e5)];
</script>
