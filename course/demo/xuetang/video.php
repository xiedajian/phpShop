<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="css/base.css">
    <link rel="stylesheet" type="text/css" href="css/index.css">
    <link rel="stylesheet" type="text/css" href="css/layout.css">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <title></title>
    <style type="text/css">
        .share_item{border: 1px solid #ccc;width: 70px;height:25px;position: absolute;left: 110px;z-index: 99;}
        .share_item img{padding-right: 5px;}
    </style>
</head>
<body>
<?php include('top.php')?>
<nav class="public-nav-layout">
    <div class="wrapper">
        <ul>
            <li class="current"><a href="index.php" >首页</a></li>
            <li><a href="fine_class.php">精品课堂</a></li>
            <li><a href="">广告素材</a></li>
        </ul>
    </div>
</nav>

<div class="wrapper mainmargin">
    <div class="video">
        <div class="nch-video ">
            <span><a href="index.php"><img src="../image/index_index.png" style="margin-bottom: 5px;"> 首页</a></span><span class="arrow">&gt;</span>
            <span><a href="">奶粉辅食</a></span><span class="arrow">&gt;</span>
            <span><a href="">婴幼儿奶粉</a></span><span class="arrow">&gt;</span>
            <span><a href="">牛奶粉</a></span>
        </div>
        <div class="video_img">
            <img src="../image/video1.png" width="493" height="341">
        </div>
        <div class="video_detail">
            <h2>国内诺优能奶粉就是荷兰牛栏吗？</h2>
            <p>很多销售婴幼儿食品的网站和母婴店所推荐的诺优能Nutrilon奶粉很多销售婴幼儿食品的网站和母婴店所推荐的诺优能Nutrilon奶粉</p>
            <ul class="data-panel">
                <li><a href="">14</a><i class="desc">学习人数</i></li>
                <li class="left-border" ><a href="">1</a><i class="desc">评论</i></li>
                <li class="left-border"><a href=""  data-score="0.0">0.0</a>
                    <i class="desc">
                        <img src="../image/wujiao3.png"> <img src="../image/wujiao3_1.png"> <img src="../image/wujiao3_1.png"> <img src="../image/wujiao3_1.png"> <img src="../image/wujiao3_1.png">
                    </i></li>
            </ul>
            <div class="video_price">
                <span class="price">￥5.00</span>
                    <span class="pd_left15">
                        <div class="old_price">￥10.00</div><div class="price_shouming">特价仅剩4天3小时2分钟</div>
                    </span>
            </div>
            <div class="video_btn">
                <a  class="jiaru_class btn" onclick="divshow()">加入待购买课程</a>
                <a target="_blank" href="orderstart.php" class="liji_buy btn">立即购买</a>
                <a href="video_detail.php" class="privew btn">免费试看</a>
            </div>
            <div class="link_pannel">
                <a href=""><img src="../image/store.png">收藏课程</a>
                <a class="share_fen"><img src="../image/share.png"> 分享课程</a>
                <div class="share_item" style="display: none">
                    <img src="../image/share_qq.png">
                    <img src="../image/share_weixin.png">
                </div>

            </div>

        </div>
    </div>

<!--课程内容，评论 成交-->

    <div class="ncs-goods-title-nav">
        <ul class="categor">
            <li name="tab_1" class="current1">课程内容</li>
            <li name="tab_2" class="">评论<em>(0)</em></li>
            <li name="tab_3" class="">成交 <em>(60)</em></li>
        </ul>

        <div id="tab_1" class="goods ">
            <img src="../image/class_img.png">
        </div>
        <div id="tab_2"  class="goods hide">
           <p class="zonghe">综合评价：<img src="../image/wujiao2.png"><img src="../image/wujiao2.png"><img src="../image/wujiao2.png"><img src="../image/wujiao2.png"><img src="../image/wujiao2.png"><span class="red_color pd_left15">5.0</span> <span style="padding-left: 20px;font-weight: normal">近30天评分</span></p>
            <ul>
                <li>
                    <div class="pinlun">
                         <img src="../image/pinlun_user.png">
                         <div class="pinlun_user">多大的的</div>
                    </div>
                    <div class="pinlun_nav">
                       <p class="pinlun_xx"> <img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><span class="red_color pd_left15">5.0</span><span class="pinlun_time">2015-12-13 18:49:50</span> </p>
                       <p class="pinlun_bref">非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西</p>
                    </div>
                </li>
                <li>
                    <div class="pinlun">
                        <img src="../image/pinlun_user.png">
                        <div class="pinlun_user">多大的的</div>
                    </div>
                    <div class="pinlun_nav">
                         <p class="pinlun_xx"> <img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><span class="red_color pd_left15">5.0</span><span class="pinlun_time">2015-12-13 18:49:50</span> </p>
                         <p class="pinlun_bref">非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西</p>
                    </div>
                </li>
                <li>
                    <div class="pinlun">
                        <img src="../image/pinlun_user.png">
                        <div class="pinlun_user">多大的的</div>
                    </div>
                    <div class="pinlun_nav">
                        <p class="pinlun_xx"> <img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><span class="red_color pd_left15">5.0</span><span class="pinlun_time">2015-12-13 18:49:50</span> </p>
                        <p class="pinlun_bref">非常好的东西非常好的东好的东西</p>
                    </div>
                </li>
            </ul>
            <div class="clearfix"></div>
          <div class="pinlun_dafen">
              <p>请先打分<img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1_1.png"><span class="pd_left15 red_color">4.0</span> </p>
              <textarea></textarea>
              <div class="pinlun_btn">提交评论</div>
          </div>
        </div>
        <div id="tab_3" class="goods hide " >
            <div class="deal-now">
                <p class="x-hint">当前价格:<span class="x-highlight pd_left15 red_color">￥5.0</span></p>
            </div>

            <p class="deal-content-t"><strong>最近一个月成交记录</strong></p>
            <table class="deal-list-table" width="100%" border="0" cellpadding="0" cellspacing="0">
            <thead>
            <tr>
                <th width="50%">学员</th>
                <th width="15%">价格</th>
                <th width="15%">数量</th>
                <th width="20%">成交时间</th>
            </tr>
            </thead>
            <tbody>

            <tr>
                <td>
                    s**i
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 11:11:08</span>
                </td>
            </tr>

            <tr class="tr-highlight">
                <td>
                    朝**具
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 10:51:48</span>
                </td>
            </tr>

            <tr>
                <td>
                    h**d
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 10:41:16</span>
                </td>
            </tr>

            <tr class="tr-highlight">
                <td>
                    抹**笑
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 10:38:47</span>
                </td>
            </tr>

            <tr>
                <td>
                    李**4
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 10:38:08</span>
                </td>
            </tr>

            <tr class="tr-highlight">
                <td>
                    派**9
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 09:47:05</span>
                </td>
            </tr>

            <tr>
                <td>
                    冰**坊
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 09:20:58</span>
                </td>
            </tr>

            <tr class="tr-highlight">
                <td>
                    金**屋
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 09:16:22</span>
                </td>
            </tr>

            <tr>
                <td>
                    h**1
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 09:11:53</span>
                </td>
            </tr>

            <tr class="tr-highlight">
                <td>
                    韶**技
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 09:09:16</span>
                </td>
            </tr>

            <tr>
                <td>
                    c**4
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 09:05:34</span>
                </td>
            </tr>

            <tr class="tr-highlight">
                <td>
                    东**酒
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 08:26:24</span>
                </td>
            </tr>

            <tr>
                <td>
                    衣**8
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 07:56:56</span>
                </td>
            </tr>

            <tr class="tr-highlight">
                <td>
                    a**e
                </td>
                <td>
                    <span class="x-highlight"><strong>0.00</strong></span>
                </td>
                <td>1</td>
                <td>
                    <span class="x-hint">2015-12-28 05:24:51</span>
                </td>
            </tr>

            </tbody>
            </table>

        </div>

 </div>


<!--右侧猜你喜欢-->
    <div class="class_right">
        <div class="class_fine">
            <div class="fine_left"><a>猜你喜欢</a></div> <div class="fine_right"><a class="chang"><img src="../image/change-group.png"> 换一组</a></div>
        </div>
        <ul>
            <li>
                <img src="../image/class_fine.png" width="264" height="180">
                <div class="fine_name pd_left15">什么是直邮宝跨境体验店</div>
            </li>
            <li>
                <img src="../image/class_fine.png" width="264" height="180">
                <div class="fine_name pd_left15">什么是直邮宝跨境体验店</div>
            </li>
            <li>
                <img src="../image/class_fine.png" width="264" height="180">
                <div class="fine_name pd_left15">什么是直邮宝跨境体验店</div>
            </li>
            <li>
                <img src="../image/class_fine.png" width="264" height="180">
                <div class="fine_name pd_left15">什么是直邮宝跨境体验店</div>
            </li>
        </ul>
    </div>

</div>
<!--遮罩层-->
<div id="BgDiv">

</div>

<!--遮罩层显示的DIV1-->
<div id="DialogDiv" style="display: none">
    <h2><a href="#" id="btnClose" onclick="closeDiv()"><img src="../image/div_delete.png"> </a></h2>
    <p><img src="../image/dagou.png">该付费课程已加入我的课程（待购买） </p>
    <p><a style="color: #fff;text-decoration: underline;" href="">查看我的课程（待购买）</a> </p>
    <div class="queding_btn" onclick="closeDiv()">确定</div>
</div>

<?php include('foot.php')?>


<script type="text/javascript">
    $(document).ready(function(){
//        课程内容  评论 成交
        $(".categor li").click(function(){
            $(".categor li").removeClass("current1");
            var id = $(this).attr("name");
            $("div.goods").addClass("hide");
            $("#" + id).removeClass("hide");
            $(this).addClass("current1");
        });


    });



</script>
<script language="javascript" type="text/javascript">
    function divshow() {
        $("#BgDiv").css({ display: "block", height: $(document).height() });
        var yscroll = document.documentElement.scrollTop;
        $("#DialogDiv" ).css("top", "100px");
        $("#DialogDiv" ).css("display", "block");
        document.documentElement.scrollTop = 0;
    }

    function closeDiv() {
        $("#BgDiv").css("display", "none");
        $("#DialogDiv" ).css("display", "none");
    }
    $(document).ready(function(){

        $('.share_fen').hover(function(){
            $(".share_item").show();
        },function(){
            $(".share_item").hide();
        });

    });
</script>

</body>
</html>