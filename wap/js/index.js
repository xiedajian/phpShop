
function show_ad(){

    //delCookie('show_ad');

    var show_ad_times = getcookie('wap_show_ad');
    if(!show_ad_times){
        addcookie('wap_show_ad',1,0.5);
        $('.popup').show();
        $('.ad').show();
    }

}

$(function() {
    var islogin=0;
    $.ajax({
        url: ApiUrl + "/index.php?act=index&key="+getcookie('key'),
        type: 'get',
        dataType: 'json',
        success: function(result) {
            var data = result.datas;
            var html = '';

            $.each(data, function(k, v) {
                $.each(v, function(kk, vv) {
                    if(kk=='islogin'){
                        if(vv>=1){//已登录
                           islogin = vv;
                           var obj=$(".header-login");
                           obj.addClass("ipvp-icon");
                           obj.addClass("header-logined");
                           obj.removeClass("header-login");
                           obj.attr("href",WapSiteUrl+"/tmpl/member/member.html?act=member");
                        }else{
                           $(".header-login").text("登录");
                        }
                        return false;
                    }
                    if(vv.title){
                        if(/(.+)(（.+）)/.test(vv.title)){
                           vv.title_0=RegExp.$1;
                           vv.title_1=RegExp.$2;
                        }else{
                           vv.title_0=vv.title;
                           vv.title_1="";
                        }
                    }
                    switch (kk) {
                        case 'adv_list':
                           $.each(vv.item, function(i, v0) {
                               vv.item[i].url = buildUrl(v0.type, v0.data);
                            });
                            break;
                        case 'xianshi':
                            $.each(vv.item, function(i, v1) {
                                vv.item[i].image_url = v1.image_url.replace("_60","_240");
                                vv.item[i].goods_url = WapSiteUrl+"/tmpl/product_detail.html?goods_id="+v1.goods_id;
                            });
                        case 'home3':
                            $.each(vv.item, function(k3, v3) {
                                vv.item[k3].url = buildUrl(v3.type, v3.data);
                            });
                            break;

                        case 'home1':
                            vv.url = buildUrl(vv.type, vv.data);
                            break;

                        case 'home2':
                        case 'home4':
                            vv.square_url = buildUrl(vv.square_type, vv.square_data);
                            vv.rectangle1_url = buildUrl(vv.rectangle1_type, vv.rectangle1_data);
                            vv.rectangle2_url = buildUrl(vv.rectangle2_type, vv.rectangle2_data);
                            break;
                    }
                    vv['islogin'] = islogin;
                    html += template.render(kk, vv);
                    return false;
                });
            });

            $("#main-container").html(html);

            $('.adv_list').each(function() {
            	var count=$(this).find('.item').length;
                if (count< 2) {
                    return;
                }
                var now_i=Math.round(Math.random()* (count-1));
                $('.adv_list_circle i:eq('+now_i+')').addClass("in");
                Swipe(this, {
                    startSlide:now_i ,
                    speed: 400,
                    auto: 3000,
                    continuous: true,
                    disableScroll: false,
                    stopPropagation: false,
                    callback: function(index, elem) {
                        $('.adv_list_circle .in').removeClass("in");
                        $('.adv_list_circle i:eq('+index+')').addClass("in");
                    },
                    transitionEnd: function(index, elem) {}
                });
            });
            $(".xianshi-time").each(function(){
                var _this=$(this);
                setInterval(function(){
                    var time_down=parseInt(_this.attr("xianshi_time_down"));
                    time_down--;
                    _this.attr("xianshi_time_down",time_down);
                    var hour=parseInt(time_down/3600);
                    hour=hour>99?99:hour;
                    var minutes=parseInt((time_down%3600)/60),second=(time_down%3600)%60;
                    var time_str=hour>9?"<span>"+parseInt(hour/10)+"</span>"+"<span>"+(hour%10)+"</span>":"<span>0</span><span>"+hour+"</span>";
                    time_str+=":";
                    time_str+=minutes>9?"<span>"+parseInt(minutes/10)+"</span>"+"<span>"+(minutes%10)+"</span>":"<span>0</span><span>"+minutes+"</span>";
                    time_str+=":";
                    time_str+=second>9?"<span>"+parseInt(second/10)+"</span>"+"<span>"+(second%10)+"</span>":"<span>0</span><span>"+second+"</span>";
                    _this.html(time_str);
                },1000);
            });

            //show_ad();
        }
    });
    // window.header_bg_ischange=false;
    // $(document).scroll(function() { 
    //     var s_top=$(this).scrollTop();
    //     if(s_top>150&&header_bg_ischange===false){
    //           window.header_bg_ischange=true;
    //          $(".header-top").css('background','#E1017E')
    //     }else if(s_top<150&&header_bg_ischange){
    //           header_bg_ischange=false;
    //          $(".header-top").removeAttr("style");
    //     } 
    // } );
    $('.header-login').click(function(){
        location.href=WapSiteUrl+'/tmpl/member/login.html';
    });
    $(".header-menu").click(function(){
        location.href=WapSiteUrl+'/tmpl/product_first_categroy.html';
    });
    //$('.header-search-icon').click(function(){
    //  var keyword = encodeURIComponent($('#keyword').val());
    //  location.href = WapSiteUrl+'/tmpl/product_list.html?keyword='+keyword;
    //});



});