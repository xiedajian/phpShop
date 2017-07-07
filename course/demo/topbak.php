<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="public-top-layout">
    <div class="topbar wrapper">
        <div class="user-entry">
            欢迎您来到 <a href="">拼单网学堂</a> <span>[<a href="index1.php">登录</a>]</span> <span>[<a href="http://www.ipvp.cn/shop/index.php?act=login&amp;op=ipvp_register">注册</a>]</span>
        </div>
        <div class="quick-menu">
            <dl>
                <dt><a href="">我的微店</a></dt>
            </dl>
            <dl>
                <dt><a href="">我的套餐</a></dt>
            </dl>
            <dl>
                <dt><a href="index.php?act=myclass&op=index">我的课堂</a><i></i> </dt>
                <dd>
                    <ul>
                        <li><a href="">我的收藏</a></li>
                        <li><a href="">待付款</a></li>
                        <li><a href="">已付款</a></li>
                    </ul>
                </dd>
            </dl>
            <dl>
                <dt><a href="">联系客服</a></dt>
            </dl>
            <dl>
                <dt><a href="">联系营销导师</a></dt>
            </dl>
        </div>
    </div>
</div>
<div class="head wrapper">
    <div class="head-logo"><a href="<?php echo APP_SITE_URL;?>"><img src="<?php echo COURSE_RESOURCE_SITE_URL;?>/images/logo.jpg"></a></div>
    <div class="head-search">
        <form method="get" action="">
            <input type="text" name="keyword" class="keyword-box" placeholder="请输入要搜索的内容">
            <input type="submit" value="搜索" class="input-submit search-btn">    </div>
    </form>
    <div class="head-keyword">
        <a href="search.php">发布自营</a>
        <a href="#">广告素材</a>
        <a href="#">发布自营</a>
        <a href="#">发布自营</a>
    </div>
</div>

<div class="public-nav-layout" style="height: 355px;">
    <div class="nav-list">
        <div class="all-category">
            <div class="title">
                <i></i>
                <h3>所有分类</h3>
            </div>
            <div class="category">
                <ul class="menu">
                    <?php if (!empty($output['category_list']) && is_array($output['category_list'])) { $i = 0; ?>
                        <?php foreach ($output['category_list'] as $key => $val) { $i++; ?>
                            <li cat_id="<?php echo $val['category'];?>" class="<?php echo $i%2==1 ? 'odd':'even';?>" <?php if($i>8){?>style="display:none;"<?php }?>>
                                <div class="class">
                                    <h4><a href="<?php echo urlShop('course_search','index',array('cate_id'=> $val['category']));?>"><?php echo $val['name'];?></a></h4>
                                        <span class="recommend-class">
                                          <?php if (!empty($val['sub_category']) && is_array($val['sub_category'])) { ?>
                                              <?php foreach ($val['sub_category'] as $k => $v) { ?>
                                                  <a href="<?php echo urlShop('course_search','index',array('cate_id'=> $v['category']));?>" title="<?php echo $v['name']; ?>"><?php echo $v['name'];?></a>
                                              <?php } ?>
                                          <?php } ?>
                                          </span>
                                    <span class="arrow"></span>
                                </div>
                                <div class="sub-class" cat_menu_id="<?php echo $val['category'];?>">
                                    <?php if (!empty($val['sub_category']) && is_array($val['sub_category'])) { ?>
                                        <?php foreach ($val['sub_category'] as $k => $v) { ?>
                                            <dl>
                                                <dt>
                                                <h3><a href="<?php echo urlShop('course_search','index',array('cate_id'=> $v['category']));?>"><?php echo $v['name'];?></a></h3>
                                                </dt>
                                                <dd class="goods-class">
                                                    <?php if (!empty($v['sub_category']) && is_array($v['sub_category'])) { ?>
                                                        <?php foreach ($v['sub_category'] as $k3 => $v3) { ?>
                                                            <a href="<?php echo urlShop('course_search','index',array('cate_id'=> $v3['category']));?>"><?php echo $v3['name'];?></a>
                                                        <?php } ?>
                                                    <?php } ?>
                                                </dd>
                                            </dl>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } ?>
                    <?php } ?>
                </ul>
            </div>
        </div>
        <ul class="site-menu">
            <?php if($output['nav_list']){foreach($output['nav_list'] as $val){?>
                <li><a href="<?php echo $val['link'];?>"<?php if($val['blank'])echo ' target="_blank"';?>><?php echo $val['title'];?></a></li>
            <?php }}?>
        </ul>
        <div class="user-info">

        </div>
    </div>
    <style type="text/css">
        .focus-wrapper ul{position:relative;width: 100%;}
        .focus-wrapper ul li{display:block; position:absolute; left:0; top:0;width: 100%;}
        .focus-wrapper ul li div{width: 100%;height: 300px;}
    </style>
    <div class="focus-wrapper" id="banner" style="">
        <ul>
            <?php if(is_array($output['focus_list'])&&!empty($output['focus_list'])) { ?>
                <?php foreach($output['focus_list'] as $val){?>
                    <li>
                        <div class="focus-img" style="background: url(<?php echo $val['image_url'];?>) 50% 0% no-repeat"></div>
                    </li>
                <?php }?>
            <?php } ?>
        </ul>
    </div>
</div>
<script>
    $(function(){
        focus_banner('banner',10000,2000);
    });
    function focus_banner(id,switchSpeed,fadeSpeed){
        //switchSpeed 图片切换时间
        //fadeSpeed 渐变时间
        setInterval(function(){
            $("#"+id).find('.focus-img').last().fadeOut(fadeSpeed, function(){
                $(this).show().parent().prependTo($("#"+id).find('ul'));
            });
        }, switchSpeed);
    }
    $(".category ul.menu").find("li").each(
        function() {
            $(this).hover(
                function() {
                    var cat_id = $(this).attr("cat_id");
                    var menu = $(this).find("div[cat_menu_id='"+cat_id+"']");
                    menu.show();
                    $(this).addClass("hover");
                    if(menu.attr("hover")>0) return;
                    menu.masonry({itemSelector: 'dl'});
                    var menu_height = menu.height();
                    if (menu_height < 60) menu.height(80);
                    menu_height = menu.height();
                    var li_top = $(this).position().top;
                    if ((li_top > 60) && (menu_height >= li_top)) $(menu).css("top",-li_top+50);
                    if ((li_top > 150) && (menu_height >= li_top)) $(menu).css("top",-li_top+90);
                    if ((li_top > 240) && (li_top > menu_height)) $(menu).css("top",menu_height-li_top+90);
                    if (li_top > 300 && (li_top > menu_height)) $(menu).css("top",60-menu_height);
                    if ((li_top > 40) && (menu_height <= 120)) $(menu).css("top",-5);
                    menu.attr("hover",1);
                },
                function() {
                    $(this).removeClass("hover");
                    var cat_id = $(this).attr("cat_id");
                    $(this).find("div[cat_menu_id='"+cat_id+"']").hide();
                }
            );
        }
    );
</script>

