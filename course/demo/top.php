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
                <dt><a href="index.php?act=myclass&op=index&tab=collection">我的课堂</a><i></i> </dt>
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

