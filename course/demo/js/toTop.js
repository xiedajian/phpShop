/**
 * Created by Administrator on 2015/10/30.
 */
function on(node, eventType, handler){
    node = typeof node == "string" ? document.getElementById(node) : node;
    if(document.all){
        node.attachEvent("on"+eventType,handler);
    }else{
        node.addEventListener(eventType,handler,false);
    }
}
//获得滚动条高度
function getScrollTop(){
    var sT = 0;
    if(document.documentElement && document.documentElement.scrollTop)
        sT = document.documentElement.scrollTop;
    else if(document.body && document.body.scrollTop)
        sT = document.body.scrollTop;
    else
        sT = 0;
    return sT;
}
//获得可见区域高度
function getClientHeight(){
    var cH = 0;
    if(document.documentElement && document.documentElement.clientHeight)
        cH = document.documentElement.clientHeight;
    else
        cH = document.body.clientHeight;
    return cH;
}
//获得可见区域高度
function getClientWidth(){
    var cW = 0;
    if(document.documentElement && document.documentElement.clientWidth)
        cW = document.documentElement.clientWidth;
    else
        cW = document.body.clientWidth;
    return cW;
}
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
on(window,"scroll",resetQQMsg);       //监听滚动事件
