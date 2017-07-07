<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo COURSE_RESOURCE_SITE_URL;?>/css/base.css">
    <link rel="stylesheet" type="text/css" href="/<?php echo COURSE_RESOURCE_SITE_URL;?>css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo COURSE_RESOURCE_SITE_URL;?>/css/layout.css">
    <script type="text/javascript" src="<?php echo COURSE_RESOURCE_SITE_URL;?>/js/jquery.js"></script>
    <script type="text/javascript" src="<?php echo COURSE_RESOURCE_SITE_URL;?>/js/home_index.js"></script>

    <style type="text/css">
        .x-image-video { background: url(../image/videologo.png) no-repeat;  position: absolute;  left: 7px; bottom: 7px;  width: 25px; height: 25px; display: block;cursor: pointer;}
        /*.x-image-video:hover{background: url(image/videologo.png) no-repeat;background-position: -25px 0;}*/

    </style>
</head>
<body>
<?php //include('top.php')?>

<nav class="public-nav-layout">
    <div class="wrapper">
        <ul>
            <li><a href="index.php" >首页</a></li>
            <li class="current"><a href="fine_class.php">精品课堂</a></li>
            <li><a href="">广告素材</a></li>
        </ul>
    </div>
</nav>
<!-- 四张动画图片-->
<div class="home-focus-layout">
    <ul id="fullScreenSlides" class="full-screen-slides">
        <li style="background:  url('../image/banner.png') no-repeat center top">
        </li>
        <li style="background:  url('../image/banner1.png') no-repeat center top">
        </li>
    </ul>
</div>

