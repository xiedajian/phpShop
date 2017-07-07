/**
 * Created by Administrator on 2015/10/22.
 */
var bannerImgWidth = 1920;
$(".layer").height($("body").height());
function getLeft(){
    var imgWidth = bannerImgWidth;//$("#gy_zw_banner img").width();
    var clientWidth= getClientWidth();
    var bannerLeft = -(imgWidth-clientWidth)/2;
    return bannerLeft;
}
var mySwiper;
var currentpage = 0;
var w = $(window).width();
var h = $(window).height();
/**
 * Created by webdesign on 2015/1/5.
 */
$(function(){
    var clientWidth= getClientWidth();
    $(".swiper-container").width(bannerImgWidth);
    var bannerLeft = -(bannerImgWidth-clientWidth)/2;
    if(bannerImgWidth < clientWidth)
        bannerLeft = 0;
    $(".swiper-container").css({"left":bannerLeft+"px"});

    //a鏍囩
    $(".pub_a a").hover(function(){
        $(this).css("color","#cd070a");
    },function(){
        $(this).css("color","#000000");
    });
    $(".play .play_item img").hover(function(){
        var index = $(".play img").index(this);
        $(this).attr("src","images/index_r4_"+(index+1)+"c.png");
//        $(this).css("transform","rotateY(360deg)");
    },function(){
        var index = $(".play img").index(this);
        $(this).attr("src","images/index_r4_"+(index+1)+".png");
//        $(this).css("transform","rotateY(0deg)");
    });
    //鐗堟湰涓€
    if($("#gy_zw_banner").length > 0) {
        mySwiper = new Swiper(".swiper-container",
            {  direction: "horizontal",//vertical horizontal
                speed: 500,
                effect:'slide',
                autoplay: 3000,
                queueEndCallbacks: true,
                resistance: true,
                releaseFormElements: true,
                simulateTouch: false,
                onSlideChangeEnd: function (e) {
                    var index = e.activeIndex;
                    if (index != currentpage) {
                        $("#banner_menu li").removeClass("li_check");
                        $("#banner_menu li:eq(" + index + ")").addClass("li_check");
                        currentpage = index;
                        //                    updateSize();
                    }
                }
            });
    }
    //鐗堟湰浜�
//    var cw = $(window).width();
//    if($("#gy_zw_banner").length > 0) {
//        var liIndex = -1;
//        $("#gy_zw_banner").cycle({
//            fx: 'scrollLeft',//blindX,curtainX,fadeZoom,growX,shuffle,slideX,fade,scrollLeft
//            timeout: 2000,
//            speed: 500,
//            width:cw,
//            after:changeLi,
//            aspect:true,
//            pause: true
//        });
//    }
//    function changeLi(){
//        liIndex++;
//        liIndex = liIndex >= 4 ? 0 : liIndex;
//        $("#banner_menu li").removeClass("li_check");
//        $("#banner_menu li:eq(" + liIndex + ")").addClass("li_check");
//    }
    //娴忚鍣ㄥぇ灏忕洃鍚�
    function updateSize()
    {
        if(parseInt($(".swiper-container").css("left")) < 0)
            $(".swiper-container").css({"left":"0px"});
        var imgWidth = bannerImgWidth;//$("#gy_zw_banner img").width();
        var clientWidth= getClientWidth();
        console.log(imgWidth+","+clientWidth);
        var bannerLeft = -(imgWidth-clientWidth)/2;
        $("#gy_zw_banner img").css({"left":bannerLeft+"px"});
    }
//    updateSize();
    $("#banner_menu li").bind("click",function(){
        var i = $("#banner_menu li").index(this);
        mySwiper.slideTo(i);
    });
    $("#fuCeng .a1").hover(function(){
        $(this).next(".a1_img").fadeIn();
    },function(){
        $(this).next(".a1_img").fadeOut();
    });
    $("#gy_index_menu li").hoverDelay(function(){
        $(this).siblings("li.li_hover").removeClass("li_hover");
        $(this).addClass("li_hover");
        if($(this).find('span').length > 0)return;
        var left = $(this).find("a").offset().left - $("#gy_index_menu").offset().left;
        var top =  $(this).find("a").offset().top+2-5;//- $("#gy_index_menu").offset().top;
        $("#gy_index_menu .under").css("width",$(this).find("a").width()+"px");
        $("#gy_index_menu .under").css("left",left+"px");
        $("#gy_index_menu .under").css("top",top+"px");
    },function(){
        $(this).removeClass("li_hover");
        if($(this).find('span').length > 0)return;
        $("#gy_index_menu .under").css("width","0px");
    });
    /*鍝佺墝鏈嶅姟*/
    $(".service .service_item").hoverDelay(function(){
        $(this).find(".img1").slideUp();
        $(this).find(".img2").slideDown();
    },function(){
        $(this).find(".img1").slideDown();
        $(this).find(".img2").slideUp();
    });
    var timer1;
    $("#gy_service_show_out .a").hover(function(){
        $(this).siblings(".a").removeClass("a_hover");
        $(this).addClass("a_hover");
        showByIndex($("#gy_service_show_out .a").index($(this)));
    });
    function showByIndex(index1){
        var imgs = $("#gy_service_show_out").next("#gy_service_content2").children(".detail_item");
        for(var i = 0; i < imgs.length; i++){
            if(imgs[i].className.indexOf("disNone") < 0)
            {
                $(imgs[i]).addClass("disNone");
                break;
            }
        }
        $(imgs[index1]).removeClass("disNone");
    }
    $("#gy_service_show_out2 .a").hover(function(){
        $(this).siblings(".a").removeClass("a_hover");
        $(this).addClass("a_hover");
        showByIndex2($("#gy_service_show_out2 .a").index($(this)));
    });
    function showByIndex2(index1){
        var imgs = $("#gy_service_show_out2").next("#gy_service_content3").children(".detail_item");
        for(var i = 0; i < imgs.length; i++){
            if(imgs[i].className.indexOf("disNone") < 0)
            {
                $(imgs[i]).addClass("disNone");
                break;
            }
        }
        $(imgs[index1]).removeClass("disNone");
    }
    $(".layer").height($("body").height());
    on(window,"resize",updateSize);
    $("#toTop a").click(function(){
        $("html, body").animate({ scrollTop: 0 }, 320);
    });
    var resetQQMsg = function(){
        var scrollTop = getScrollTop()+182;
        if(scrollTop > 200)
        {
            $("#toTop a").css({"visibility":"visible"});
        }else{
            $("#toTop a").css({"visibility":"hidden"});
        }
        var toTopTop = getScrollTop() + getClientHeight() - 60;
        var isIE=!!window.ActiveXObject;
        var isIE6=isIE&&!window.XMLHttpRequest;
        if(isIE && isIE6){
            $("#toTop").css("top",toTopTop+"px");
        }
    }
    resetQQMsg();
    on(window,"scroll",resetQQMsg);       //鐩戝惉婊氬姩浜嬩欢
})
