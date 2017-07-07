<div id="footer" class="wrapper">
    <p><a href='index.php'>首页</a>
        | <a target="_blank" href="http://ipvp.cn/shop/index.php?act=article&article_id=25">合作与洽谈</a>
        | <a target="_blank" href="http://www.ipvp.cn/shop/index.php?act=article&article_id=23">联系我们</a>
        | <a target="_blank" href="http://www.ipvp.cn/shop/index.php?act=article&article_id=22">关于我们</a>
    </p>
    <p> Copyright 2015</a> ipvp.cn All rights reserved. 闽ICP备08003512号-2</p> <br/>
</div>


<!--左侧微信公众号-->
<div class="weixin-box">
    <div class="weixin" >
        <img src="../image/left_weixin.png">
    </div>
    <div class="weixin-open">
        <div class="wx-open-title">微信公众号(待推送内容)</div>
        <div class="wx-open-bref">
            <ul>
                <li>
                    <div class="open_n"><input type="checkbox"><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/weixin_open.jpg" ><a class="open_name" href="#">文章标题文章标题</a><img class="delete" src="<?php echo COURSE_TEMPLATES_URL;?>/image/delete.png"></div>
                </li>
                <li>
                    <div class="open_n"><input type="checkbox"><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/weixin_open.jpg" ><a class="open_name" href="#">文章标题文章标题</a><img class="delete" src="<?php echo COURSE_TEMPLATES_URL;?>/image/delete.png"></div>
                </li>
                <li>
                    <div class="open_n"><input type="checkbox"><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/weixin_open.jpg" ><a class="open_name" href="#">文章标题</a><img class="delete" src="<?php echo COURSE_TEMPLATES_URL;?>/image/delete.png"></div>
                </li>
                <li>
                    <div class="open_n"><input type="checkbox"><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/weixin_open.jpg" ><a class="open_name" href="#">文章标题</a><img class="delete" src="<?php echo COURSE_TEMPLATES_URL;?>/image/delete.png"></div>
                </li>

            </ul>
        </div>
        <div class="wx-open-btn"><a><input type="checkbox">全选</a> <a class="yijian_btn">一键发布</a></div>
        <div class="wx-open-box"><img src="<?php echo COURSE_TEMPLATES_URL;?>/image/sanjiao_open.png"></div>
    </div>
</div>



<script type="text/javascript">
    $(document).ready(function(){
//  点击展开微信公众号
        $(".weixin").click(function(){
            $(".weixin").css("display","none");
            $(".weixin-open").css("display","block");
        });
//  收缩微信公众号
        $(".wx-open-box").click(function(){
            $(".weixin").css("display","block");
            $(".weixin-open").css("display","none");
        });

    });
</script>

<script type="text/javascript" src="<?php echo COURSE_TEMPLATES_URL;?>/js/toTop.js"></script>

<!--返回顶部-->
<div id="toTop" title="返回顶部">
    <p>
        <a href="#" style="visibility: visible;"></a>
    </p>
</div>