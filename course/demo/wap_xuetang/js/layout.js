/**
 * Created by Administrator on 16-1-8.
 */

$(document).ready(function(){
//收藏
    $(".bottom__fav-wrap").toggle(function(){
        $(".tip").show().delay(1000).hide(0);
        $(".store_shifo").html("收藏成功");
        $(".fav_img").attr("src","../images/storehou.png");
    },function(){
        $(".tip").show().delay(1000).hide(0);
        $(".store_shifo").html("取消收藏");
        $(".fav_img").attr("src","../images/store.png");
    });

// 购物车
    $(".bottom__cart-wrap").toggle(function(){
        $(".tip").show().delay(1000).hide(0);
        $(".store_shifo").html("添加购物车成功");
    },function(){
        $(".tip").show().delay(1000).hide(0);
        $(".store_shifo").html("取消购物车");
    });




//分享层
    $(".bottom__share-wrap").click(function(){
        $("#BgDiv").show();
        $("#shareDiv").show();
    });
    $("#BgDiv").click(function(){
        $("#BgDiv").hide();
        $("#shareDiv").hide();
        $("#DialogDiv").hide();
    });
    $(".share_qx").click(function(){
        $("#BgDiv").hide();
        $("#shareDiv").hide();
    });
//评论层
    $(".bottom__discuss-wrap").click(function(){
        $("#BgDiv").show();
        $("#DialogDiv").show();
    });
    $(".dialog-cancel-btn").click(function(){
        $("#BgDiv").hide();
        $("#DialogDiv").hide();
    });


});