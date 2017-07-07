<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=0" />
    <title>HTML5手机网页视频播放器代码</title>

    <link rel="stylesheet" type="text/css" href="css/course.css">
    <script src="js/jquery.js" type="text/javascript" charset="utf-8"></script>
    <script src="js/willesPlay.js" type="text/javascript" charset="utf-8"></script>
    <script type="text/javascript" src="js/layout.js"></script>

    <style type="text/css">
        .class_margintop{background-color: #F0F0F0 ;height: 15px;}


        .course-buy {display: block; border: 0;width: 80px;height: 40px;padding: 0; position: absolute; right: 10px;top: 5px; font-size: 20px;background: #e4007f;color: #fff;border-radius: 3px;}


        #willesPlay .playContent {
            position: relative;
            height: auto;
            top:51px;
            overflow: hidden;
            cursor: pointer;
        }

    </style>
</head>
<body>
<?php include('top.php')?>



<div class="tip"><img src="images/dagou.png"> <span class="store_shifo"></span></div>


<div id="willesPlay">
    <div class="playContent">
        <video width="100%" height="100%" id="playVideo">
            <source src="images/yinyue.mp4" type="video/mp4"></source>
            当前浏览器不支持 video直接播放，点击这里下载视频： <a href="/">下载视频</a>
        </video>
        <div class="playTip"><div class="glyphicon"></div> </div>
    </div>
</div>
<div class="scroll-wrapper">

    <div id="js-container" class="container" style="min-height: 446px;">
        <ul id="js-tab-bar" class="tab-bar" >
            <li name="tab_1" class="tab-bar__item z-active" ><a href="javascript:void(0)">详情</a></li>
            <li name="tab_2" class="tab-bar__item "><a href="javascript:void(0)">相关课程</a></li>
        </ul>
        <div id="tab_1" class="section-rel-class-list">
            <div class="class_margintop" style="margin-top: 46px;"></div>

            <div class="js-item-bref">
                内容简介： 很多销售婴幼儿食品的网站和母婴店所推荐的诺优能Nutrilon奶粉
            </div>
            <div class="class_margintop"></div>

            <div class="item_detail">
                <ul class="data-panel">
                    <li><a href="">14</a><i class="desc">学习人数</i></li>
                    <li class="left-border"><a href="">1</a><i class="desc">评论</i></li>
                    <li class="left-border"><a href="" data-score="0.0">0.0</a>
                        <i class="desc">
                            <img src="images/wujiao3.png"> <img src="images/wujiao3_1.png"> <img src="images/wujiao3_1.png"> <img src="images/wujiao3_1.png"> <img src="images/wujiao3_1.png">
                        </i>
                    </li>
                </ul>
            </div>
            <div class="class_margintop"></div>
            <!--评论-->
            <div id="js-item-comment" class="basic-info-list__item   basic-info-list__item--comment" >
                <p class="basic-info-list__item-title border-bottom">
                    <span class="basic-info-list__item-title-word">  学员评论(292)  </span> <a class="basic-info-list__check-all"  href="comment.php">查看全部</a><i class="icon-font i-v-right"></i>
                </p>
                <ul class="basic-info-list__comment-cnt">
                    <li class="mod-comments-list__item clearfix" >
                        <div class="star-list">
                            <div class="star-list">
                                <i class="no-event" data-count="1"></i><i class="no-event" data-count="2"></i><i class="no-event" data-count="3"></i><i class="no-event" data-count="4"></i><i class="no-event" data-count="5"></i></div></div>
                        <div class="mod-comments-list-item__content">23215</div> <div class="mod-comments-list-item__bottom"> <div class="mod-comments-list__name">流****</div> <div class="mod-comments-list-item__date">发表于昨天</div> </div> <!--</div>-->
                    </li>
                </ul>
            </div>
        </div>
        <div id="tab_2" class="section-rel-class-list hide">
            <div class="class_margintop" style="margin-top: 46px;"></div>
            <div class="rel-class__title">浏览此课程的用户还看了以下课程</div>
            <ul>
                <li class="course">
                    <div class="course__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
                    <h3 class="course__name">保税仓介绍保税仓介绍 保税仓介绍</h3>
                    <p class="course__info">
                        <span class="course__info-item u-price z-free">免费</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i><em>2万</em></span>
                    </p>
                </li>
                <li class="course">
                    <div class="course__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
                    <h3 class="course__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
                    <p class="course__info">
                        <span class="course__info-item u-price z-free">免费</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i><em>2万</em></span>
                    </p>
                </li>
                <li class="course">
                    <div class="course__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
                    <h3 class="course__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
                    <p class="course__info">
                        <span class="course__info-item u-price z-free">免费</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i><em>2万</em></span>
                    </p>
                </li>
                <li class="course">
                    <div class="course__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
                    <h3 class="course__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
                    <p class="course__info">
                        <span class="course__info-item u-price z-free">免费</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i><em>2万</em></span>
                    </p>
                </li>
            </ul>
        </div>

        <div id="js-bottom" class="bottom">
            <div class="bottom__fav-wrap"><button id="js-bottom-fav" class="bottom__fav"><i class="icon-font"><img class="fav_img" src="images/store.png"></i></button></div>
            <div class="bottom__share-wrap"><button id="js-bottom-share" class="bottom__share"><i class="icon-font"><img src="images/share.png"> </i></button></div>
            <div class="bottom__cart-wrap"><button id="js-bottom-cart" class="bottom__cart"><i class="icon-font"><img src="images/cart1.png"> </i></button></div>
            <div id="js-shop" class="bottom__shop"><button class="course-buy ">去购买</button></div>
        </div>

    </div>

</div>

<!--遮罩层-->
<div id="BgDiv"></div>

<!--遮罩层显示的DIV1-->
<div id="shareDiv" style="display: none">
    <div class="share_title">分享到</div>
    <ul>
        <li><img src="images/weixin.png"><p>微信</p></li>
        <li><img src="images/qq.png"><p>QQ空间</p></li>
    </ul>
    <div style="clear: both"></div>
    <div class="share_qx">取消</div>
</div>

<script type="text/javascript">
    $(document).ready(function(){
//        详情 相关课程
        $(".tab-bar li").click(function(){
            $(".tab-bar li").removeClass("z-active");
            var id = $(this).attr("name");
            $("div.section-rel-class-list").addClass("hide");
            $("#" + id).removeClass("hide");
            $(this).addClass("z-active");
        });
    });



</script>
</body>
</html>