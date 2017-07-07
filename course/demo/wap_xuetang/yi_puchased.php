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
        .puchased_list {position: absolute;top: 45px; right: 0; bottom: 0;left: 0;}
        .puchased_course {position: relative; padding: 10px 16px 10px 146px;height: 147px;background: #fff; border-bottom: 1px solid #ccc;}
        .puchased_title{position: absolute;left: 10px;;}
        .puchased__cover {overflow: hidden; position: absolute; top: 30px; left: 16px; width: 120px; height: 68px; background: url(http://9.url.cn/edu/mobilev2/img/default-img.74ba483.png) #fff no-repeat top left; background-size: 120px 68px; }
        .puchased__name { position: absolute; top: 30px;;text-overflow: ellipsis;display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; word-break: break-all; overflow: hidden;max-height: 40px; white-space: inherit; }
        .puchased__info {position: absolute;top:70px;; overflow: hidden; margin-top: 8px;max-height: 20px;white-space: nowrap;text-overflow: ellipsis; word-break: break-all; }
        .puchased__btn-group {background: #fff;text-align: right; padding: 16px 10px;overflow: hidden; position: absolute; right: 0; top:100px; }
        .puchased__btn.orange {color: #fff;  border: 0; background: #e4007f;}
        .puchased__btn:first-child {margin-right: 0; }
        .puchased__btn { color: #404040; background: #fff; border: 1px solid #c8c8c8; border-radius: 3px;height: 34px; width: 106px;text-align: center; font-size: 14px;display: inline-block; margin-right: 10px; float: right; padding: 0;  }
        .hebing{  height: 45px;;width: 100%;  position: fixed;bottom: 0;}
        .hebing__btn{width: 85px;border: 1px solid #dd017c;float: right;text-align: center;;height: 30px;line-height: 30px;margin-bottom: 20px;margin-right: 20px;}
    </style>
</head>
<body>

<?php include('top.php')?>

<div class="puchased_list">
    <ul>
        <li class="puchased_course">
            <div class="puchased_title"> <input type="checkbox" name="sub"> 下载 </div>
            <div class="puchased__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
            <h3 class="puchased__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="puchased__info">
                <span class="course__info-item u-price z-free">￥10.00</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>图文</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
            <div class="puchased__btn-group"> <button class="puchased__btn orange" data-operation="pay">下载</button> </div>
        </li>
        <li class="puchased_course">
            <div class="puchased_title"> <input type="checkbox"  name="sub"> 下载 </div>
            <div class="puchased__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
            <h3 class="puchased__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="puchased__info">
                <span class="course__info-item u-price z-free">￥10.00</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>图文</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
            <div class="puchased__btn-group"> <button class="puchased__btn orange">下载</button></div>
        </li>
        <li class="puchased_course">
            <div class="puchased__cover" style="background-image:url(http://10.url.cn/qqcourse_logo_ng/ajNVdqHZLLDLibIpzVtOtwDbhv61StuvuBRrItTkY0aWsoHHqbVQWEH1avib6fEGnl2Uaiah6rcn4I/220?tp=webp);"></div>
            <h3 class="puchased__name">保税仓介绍保税仓介绍 保税仓介绍  </h3>
            <p class="puchased__info">
                <span class="course__info-item u-price z-free">￥10.00</span><span class="course__info-item u-message"><i class="icon-font i-user-border"></i>视频</span><span class="course__info-item u-comment"><i class="icon-font"></i><img src="images/start.png"> <em>4.5</em></span>
            </p>
            <div class="puchased__btn-group"> <button class="puchased__btn orange">观看</button></div>
        </li>
    </ul>
    <div class="hebing hide"><div class="hebing__btn">批量下载</div> </div>
</div>


<script type="text/javascript">
    $(document).ready(function(){
        $("input[name='sub']").click(function() {
            $(".hebing").removeClass("hide");
        });
    });
</script>



</body>
</html>