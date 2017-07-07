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
    <script type="text/javascript" src="js/lrtk.js"></script>

    <title></title>
    <style type="text/css">
        body{    background: #f2f2f4;}
    </style>
</head>
<body>

<?php include('top.php')?>

<div class="mod-comments-info scroll-wrapper">
    <p class="zonghe">综合评价：<img height="12" width="12"  src="images/wujiao1.png"><img height="12" width="12"   src="images/wujiao1.png"><img height="12" width="12"  src="images/wujiao1.png"><img height="12" width="12"  src="images/wujiao1.png"><img height="12" width="12" src="images/wujiao1.png"><span class="red_color pd_left15">5.0</span>
        <span style="padding-left: 20px;font-weight: normal">近30天评分</span> </p>
</div>
<div class="mod-comments-list__wrap" id="js-comments-list">
    <ul id="comments-list-page" class="mod-comments-list__list">
        <li class="mod-comments-list__item ">
            <div class="star-list">
                <i class="no-event" data-count="1"></i><i class="no-event" data-count="2"></i><i class="no-event" data-count="3"></i><i class="no-event" data-count="4"></i><i class="no-event" data-count="5"></i>
            </div> <div class="mod-comments-list-item__content">课程与描述相符很满意、老师的讲解表达很满意、老师的课前服务很满意</div> <div class="mod-comments-list-item__bottom"> <div class="mod-comments-list__name">大****</div>
            <div class="mod-comments-list-item__date">发表于2015-09-11</div> </div>
         </li>
        <li class="mod-comments-list__item ">
            <div class="star-list">
                <i class="no-event" data-count="1"></i><i class="no-event" data-count="2"></i><i class="no-event" data-count="3"></i><i class="no-event" data-count="4"></i><i class="no-event" data-count="5"></i>
            </div>  <div class="mod-comments-list-item__content">老师讲的很好，很期待</div> <div class="mod-comments-list-item__bottom"> <div class="mod-comments-list__name">大****</div>
            <div class="mod-comments-list-item__date">发表于2015-09-11</div> </div>
        </li>
        <li class="mod-comments-list__item " id="commentid-cmt_319243973_120397">
            <div class="star-list">
                <i class="no-event" data-count="1"></i><i class="no-event" data-count="2"></i><i class="no-event" data-count="3"></i><i class="no-event" data-count="4"></i><i class="no-event" data-count="5"></i>
            </div><div class="mod-comments-list-item__content">老师讲的很好，很期待</div>
            <div class="mod-comments-list-item__bottom"> <div class="mod-comments-list__name">大****</div>
            <div class="mod-comments-list-item__date">发表于2015-09-11</div> </div>
        </li>
    </ul>

</div>

<!--遮罩层-->
<div id="BgDiv"></div>

<!--遮罩层显示的DIV1-->
<div id="DialogDiv"  style="display: none;">
    <div class="dialog-content" style="text-align: left">
        <div class="commentbox-bd">
            <div class="comment-star-item">
                <div class="commentbox-bd-row-col1">请您评分：</div>
                <div class="commentbox-bd-row-col1">
                    <ul class="star" id="star">
                        <li><a href="javascript:void(0)" title="1" class="one-star">1</a></li>
                        <li><a href="javascript:void(0)" title="2" class="two-stars">2</a></li>
                        <li><a href="javascript:void(0)" title="3" class="three-stars">3</a></li>
                        <li><a href="javascript:void(0)" title="4" class="four-stars">4</a></li>
                        <li><a href="javascript:void(0)" title="5" class="five-stars">5</a></li>
                    </ul>
                    <div class="current-rating" id="showb"></div>
                </div>
            </div>
                <div >课程评论：</div>
            <div class=" commentbox-bd-row-comment-wrapper"><textarea class="commentbox-bd-comment" placeholder="请输入100字内评论"></textarea> </div>
        </div>
    </div>
    <div class="dialog-btn-group ">  <button class="dialog-cancel-btn">取消</button>  <button class="dialog-confirm-btn">确认</button> </div>
</div>

<div id="js-bottom" class="bottom">
    <div id="js-appearance" class="bottom__app" ><button class="course-action apply">评论</button></div>
</div>


<script language="javascript" type="text/javascript">
$(document).ready(function(){
    $(".bottom__app").click(function(){
         $("#BgDiv").fadeIn();
         $("#DialogDiv").fadeIn();
    });
    $(".dialog-cancel-btn").click(function(){
       $("#BgDiv").fadeOut();
       $("#DialogDiv").fadeOut();
        });
    $("#BgDiv").click(function(){
        $("#BgDiv").hide();
        $("#DialogDiv").hide();
    });


});
</script>

</body>
</html>