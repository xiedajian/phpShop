<?php defined('InShopNC') or exit('Access Invalid!');?>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/home_index.js" charset="utf-8"></script>
<!--[if IE 6]>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ie6.js" charset="utf-8"></script>
<![endif]-->
<script type="text/javascript">
var uid = window.location.href.split("#V3");
var  fragment = uid[1];
if(fragment){
	if (fragment.indexOf("V3") == 0) {document.cookie='uid=0';}
else {document.cookie='uid='+uid[1];}
	}

</script>
<style type="text/css">
.category { display: block !important; }
.no-show-price{
    text-align: center;
    font-size: 12px;
    color: #dddddd;
}
</style>
<div class="clear"></div>

<!-- HomeFocusLayout Begin-->
<div class="home-focus-layout"> <?php echo $output['web_html']['index_pic'];?>
  <div class="right-sidebar">
    <div class="policy">
      <ul>
        <li class="b1">七天包退</li>
        <li class="b2">正品保障</li>
        <li class="b3">闪电发货</li>
      </ul>
    </div>
    <?php if($_SESSION['member_id']){ ?>
      <div class="index-right-login-box">
        <div class="logined">
           <dl>
             <dt><a href='index.php?act=member&op=home'><img src="<?php echo getMemberAvatar($output['member_info']['member_avatar']);?>" > </a></dt>
             <dd>
               Hi，您好</br><?php echo getMemberShowName();?>
                 <p style="color: #FF7600;cursor: pointer" onclick="javascript:void(location.href='index.php?act=login&op=logout')">安全退出</p>
             </dd>

           </dl>
           <ul>
             <li> <a href='index.php?act=predeposit&op=pd_log_list'><b><?php echo $output['member_info']['available_predeposit'];?></b><p>账户余额</p></a> </li>
             <li> <a href='index.php?act=member_message&op=message'><b><?php echo $output['message_num']>0?$output['message_num']:'0';?></b><p>站内消息</p></a> </li>
             <li> <a href='index.php?act=member_favorites&op=fglist'><b><?php echo $output['member_info']['favorites_count'];?></b><p>我的收藏</p></a> </li>
             <li> <a href='index.php?act=member_order&state_type=state_new'><b><?php echo $output['member_info']['order_nopay_count'];?></b><p>待付款</p></a> </li>
             <li> <a href='index.php?act=member_order&state_type=state_send'><b><?php echo $output['member_info']['order_noreceipt_count'];?></b><p>待收货</p></a> </li>
             <li> <a href='index.php?act=member_order&state_type=state_noeval'><b><?php echo $output['member_info']['order_noeval_count'];?></b><p>待评价</p></a> </li>
           </ul>
        </div>
      </div>
    <?php }else{ ?> 
    <div class="index-right-login-box">
        <script>
            function direct(data){
                if(data.state==1){
                    location.href=data.msg;
                }
            }
        </script>
        <script src="<?php echo $output['action'];?>&ajax=1&process_func=direct"></script>
      <form id="login_form" method="post" action="<?php echo $output['action'];?>">
        <?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="nchash" type="hidden" value="<?php echo getNchash('login','index');?>" />
        <dl>
          <dt><strong>登录名</strong></dt>
          <dd>
            <input type="text" class="text" tabindex="1" autocomplete="off"  name="user_name" autofocus >
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt><strong>登录密码</strong><a href="index.php?act=login&op=forget_password" target="_blank">忘记登录密码？</a></dt>
          <dd>
            <input tabindex="2" type="password" class="text" name="password" autocomplete="off">
            <label></label>
          </dd>
        </dl>
        <?php if(C('captcha_status_login') == '1') { ?>
        <dl>
          <dt><strong>验证码</strong><a href="javascript:void(0)" class="ml5" onclick="javascript:document.getElementById('index_codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash('login','index');?>&t=' + Math.random();">更换验证码</a></dt>
          <dd>
            <input tabindex="3" type="text" name="captcha" autocomplete="off" class="text w70" id="captcha2" maxlength="4" size="10" />
            <img src="<?php echo SHOP_SITE_URL?>/index.php?act=seccode&op=makecode&nchash=<?php echo getNchash('login','index');?>" name="codeimage" border="0" id="index_codeimage" class="vt">
            <label></label>
          </dd>
        </dl>
        <?php } ?>
        <div class="bottom">
          <input type="submit" class="submit" value="确认">
          <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
          <a href="index.php?act=login&op=ipvp_register" target="_blank">注册新用户</a> </div>
      </form>
    </div>
    <?php } ?>
    <div class="proclamation">
      <ul class="tabs-nav">
        <li class="tabs-selected">
          <h3>招商入驻</h3>
        </li>
        <li>
          <h3><?php echo $output['show_article']['notice']['ac_name'];?></h3>
        </li>
      </ul>
      <div class="tabs-panel"> <a href="<?php echo urlShop('show_joinin', 'index');?>" title="申请商家入驻；已提交申请，可查看当前审核状态。" class="store-join-btn" target="_blank">&nbsp;</a> <a href="<?php echo urlShop('seller_login','show_login');?>" target="_blank" class="store-join-help"><i class="icon-cog"></i>登录商家管理中心</a> </div>
      <div class="tabs-panel tabs-hide">
        <ul class="mall-news">
          <?php if(!empty($output['show_article']['notice']['list']) && is_array($output['show_article']['notice']['list'])) { ?>
          <?php foreach($output['show_article']['notice']['list'] as $val) { ?>
          <li><i></i><a target="_blank" href="<?php echo empty($val['article_url']) ? urlShop('article', 'show',array('article_id'=> $val['article_id'])):$val['article_url'] ;?>" title="<?php echo $val['article_title']; ?>"><?php echo str_cut($val['article_title'],24);?> </a>
            <time>(<?php echo date('Y-m-d',$val['article_time']);?>)</time>
          </li>
          <?php } ?>
          <?php } ?>
        </ul>
      </div>
    </div>
  </div>
