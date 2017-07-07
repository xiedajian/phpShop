<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <script type="text/javascript" src="../js/jquery.js"></script>

    <style type="text/css">
        .pay_way{width: 100%;height:325px;background-color: #FFFCEF;border:1px solid #D8D8D8;margin-top: 25px;}
        .way_name{font-size: 14px;padding: 30px 15px;}
        .pay_jiner{font-size: 16px;padding: 15px 0;font-weight: bolder}
        .pay_order{font-size: 16px;padding: 15px 0;font-weight: bolder;cursor: pointer}

        .pay_way ul li {text-align:center;width: 156px;height: 56px;background-color: #fff;float: left;margin-left: 20px;margin-right: 20px;}
        .pay_way ul li img{padding-top: 2px;}
        .pay_current{background: url("../image/gouxuan.png")}
        
        .zhifu_btn{background: url("../image/zhifu_btn.png") repeat-x;width: 155px;margin-top: 100px;height: 60px;margin-left: 20px;color: #fff;font-size: 16px;line-height: 60px;text-align: center}
    
    </style>
</head>
<body>
<?php include('top.php')?>

<div class="wrapper order-form">
    <div class="container_24 x-clear">
        <div class="grid_20 prefix_2">
            <div class="step-1"><img src="../image/img_alicdn1.png"></div>
            <div class="pay_jiner">实付金额：<span style="font-size: 20px;color: #E1017E">99.00</span></div>
            <div class="pay_order">订单详情<img src="../image/xia_sanjiao.png"></div>
            <div>
                <table class="pay">
                    <thead>
                    <tr>
                        <td class="left">课程名称</td>
                        <td>课程类型</td>
                        <td>价格（元）</td>
                        <td>数量</td>
                        <td>优惠方式</td>
                        <td>小计(元)</td>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td class="left name">
                            <div class="x-imagebox">
                                <a href="" class="x-videobutton x-videobutton-normal x-imagebox-img" target="_blank">
                                    <img class="x-image x-image-normal" src="../image/order.png">
                                </a>
                                <div class="img_title">  知识产权侵权申诉流程讲解  </div>
                            </div>
                        </td>
                        <td>点播课程</td>
                        <td>10.00</td>
                        <td>1</td>
                        <td>-</td>
                        <td><span class="x-highlight">10.00</span></td>
                    </tr>

                    </tbody>

                </table>
            </div>
            <div class="pay_way">
                <div class="way_name">支付方式</div>
                <ul class="way_moth">
                  <li class="pay_current"><img src="../image/zhifubao.png"></li>
                  <li class=""><img src="../image/weixin2.png"></li>
                </ul>
                <div class="zhifu_btn">确认并支付</div>

            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".way_moth li").click(function(){
           $(".way_moth li").removeClass("pay_current");
            $(this).addClass("pay_current");
        });
        $(".pay_order").click(function(){
            $(".pay").toggle();
        });
    });
</script>
</body>
</html>