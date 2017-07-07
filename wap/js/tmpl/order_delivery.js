$(function() {
    var key = getcookie('key');
    if (key=='') {
        window.location.href = WapSiteUrl + '/tmpl/member/login.html';
        return;
    }

    var order_id = GetQueryString("order_id");

    $.ajax({
        type: 'post',
        url: ApiUrl + "/index.php?act=member_order&op=search_deliver",
        data:{key:key,order_id:order_id},
        dataType:'json',
        success:function(result) {
            //检测是否登录了
            checklogin(result.login);
            result.datas.WapSiteUrl = WapSiteUrl;//页面地址
            var html = template.render('order-delivery-tmpl', result.datas);
            $("#order-delivery").html(html);
        }
    });

});
