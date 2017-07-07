<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/layout.css">
</head>
<body>
<?php include('top.php')?>

<div class="wrapper order-form">
    <div class="container_24 x-clear">
        <div class="grid_20 prefix_2">
            <div class="step-1"><img src="../image/img_alicdn.png"></div>
            <h3>您购买的课程：</h3>
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
                    <tfoot>
                    <tr>
                        <td colspan="6" align="right">
                            <strong class="x-label">
                                商品总价： <span class="x-highlight">￥10.00</span> 元&nbsp;&nbsp;&nbsp;
                            </strong>
                            <a class="x-button x-button-hightlight x-button-fixed">
                            <span class="x-button-container">
                            <span class="x-button-content" >
		                   <button type="button"  id="btnPay" onclick="location='orderstart1.php' ">确认并支付</button>
                            </span>
                            </span>
                            </a>
                            <div class="agreement" data-isagree="false" id="boxAgreement">
                                <span id="tipAgreement" class="x-tipbox x-tipbox-error">请先接受服务协议</span>
                                <input type="checkbox" id="checkAgreement" checked="checked"><label for="checkAgreement">我已阅读<a href="service-notice.php" target="_blank" >《服务须知》</a></label>
                            </div>
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>

</body>
</html>