<div class="wrapper mainmargin">
    <div class="class_left">
        <div class="nch-breadcrumb "><i class="icon-home"></i>
            <span><a href="index.php">首页</a></span><span class="arrow">&gt;</span>
            <span><a href="">奶粉辅食</a></span><span class="arrow">&gt;</span>
            <span><a href="">婴幼儿奶粉</a></span><span class="arrow">&gt;</span>
            <span>牛奶粉</span>
        </div>

        <div class="result">
            <div class="result_number"><img src="../image/gradute.png">共找到180个结果 </div>
            <div class="type">
                <ul><li class="type_name">类型:</li><li class="red_color">视频</li><li>图文</li><li>图片/素材</li></ul>
                <div class="clearfix"></div>
                <ul><li class="type_name">付款类型:</li><li class="red_color">付费</li><li>免费</li></ul>
            </div>
        </div>

    <div class="class_detail">
        <div class="detail_paixu">
           <span>排序方式</span> <a class="moren">默认</a><a>热度<img src="../image/result_down.png"> </a><a>价格<img src="../image/result_up.png"></a>
        </div>
        <ul>
            <li class="detail_li">
                <a  target="_blank"  class="x-image-link result-img">
                    <img src="" width="162" height="110">

                </a>
                <div class="detail_left">
                    <div class="detail_name"><a href="img_detail.php">知识产权侵权申诉流程讲解</a> <span>图片/素材</span></div>
                    <div>说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授家讲授说明的明星僵尸楼梦未大家讲授</div>
                </div>
                <div class="detail_right">
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/shopping_cart.png"> 641人已购买</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/discuss.png">64条评论</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/store.png"> 收藏数：79</p>
                </div>
                <div class="detail_price"><span class="red_color">￥</span><span class="price">30</span> <span style=" text-decoration:line-through;">￥50</span></div>
                <img class="class_logo" src="../image/class_logo.png">
            </li>

            <li class="detail_li">
                <a  target="_blank"  class="x-image-link result-img">
                    <img src="" width="162" height="110">
                </a>
                <div class="detail_left">
                    <div class="detail_name"><a href="tuwen_detail.php">知识产权侵权申诉流程讲解</a> <span>图文</span></div>
                    <div><a href="tuwen_detail.php">说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授家讲授说明的明星僵尸楼梦未大家讲授</a> </div>
                </div>
                <div class="detail_right">
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/discuss.png">64条评论</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/store.png"> 收藏数：79</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/tuisong.png">加入公众号待推送类别 </p>
                </div>
            </li>
            <li class="detail_li">
                <a  target="_blank"  class="x-image-link result-img">
                    <img src="" width="162" height="110">
                    <span class="x-image-video"></span>
                </a>
                <div class="detail_left">
                    <div class="detail_name"><a href="video.php">知识产权侵权申诉流程讲解</a> <span>视频</span></div>
                    <div>说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授家讲授说明的明星僵尸楼梦未大家讲授</div>
                </div>
                <div class="detail_right">
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/discuss.png">64条评论</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/store.png"> 收藏数：79</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/tuisong.png">加入公众号待推送类别 </p>
                </div>

            </li>
            <li class="detail_li">
                <a  target="_blank"  class="x-image-link result-img">
                    <img src="" width="162" height="110">
                </a>
                <div class="detail_left">
                    <div class="detail_name">知识产权侵权申诉流程讲解<span>图文</span></div>
                    <div>说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授家讲授说明的明星僵尸楼梦未大家讲授</div>
                </div>
                <div class="detail_right">
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/discuss.png">64条评论</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/store.png"> 收藏数：79</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/tuisong.png">加入公众号待推送类别 </p>
                </div>

            </li>
            <li class="detail_li">
                <a  target="_blank"  class="x-image-link result-img">
                    <img src="" width="162" height="110">
                </a>
                <div class="detail_left">
                    <div class="detail_name">知识产权侵权申诉流程讲解<span>图文</span></div>
                    <div>说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授家讲授说明的明星僵尸楼梦未大家讲授</div>
                </div>
                <div class="detail_right">
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/discuss.png">64条评论</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/store.png"> 收藏数：79</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/tuisong.png">加入公众号待推送类别 </p>
                </div>

            </li>
            <li class="detail_li">
                <a  target="_blank"  class="x-image-link result-img">
                    <img src="" width="162" height="110">
                </a>
                <div class="detail_left">
                    <div class="detail_name">知识产权侵权申诉流程讲解<span>图文</span></div>
                    <div>说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授家讲授说明的明星僵尸楼梦未大家讲授</div>
                </div>
                <div class="detail_right">
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/discuss.png">64条评论</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/store.png"> 收藏数：79</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/tuisong.png">加入公众号待推送类别 </p>
                </div>

            </li>
            <li class="detail_li">
                <a  target="_blank"  class="x-image-link result-img">
                    <img src="" width="162" height="110">
                </a>
                <div class="detail_left">
                    <div class="detail_name">知识产权侵权申诉流程讲解<span>图文</span></div>
                    <div>说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授家讲授说明的明星僵尸楼梦未大家讲授</div>
                </div>
                <div class="detail_right">
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/discuss.png">64条评论</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/store.png"> 收藏数：79</p>
                    <p><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/tuisong.png">加入公众号待推送类别 </p>
                </div>

            </li>
            <li class="detail_li">
                <a  target="_blank"  class="x-image-link result-img">
                    <img src="" width="162" height="110">
                </a>
                <div class="detail_left">
                    <div class="detail_name">知识产权侵权申诉流程讲解<span>图文</span></div>
                    <div>说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授说明的明星僵尸楼梦未大家讲授家讲授说明的明星僵尸楼梦未大家讲授</div>
                </div>
                <div class="detail_right">
                    <p><img src="../image/discuss.png">64条评论</p>
                    <p><img src="../image/store.png"> 收藏数：79</p>
                    <p><img src="../image/tuisong.png">加入公众号待推送类别 </p>
                </div>
            </li>
        </ul>
        <div class="page">
            每页显示数量<div class="page_no"></div>
        </div>

    </div>
    </div>
    <div class="class_right">
        <img src="../image/ad_wei.png">
        <div class="class_fine">
            <div class="fine_left"><a>精品课程</a></div> <div class="fine_right"><a class="chang"><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/change-group.png"> 换一组</a></div>
        </div>
        <ul>
            <li>
                <img src="<?php echo COURSE_TEMPLATES_URL;?>/image/class_fine.png" width="264" height="180">
                <div class="fine_name pd_left15">什么是直邮宝跨境体验店</div>
            </li>
            <li>
                <img src="<?php echo COURSE_TEMPLATES_URL;?>/image/class_fine.png" width="264" height="180">
                <div class="fine_name pd_left15">什么是直邮宝跨境体验店</div>
            </li>
            <li>
                <img src="<?php echo COURSE_TEMPLATES_URL;?>/image/class_fine.png" width="264" height="180">
                <div class="fine_name pd_left15">什么是直邮宝跨境体验店</div>
            </li>
            <li>
                <img src="<?php echo COURSE_TEMPLATES_URL;?>/image/class_fine.png" width="264" height="180">
                <div class="fine_name pd_left15">什么是直邮宝跨境体验店</div>
            </li>
        </ul>
    </div>
</div>

<?php include('foot.php')?>

</body>
</html>