<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/course.css">
    <script type="text/javascript" src="js/jquery.js"></script>

    <title></title>
    <style type="text/css">
        body { background-color: rgb(231,233,237); font-size: 14px; }
    </style>
</head>
<body>

<?php include('top.php')?>

<div class="center-top scroll-wrapper"></div>
<div class="center-top2">
    <img id="avatar" class="center_avatar" src="http://www.ipvp.cn/data/upload/shop/common/default_user_portrait.gif">
    <div class="member-info">
        <span id="username">小胖</span>
    </div>
</div>
<div class="sep"></div>

<div class="menu_3">
   <div class="menu_title">我的课堂</div>
    <div class="sep-line"></div>
    <a href="myfav.php">
        <div class="item">
            <span class="history-icon"><img src="images/store11.png"> </span>
            <span class="txt">我的收藏(4)</span>
            <span class="center-icon right-icon arrow-icon"></span>
        </div>
    </a>
    <div class="sep-line"></div>
    <a href="dai_purchased.php">
        <div class="item">
            <span class=" history-icon"><img src="images/dai_buy.png"> </span>
            <span class="txt">待购买(2)</span>
            <span class="center-icon right-icon arrow-icon"></span>
        </div>
    </a>
    <div class="sep-line"></div>
    <a href="yi_puchased.php">
        <div class="item">
            <span class=" history-icon"><img src="images/yi_buy.png"> </span>
            <span class="txt">已购买(3)</span>
            <span class="center-icon right-icon arrow-icon"></span>
        </div>
    </a>
    <div class="sep-line"></div>
    <a href="myfav.php">
        <div class="item">
            <span class="history-icon"><img src="images/center_tui.png"> </span>
            <span class="txt">已加入公众号的图文素材(20)</span>
            <span class="center-icon right-icon arrow-icon"></span>
        </div>
    </a>
</div>

<div class="sep"></div>
<div class="menu_3">
    <div class="menu_title">个性化</div>
    <div class="sep-line"></div>
    <a href="myfav.php">
        <div class="item">
            <span class="history-icon"><img src="images/center_zuji.png"> </span>
            <span class="txt">我的足迹</span>
            <span class="center-icon right-icon arrow-icon"></span>
        </div>
    </a>
    <div class="sep-line"></div>
    <a href="myfav.php">
        <div class="item">
            <span class="history-icon"><img src="images/tuijian.png"> </span>
            <span class="txt">为我推荐</span>
            <span class="center-icon right-icon arrow-icon"></span>
        </div>
    </a>
</div>

<div class="sep"></div>
<div class="menu_3">
    <div class="menu_title">帮助/客服</div>
    <div class="sep-line"></div>
        <div  id="kefu" class="item">
            <span class="history-icon"><img src="images/phoen_kefu.png"> </span>
            <span class="txt">联系客服</span>
            <span class="center-icon right-icon arrow-icon"></span>
        </div>

    <div class="sep-line"></div>
        <div  id="daoshi" class="item">
            <span class="history-icon"><img src="images/phone_daoshi.png"> </span>
            <span class="txt">联系营销导师</span>
            <span class="center-icon right-icon arrow-icon"></span>
        </div>
</div>
<div class="sep"></div>

<div class="menu_3">
    <div class="menu_title">服务须知</div>
    <div class="sep-line"></div>
    <a href="service-notice.php">
        <div class="item">
            <span class="history-icon "><img src="images/center_service.png"> </span>
            <span class="txt">课程购买服务须知</span>
            <span class="center-icon right-icon arrow-icon"></span>
        </div>
    </a>
</div>
<div class="logout_btn">
    退出当前账号
</div>

<!--遮罩层-->
<div id="BgDiv">

</div>
<!--遮罩层显示的DIV1-->
<div id="DialogDiv"  style="display: none;">
    <p>电话：4000908982</p>
    <p>qq:2785299647</p>
    <p>邮箱：pindanwang@126.com</p>
    <p>微信：母婴拼单网</p>
    <div class="dialog-btn-group">  <button class="dialog-cancel-btn">取消</button>  <button class="dialog-confirm-btn">电话呼叫</button> </div>
</div>
<!--遮罩层显示的DIV1-->
<div id="DialogDiv1"  style="display: none;">
    <p>联系人:姜薇</p>
    <p>电话：4000908982</p>
    <p>qq:2785299647</p>
    <p>邮箱：pindanwang@126.com</p>
    <p>微信：母婴拼单网</p>
    <div class="dialog-btn-group">  <button class="dialog-cancel-btn">取消</button>  <button class="dialog-confirm-btn">电话呼叫</button> </div>
</div>

<script language="javascript" type="text/javascript">
    $(document).ready(function(){
        $("#kefu").click(function(){
            $("#BgDiv").fadeIn();
            $("#DialogDiv").fadeIn();
        });
        $(".dialog-cancel-btn").click(function(){
            $("#BgDiv").fadeOut();
            $("#DialogDiv").fadeOut();
            $("#DialogDiv1").fadeOut();
        });
        $("#daoshi").click(function(){
            $("#BgDiv").fadeIn();
            $("#DialogDiv1").fadeIn();
        });
    });
</script>


</body>
</html>