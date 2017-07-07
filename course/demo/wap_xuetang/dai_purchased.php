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
        .puchased_list { position: absolute;top: 50px; right: 0;left: 0;margin-bottom: 60px;  }
        .puchased_course {position: relative; padding: 10px 16px 10px 146px;height: 97px; background: #fff; border-bottom: 1px solid #ccc;}
        .puchased_title{  position: absolute;left: 6px;bottom: 40px;}
        .puchased__cover  img{overflow: hidden; position: absolute; top: 30px; left: 20px; width: 120px; height: 68px;}
        .puchased__name {position: absolute;top: 30px;;text-overflow: ellipsis;display: -webkit-box;-webkit-line-clamp: 2; -webkit-box-orient: vertical;word-break: break-all;overflow: hidden;max-height: 40px;white-space: inherit;}
        .puchased__info {position: absolute;top:70px;;overflow: hidden;margin-top: 8px;max-height: 20px;white-space: nowrap; text-overflow: ellipsis; word-break: break-all;}
        .pachase_price{padding: 15px;font-weight: bolder}
        .pachase_price span{float: right}
        .pachase_btn{position: fixed;bottom: 0;height: 55px;width: 100%;line-height: 55px;color: #fff;text-align: center;font-size: 16px;}
        .pachase_btn ul li{;width: 50%;float: left}
        .pachase_btn .delete_btn{background-color: #3A3A3A;}
        .pachase_btn .buy_btn{background-color: #E1017E;}
    </style>
</head>
<body>

<?php include('top.php')?>

<div class="puchased_list">
    <ul>
        <li class="puchased_course">
            <div class="puchased_title"> <input type="checkbox"  name="sub"> </div>
            <div class="puchased__cover"><img src="images/220.jpg" </div>
            <h3 class="puchased__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="puchased__info">
                <span class="course__info-item u-price z-free">￥10.00</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>图文</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
        </li>
        <li class="puchased_course">
            <div class="puchased_title"> <input type="checkbox" name="sub">   </div>
            <div class="puchased__cover"><img src="images/220.jpg" </div>
            <h3 class="puchased__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="puchased__info">
                <span class="course__info-item u-price z-free">￥10.00</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>图文</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
        </li>
        <li class="puchased_course">
            <div class="puchased_title"> <input type="checkbox" name="sub">   </div>
            <div class="puchased__cover"><img src="images/220.jpg" </div>
            <h3 class="puchased__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="puchased__info">
                <span class="course__info-item u-price z-free">￥10.00</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>图文</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
        </li>
    </ul>
    <div class="pachase_price">商品金额 <span>￥20.00</span></div>
</div>
<div class="pachase_btn">
    <ul>
        <li class="delete_btn">删除</li>
        <li class="buy_btn">立即购买</li>
    </ul>
</div>


<script type="text/javascript">
    $(document).ready(function(){

    });
</script>

</body>
</html>