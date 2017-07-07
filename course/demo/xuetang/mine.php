    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="<?php echo COURSE_TEMPLATES_URL;?>/css/base.css">
    <link rel="stylesheet" type="text/css" href="<?php echo COURSE_TEMPLATES_URL;?>/css/index.css">
    <link rel="stylesheet" type="text/css" href="<?php echo COURSE_TEMPLATES_URL;?>/css/layout.css">
    <script type="text/javascript" src="<?php echo COURSE_TEMPLATES_URL;?>/js/jquery.js"></script>

    <style type="text/css">
        input[type="text"], input[type="password"], textarea, select, .type-file-text, .editable, .editable-tarea {
            color: #333333;
            background: #FAFAFA none repeat scroll 0 0;
            border-style: solid;
            border-width: 1px;
            border-color: #ABADB3 #E2E3EA #E2E3EA #ABADB3;
        }
        .pg_btn{background-color: #E1017E;color: #fff; padding: 5px 10px;border-radius: 5px;margin-left: 10px;font-size: 15px;}
        option {
            font-weight: normal;
            display: block;
            padding: 0px 2px 1px;
            white-space: pre;
            min-height: 1.2em;
        }
        #footer{position: fixed;bottom: 0px;}
    </style>
<div class="wrapper">
    <div class="person_center">个人中心</div><div class="person_line"></div>
    <div class="mine_left">
        <ul class="store_ul">
            <li class="my_class xuanzhong" ><img class="img_class" src="<?php echo COURSE_TEMPLATES_URL;?>/image/my_class1.png">我的课堂</li>
            <li class="my_phone" ><img class="img_phone" src="<?php echo COURSE_TEMPLATES_URL;?>/image/my_phone.png">联系我们</li>
        </ul>
    </div>
        <div class="mine_right">
            <div id="mineclass" class=" ">
                <div class="mine_ul top-tab">
                    <ul class="class_ul">
                        <li name="class_1" class="xz" tab="collection">我的收藏</li>
                        <li name="class_2" class="" tab="paying">待付款</li>
                        <li name="class_3" class="" tab="paid">已付款</li>
                    </ul>
                </div>
                <div id="class_1" class="classs ">
                    <div class="my_fenlei">
                            <input type="hidden" value="" name="tab">
                        <table width="90%">
                            <tbody>
                            <tr>
                                <td width="35%">
                                    <!--//zmr>v30-->
                                    选择分类：
                                    <select name="parent_1" id="level_1">
                                        <option value="0">请选择</option>
                                        <?php if($output['category']){foreach($output['category'] as $val){?>
                                            <option value="<?php echo $val['category']?>"><?php echo $val['name'];?><?php echo $val['state']==0?'（禁用）':'';?></option>
                                        <?php }}?>
                                    </select>
                                    <select name="parent_2" id="level_2">
                                        <option value="0">请选择</option>
                                    </select>
                                    <select name="parent_3" id="level_3">
                                        <option value="0">请选择</option>
                                    </select>
                                </td>
                                <td width="22%">标题：<input type="text" value="<?php echo $output['get']['title'];?>" id="title" name="title"></td>
                                <td style="text-align: left;">
                                    <a href="JavaScript:void(0);" class="store_btn" title="<?php echo $lang['nc_query'];?>">查询</a>
                                </td>
                            </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="myclass_list">

                    </div>
                </div>
                </div>
                <div id="minephone" class="hide">
                    <div class="mine_ul">
                        <ul>
                            <li class="kefu xz">联系客服</li>
                            <li class="daoshi">联系营销导师</li>
                        </ul>
                        <div id="kefu">
                            <div class="kf_title">您好，有关微商城相关的营销问题欢迎通过以下方式联系我们，我们将竭诚为您服务！</div>
                            <p>电话：4000908982</p>
                            <p>qq:2785299647</p>
                            <p>邮箱：pindanwang@126.com</p>
                            <p>微信：母婴拼单网</p>
                            <p class="office">福州拼单网电子商务有限公司<span class="blue_color">关于我们</span></p>
                        </div>
                        <div id="daoshi" style="display: none">
                            <div class="kf_title"> 您好，有关微商城相关的营销问题欢迎通过以下方式联系我们，我们将竭诚为您服务！</div>
                            <p>联系人：姜薇</p>
                            <p>电话：12345667899</p>
                            <p>qq:2132132123</p>
                            <p>微信：325645362253</p>
                            <p class="office">福州拼单网电子商务有限公司<span class="blue_color">关于我们</span></p>
                        </div>
                    </div>
                </div>
        </div>

</div>
    <script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script type="text/javascript">
    function load_page(tab,category,title){
        if(!tab)return;
        $('.myclass_list').html('加载中.....');
        if(!category && !title){
            $("input[name='tab']").val(tab);
            $(".myclass_list").load('index.php?act=myclass&op=myclassindex&' + $.param({
                    'tab':tab
                }));
        }else{
            $(".myclass_list").load('index.php?act=myclass&op=myclassindex&' + $.param({
                    'tab':tab,
                    'category': category,
                    'title': title
                }));
        }
    }
    $(function(){
        var tab = '<?php echo $_GET['tab'];?>';
        load_page(tab);
        $('.top-tab').find('li').click(function () {
            $('.top-tab').find('li').removeClass('xz');
            $(this).addClass('xz');
            var tab = $(this).attr('tab');
            load_page(tab);
        });
        $('.store_btn').click(function(){ //查询我的收藏
            var tab = $("input[name='tab']").val();
            var category = 0;
            $('.my_fenlei  select').each(function () {
                if ($(this).val() > 0) category = $(this).val();
            });
            var title = $.trim($('#title').val());
            load_page(tab,category,title);
        });
        $("#level_1").change(function () {
            var code = $("#level_1").val();
            if (!code) {
                $("#level_2").empty();
            } else {
                $.getJSON('index.php?act=myclass&op=ajax_category&code=' + code, function (data) {
                    $("#level_2").empty();
                    $("#level_2").append('<option value="0">请选择</option>');
                    for (var i = 0; i < data.length; i++) {
                        $("#level_2").append('<option value="' + data[i]['category'] + '">' + data[i]['name'] + (data[i]['state'] == 0 ? '(禁用)' : '') + '</option>');
                    }
                });
            }
        });
        $("#level_2").change(function () {
            var code = $("#level_2").val();
            if (!code) {
                $("#level_3").empty();
            } else {
                $.getJSON('index.php?act=mc_category&op=ajax_category&code=' + code, function (data) {
                    $("#level_3").empty();
                    $("#level_3").append('<option value="0">请选择</option>');
                    for (var i = 0; i < data.length; i++) {
                        $("#level_3").append('<option value="' + data[i]['category'] + '">' + data[i]['name'] + (data[i]['state'] == 0 ? '(禁用)' : '') + '</option>');
                    }
                });
            }
        });
        $("#level_1").change();
        $("#level_2").change();
        //我的课堂和联系我们菜单切换
        $(".my_class").click(function(){
            $("#mineclass").css("display","block");
            $("#minephone").css("display","none");
            $(".my_phone").removeClass("xuanzhong");
            $(".my_class").addClass("xuanzhong");
            $(".my_class").children("img").attr("src","<?php echo COURSE_TEMPLATES_URL;?>/image/my_class1.png");
            $(".my_phone").children("img").attr("src","<?php echo COURSE_TEMPLATES_URL;?>/image/my_phone.png");

        });
        $(".my_phone").click(function(){
            $("#mineclass").css("display","none");
            $("#minephone").css("display","block");
            $(".my_class").removeClass("xuanzhong");
            $(".my_phone").addClass("xuanzhong");
            $(".my_class").children("img").attr("src","<?php echo COURSE_TEMPLATES_URL;?>/image/my_class.png");
            $(".my_phone").children("img").attr("src","<?php echo COURSE_TEMPLATES_URL;?>/image/my_phone1.png");
        });

        $(".kefu").click(function(){
            $("#kefu").css("display","block");
            $("#daoshi").css("display","none");
            $(".daoshi").removeClass("xz");
            $(".kefu").addClass("xz");
        });
        $(".daoshi").click(function(){
            $("#kefu").css("display","none");
            $("#daoshi").css("display","block");
            $(".kefu").removeClass("xz");
            $(".daoshi").addClass("xz");
        });
    });
</script>
