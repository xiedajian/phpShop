<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link href="<?php echo COURSE_TEMPLATES_URL;?>/css/base.css" rel="stylesheet" type="text/css">
</head>
<body>
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
                <dt><a target="_blank" href="mine.php">我的课堂</a><i></i> </dt>
                <dd>
                    <ul>
                        <li><a href="">我的收藏</a></li>
                        <li><a href="">待购买</a></li>
                        <li><a href="">已购买</a></li>
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
    <div class="head-logo"><img src="../image/logo.jpg"></div>
    <div class="head-search">
        <input type="text" id="txtHeaderSearch" placeholder="请输入要搜索的内容">
        <input type="submit" id="button" value="搜索" class="input-submit">    </div>
    <div class="head-keyword">
        <a href="search.php">发布自营</a>
        <a href="#">广告素材</a>
        <a href="#">发布自营</a>
        <a href="#">发布自营</a>
    </div>
</div>
<script type="text/javascript">
    $(function(){
        $(".quick-menu dl").hover(function() {
                $(this).addClass("hover");
            },
            function() {
                $(this).removeClass("hover");
            });

    });
</script>
</body>
</html>