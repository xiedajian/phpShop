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
    <link type="text/css" rel="stylesheet" href="css/demo.css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/demo.js"></script>


    <title></title>
    <style>
        .search-box {    position: fixed; z-index: 10003;background: #f8f8f8;  width: 100%;top: 50px;overflow: hidden}
        .m-search {  padding: 0 15px;height: 48px;display: -webkit-box;-webkit-box-align: center; }
        .f-border-1px { position: relative; }
        .u-search { display: -webkit-box; background: #fff;-webkit-box-flex: 1;-webkit-box-align: center;    border: 1px solid #a6a6a6; border-radius: 3px;}
        .u-search .i-search { color: #a9a9a9; font-size: 13px; line-height: 13px;width: 13px; height: 13px; display: block; margin: 0 3px 0 9px;}
        .u-search__input { display: block; outline: 0;border: 0;border-radius: 0;height: 30px; line-height: 30px;font-size: 14px; -webkit-box-flex: 1;padding-left: 2px;}
        .u-search__reset-wrap {padding: 7px 9px;}
        html .icon-font, html .f-icon-font::before { visibility: visible;}
        .u-search__reset {display: none;border-radius: 8px;width: 16px; height: 16px; background: #8e8e93; font-size: 12px;line-height: 16px;color: #fff;text-align: center;}
        .icon-font {visibility: hidden; font-family: iconfont;speak: none;font-style: normal;font-weight: 400;font-variant: normal;text-transform: none;line-height: 1; font-size: 16px; position: relative; vertical-align: -2px;-webkit-font-smoothing: antialiased; }
        .u-button {height: 30px; margin: 4px 0 4px 10px;padding: 0 11px;border-radius: 3px;background: #fff;color: #0079ff;font-size: 14px;line-height: 30px;text-align: center; border: 1px solid #0079ff;}
         .section{margin-top: 150px;}



    </style>


</head>
<body>

<?php include('top.php')?>

<div class="search-box">
    <div class="m-search f-border-1px">
        <div id="searchForm" class="u-search f-border-1px">
            <i class="icon-font i-search"><img src="images/input_search.png"> </i>
            <input id="searchInput" class="u-search__input" type="search" maxlength="40" autocomplete="off" autocorrect="off" placeholder="请输入课程名称">
            <div class="u-search__reset-wrap" cmd="resetSearch"><i class="u-search__reset icon-font i-close"></i></div>
        </div>
        <div class="u-button  u-search__btn" >搜索</div>
    </div>
</div>
<div class="screening">
    <ul >
        <li id="all" class="Regional">全部分类</li>
        <li class="Brand">类型</li>
        <li class="Sort">综合排序</li>
    </ul>
</div>
<!-- End screening -->
<!-- grade -->
<div class="grade-eject">
    <ul class="grade-w" id="gradew">
        <li onclick="grade1(this)">全部</li>
        <li onclick="grade1(this)">直邮宝业务</li>
        <li onclick="grade1(this)">母婴知识库</li>
        <li onclick="grade1(this)">微店管理</li>
    </ul>
    <ul class="grade-t" id="gradet">
        <li onclick="gradet(this)">全部</li>
        <li onclick="gradet(this)">业务介绍</li>
        <li onclick="gradet(this)">加盟方案</li>
        <li onclick="gradet(this)">o2o整体解决方案</li>
        <li onclick="gradet(this)">有偿服务</li>
    </ul>
    <ul class="grade-s" id="grades">
        <li onclick="grades(this)">全部</li>
        <li onclick="grades(this)">政策解读</li>
        <li onclick="grades(this)">公司优势</li>
        <li onclick="grades(this)">精选商品</li>
        <li onclick="grades(this)">售后服务</li>
        <li onclick="grades(this)">FAQ</li>
    </ul>
</div>
<!-- End grade -->
<div class="Brand-eject Brand-height">
    <ul class="Brand-Sort" id="Brand-Sort">
        <li onclick="Brand(this)">视频</li>
        <li onclick="Brand(this)">图文</li>
        <li onclick="Brand(this)">图片/素材</li>
    </ul>
</div>
<!-- Category -->
<div class="Sort-eject Sort-height">
    <ul class="Sort-Sort" id="Sort-Sort">
        <li onclick="Sorts(this)">人气优先</li>
        <li onclick="Sorts(this)">价格从低到高</li>
        <li onclick="Sorts(this)">价格从高到低</li>
    </ul>
</div>

<div class="section">
    <ul>
        <li class="course">
            <div class="course__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
            <h3 class="course__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="course__info">
                <span class="course__info-item u-price z-free">免费</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>图文</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
        </li>
        <li class="course">
            <div class="course__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
            <h3 class="course__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="course__info">
                <span class="course__info-item u-price z-free">免费</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>图文</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
        </li>
        <li class="course">
            <div class="course__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
            <h3 class="course__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="course__info">
                <span class="course__info-item u-price z-free">免费</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>视频</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
        </li>
        <li class="course">
            <div class="course__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
            <h3 class="course__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="course__info">
                <span class="course__info-item u-price z-free">免费</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>图片/素材</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
        </li>
        <li class="course">
            <div class="course__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
            <h3 class="course__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="course__info">
                <span class="course__info-item u-price z-free">免费</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>图文</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
        </li>
    </ul>
</div>



</body>

</html>