</div>
<!--HomeFocusLayout End-->

<div class="home-sale-layout wrapper">
  <?php if(!empty($output['xianshi_item']) && is_array($output['xianshi_item'])) { ?>
  <div class="right-sidebar">
    <div class="title">
      <h3><?php echo $lang['nc_xianshi'];?></h3>
    </div>
    <div id="saleDiscount" Class="sale-discount">
      <ul>
        <?php foreach($output['xianshi_item'] as $val) { ?>
        <li>
          <dl>
            <dt class="goods-name"><?php echo $val['goods_name']; ?></dt>
            <dd class="goods-thumb"><a href="<?php echo urlShop('goods','index',array('goods_id'=> $val['goods_id']));?>"> <img src="<?php echo thumb($val, 240);?>"></a></dd>
            <dd class="goods-price">


                <?php if($output['show_price']['show_price']){?>
                    <?php echo ncPriceFormatForList($val['xianshi_price']); ?> <span class="original"><?php echo ncPriceFormatForList($val['goods_price']);?></span>
                <?php }else{?>
                    <p class="no-show-price"><?php echo $output['show_price']['show_price_short_tip'];?></p>
                <?php }?>
            </dd>
            <!--<dd class="goods-price-discount"><em><?php echo $val['xianshi_discount']; ?></em></dd>-->
            <dd class="time-remain" count_down="<?php echo $val['end_time']-TIMESTAMP;?>"><i></i><em time_id="d">0</em><?php echo $lang['text_tian'];?><em time_id="h">0</em><?php echo $lang['text_hour'];?> <em time_id="m">0</em><?php echo $lang['text_minute'];?><em time_id="s">0</em><?php echo $lang['text_second'];?> </dd>
            <dd class="goods-buy-btn"></dd>
          </dl>
        </li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <?php } ?>
</div>
<div class="wrapper">
  <div class="mt10">
    <div class="mt10"><?php echo loadadv(11,'html');?></div>
  </div>
</div>
<!--StandardLayout Begin--> 
<?php echo $output['web_html']['index'];?> 
<!--StandardLayout End-->
<div class="wrapper">
  <div class="mt10"><?php echo loadadv(9,'html');?></div>
</div>
<!--link Begin-->
<!--<div class="full_module wrapper">
  <h2><b><?php echo $lang['index_index_link'];?></b></h2>
  <div class="piclink">
    <?php if(is_array($output['$link_list']) && !empty($output['$link_list'])) {
		  	foreach($output['$link_list'] as $val) {
		  		if($val['link_pic'] != ''){
		  ?>
    <span><a href="<?php echo $val['link_url']; ?>" target="_blank"><img src="<?php echo $val['link_pic']; ?>" title="<?php echo $val['link_title']; ?>" alt="<?php echo $val['link_title']; ?>" width="88" height="31" ></a></span>
    <?php
		  		}
		 	}
		 }
		 ?>
    <div class="clear"></div>
  </div>
  <div class="textlink">
    <?php 
		  if(is_array($output['$link_list']) && !empty($output['$link_list'])) {
		  	foreach($output['$link_list'] as $val) {
		  		if($val['link_pic'] == ''){
		  ?>
    <span><a href="<?php echo $val['link_url']; ?>" target="_blank" title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'],16);?></a></span>
    <?php
		  		}
		 	}
		 }
		 ?>
    <div class="clear"></div>
  </div>
</div>-->
<!--link end-->
<div class="footer-line"></div>
<!--首页底部保障开始-->
<?php require_once template('layout/index_ensure');?>
<!--首页底部保障结束-->
<!--StandardLayout Begin-->
<div class="nav_Sidebar">
<a class="nav_Sidebar_1" href="javascript:;" ></a>
<a class="nav_Sidebar_2" href="javascript:;" ></a>
<a class="nav_Sidebar_3" href="javascript:;" ></a>
<a class="nav_Sidebar_4" href="javascript:;" ></a>
</div>
<!--StandardLayout End-->
<script>
    function addcookie(name,value,expireHours){
        var cookieString=name+"="+escape(value)+"; path=/";
        //判断是否设置过期时间
        if(expireHours>0){
            var date=new Date();
            date.setTime(date.getTime+expireHours*3600*1000);
            cookieString=cookieString+"; expire="+date.toGMTString();
        }
        document.cookie=cookieString;
    }

    function getcookie(name){
        var strcookie=document.cookie;
        var arrcookie=strcookie.split("; ");
        for(var i=0;i<arrcookie.length;i++){
            var arr=arrcookie[i].split("=");
            if(arr[0]==name)return arr[1];
        }
        return "";
    }
    function close_ad(){
        $('#modal').hide();$('.ad').hide();
    }
    $(function(){
//        if(!getcookie('show_ad')){
//            addcookie('show_ad',1,0.5);
//            $('#modal').show();
//            $('#ad_content').show();
//        }

    });
</script>
<div id="modal" class="popup"></div>
<div class="ad" id="ad_content">
    <div class="close" onclick="close_ad()"></div>
    <a href="index.php?act=goods&op=index&goods_id=104363">
    <img src="<?php echo SHOP_TEMPLATES_URL.'/images/ad.jpg';?>" class="ad-img">
    </a>
</div>

