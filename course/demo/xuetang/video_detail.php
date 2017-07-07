

    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo COURSE_TEMPLATES_URL;?>/css/base.css">
    <link rel="stylesheet" type="text/css" href="<?php echo COURSE_TEMPLATES_URL;?>/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo COURSE_TEMPLATES_URL;?>/css/layout.css">
    <link rel="stylesheet" type="text/css" href="<?php echo COURSE_TEMPLATES_URL;?>/css/online.css">
    <script type="text/javascript" src="<?php echo COURSE_TEMPLATES_URL;?>/js/jquery.js"></script>

    <style type="text/css">
        .container_24 .grid_18 {  width: 890px; }
        .container_24 .grid_6 {width: 290px; }
        .alpha {  margin-left: 0; }
        .grid_18,  .grid_6 {display: inline;float: left; margin-left: 5px;  margin-right: 5px;}
        .ncs-goods { margin-top: 25px; width:99.8%;  border: solid #D7D7D7 1px;float: left}
        .categor1 {  font-size: 0;  background-color: #FCFCFC; border: solid #D7D7D7 1px;padding:3px 0;height: 33px;}
        .categor1 li.current2  {  margin: -7px 0 -1px 0;color: #333; padding: 11px 15px 6px 15px; border-style: solid; border-color: #E1017E #DDD transparent #DDD;  border-width: 2px 1px 0 1px;  }
        .categor1 li { vertical-align: top;  float: left;  letter-spacing: normal;word-spacing: normal;width:200px;text-align:center; }
        .categor1 li  {  font: normal 14px/20px "Microsoft Yahei"; text-decoration: none; color: #777;background-color: #F5F5F5; padding: 6px 15px 10px 15px;  border-style: solid; border-color: #D7D7D7; border-width: 0 1px 0 0;  }

        .J_xuePlayer{height: 668px; width: 890px;margin-top: 35px;background-color: #000;position: relative}

        .video_xinxi{width: 182px;background-color:rgba(0,0,0,0.9);height: 668px;position: absolute;right: 0;top: 0;color: #fff;}
        .video_xinxi img{margin:40px 25px;}
        .video_xinxi p{font-size: 16px;height: 35px;;line-height: 35px;padding-left: 15px;}
        .buy {width:110px;height: 40px;line-height: 40px;font-size: 16px;background-color: #E1017E;text-align: center;margin: 30px; }
        .buy a{color: #fff;}
    </style>



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

    <div class="cus-learn" data-spm="1998201251" style="display: block;">
        <div class="container_24 x-clear">
            <div class="grid_18 omega info-box"  data-isfree="false" data-share-title="天天特价报名攻略 99.99%报名时间" >
                <h2> 天天特价报名攻略 99.99%报名时间</h2>
                <div class="J_xuePlayer">
                     <video width="890" height="668" controls="controls" autoplay="autoplay" >
                        <source src="<?php echo COURSE_TEMPLATES_URL;?>/vedio/yinyue.mp4" type="video/mp4" />
                    </video>
                    <div class="video_xinxi">
                        <div class="delete_vedio" style="font-size: 20px">X</div>
                        <img src="../image/vedio_xinxi.png">
                        <p>课程单价：<span class="red_color">9.90</span> </p>
                        <p>课程时长：16分钟</p>
                        <p>学习数：86</p>
                       <div class="buy"><a>立即购买</a></div>
                        <p>本课程为收费课程购买后可完整学习</p>
                    </div>
                </div>

            </div>

            <div class="grid_6 alpha">
                <!-- 收藏、分享、手机同步看课程链接 -->

                <div class="link-pannel link-learn">
                    <a href="javascript:void(0);" class="J_xueGoFavo"><i class="icon icon-collect"></i>收藏课程</a>
                    <a href="javascript:void(0);" class="pannel-title" data-overlap="share"><i class="icon icon-share"></i>分享课程</a>
                </div>

                <dl class="cus-module cus-related cus-lecture-related " style="height: 668px;">

                    <div id="J_markScore" class="mark-score" data-hasscore="N" style="display: none;">
                        <p class="note info-nocomment">您好, 这门课程符合您的预期么？</p>
                        <span class="note info-comment">已评论</span>
		            <span class="x-starrating x-inline-block">
		                <span style="width:0%" class="x-starrating-fore x-inline-block"></span>
		                <span class="x-starrating-back"></span>
		            </span>
                        <span class="score"><i>0</i>分</span>

                        <div id="J_score_comment">
                            <textarea class="wordarea" cols="30" rows="10" placeholder="您的评论能帮我们提高教课水平....."></textarea>
                            <p class="validCode" style="display: none;">
                                <input type="text" id="validCode1" name="validCode" placeholder="字符不区分大小写">
                                <img src="https://pin.aliyun.com/get_img?sessionid=&amp;identity=daxue.taobao" alt="">
                                <i class="info">输入图片中的字符，<span class="btn-reImg">看不清楚？换张图片</span></i>
                            </p>
                            <a href="javascript:void(0);" class="btn btn-comment">发表评论</a>
                            <span class="icon icon-error">已经超出20个字请注意</span>
                        </div>
                    </div>


                    <dt class="rec"> 上海千浪软 <span>课程推荐</span></dt>
                    <div id="relativeConfigCourse"><!--  -->
                        <dd>
                            <a href="//i.daxue.taobao.com/learning/study/detail-22638.htm" target="_blank">
                                <img src="//img.alicdn.com/bao/uploaded/i4/TB1QhGeLXXXXXb9XFXXXXXXXXXX_!!2-item_pic.png" alt="天天特价报名攻略 99.99%选款过关">
                            </a>
                            <div class="cus-title">
                                <a href="//i.daxue.taobao.com/learning/study/detail-22638.htm" target="_blank">
                                    天天特价报名攻略 99.99%选款过关
                                </a>
                                <p class="cus-num"><i>18</i>人学习</p>
                            </div>
                            <i class="label cus-score">0.0分</i>
                            <i class="icon icon-current"></i>
                        </dd>
                    </div>

                    <div id="relativeConfigCourse"><!--  -->
                        <dd>
                            <a href="//i.daxue.taobao.com/learning/study/detail-22638.htm" target="_blank">
                                <img src="//img.alicdn.com/bao/uploaded/i4/TB1QhGeLXXXXXb9XFXXXXXXXXXX_!!2-item_pic.png" alt="天天特价报名攻略 99.99%选款过关">
                            </a>
                            <div class="cus-title">
                                <a href="//i.daxue.taobao.com/learning/study/detail-22638.htm" target="_blank">
                                    天天特价报名攻略 99.99%选款过关
                                </a>
                                <p class="cus-num"><i>18</i>人学习</p>
                            </div>
                            <i class="label cus-score">0.0分</i>
                            <i class="icon icon-current"></i>
                        </dd>
                    </div>
                    <div id="relativeConfigCourse"><!--  -->
                        <dd>
                            <a href="//i.daxue.taobao.com/learning/study/detail-22638.htm" target="_blank">
                                <img src="//img.alicdn.com/bao/uploaded/i4/TB1QhGeLXXXXXb9XFXXXXXXXXXX_!!2-item_pic.png" alt="天天特价报名攻略 99.99%选款过关">
                            </a>
                            <div class="cus-title">
                                <a href="//i.daxue.taobao.com/learning/study/detail-22638.htm" target="_blank">
                                    天天特价报名攻略 99.99%选款过关
                                </a>
                                <p class="cus-num"><i>18</i>人学习</p>
                            </div>
                            <i class="label cus-score">0.0分</i>
                            <i class="icon icon-current"></i>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
    <div class="ncs-goods">

    <ul class="categor1">
        <li name="tab_1" class="current2">课程内容</li>
        <li name="tab_2" class="">评论<em>(0)</em></li>
        <li name="tab_3" class="">成交 <em>(60)</em></li>
    </ul>

    <div id="tab_1" class="goods hide">
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
                    <p class="pinlun_bref">非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西的东西非常好的东西</p>
                </div>
            </li>
            <li>
                <div class="pinlun">
                    <img src="../image/pinlun_user.png">
                    <div class="pinlun_user">多大的的</div>
                </div>
                <div class="pinlun_nav">
                    <p class="pinlun_xx"> <img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><span class="red_color pd_left15">5.0</span><span class="pinlun_time">2015-12-13 18:49:50</span> </p>
                    <p class="pinlun_bref">非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西常好的东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西</p>
                </div>
            </li>
            <li>
                <div class="pinlun">
                    <img src="../image/pinlun_user.png">
                    <div class="pinlun_user">多大的的</div>
                </div>
                <div class="pinlun_nav">
                    <p class="pinlun_xx"> <img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><img src="../image/wujiao1.png"><span class="red_color pd_left15">5.0</span><span class="pinlun_time">2015-12-13 18:49:50</span> </p>
                    <p class="pinlun_bref">非常好的东西非常好的西非常好的东西非常好的东西东西非常好的东西非常好的东西非常好的东西非常好的东西非常好的东西</p>
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
    <div id="tab_3" class="goods  " >


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
    </div>




<script type="text/javascript">
    $(document).ready(function(){
//        课程内容  评论 成交
        $(".categor1 li").click(function(){
            $(".categor1 li").removeClass("current2");
            var id = $(this).attr("name");
            $("div.goods").addClass("hide");
            $("#" + id).removeClass("hide");
            $(this).addClass("current2");
        });

        $(".delete_vedio").click(function(){
            $(".video_xinxi").hide();
        });



    });



</script>
