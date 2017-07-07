
function show_dialog(content,btn,ref){
    $('#content').html(content);
    $('#click').html(btn);
    $('#click').unbind('click');
    $('#click').click(function(){
        $('.popup').hide();
        $('.pop').hide();
        if(ref){
            location.href = ref;
        }
    });
    $('.popup').show();
    $('.pop').show();
}

$(function(){

    var query = location.search;
    /ref=(.*)&?/.test(location.search);
    var ref =RegExp.$1;
    var wxinfo = getcookie('wx_bind');
    var username = getcookie('username');
    var token =  getcookie('key');
    delCookie('wx_bind');
    if(!wxinfo){
        location.href = ref;
    }
    $.ajax({
        type:'get',
        url:ApiUrl + "/index.php?act=login&op=wxbind&wxinfo="+wxinfo,
        dataType:'json',
        success:function(result){
            if(!result.datas.error){
                result.datas['username'] = username;
                var html = template.render('bind_info', result.datas);
                $('#main_panel').html(html);

                $('#do_bind').click(function(){
                    $.ajax({
                        type:'post',
                        url:ApiUrl+"/index.php?act=login&op=wxbind",
                        data:{wxinfo:wxinfo,token:token,form_submit:'ok'},
                        dataType:'json',
                        success:function(r){
                            if(!r.datas.error){
                                show_dialog('您的账号已绑定成功','知道了',ref);

                            }else{
                                show_dialog(r.datas.error,'确定','');
                            }
                        }
                    });
                });
                $('#not_bind').click(function(){
                    location.href = ref;
                });
            }else{
                location.href = ref;
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            $.ajax({
                type:'get',
                url:ApiUrl + "/index.php?act=login&op=wxbind&wxinfo="+wxinfo,
                dataType:'json',
                success:function(result){
                    if(!result.datas.error){
                        result.datas['username'] = username;
                        var html = template.render('bind_info', result.datas);
                        $('#main_panel').html(html);

                        $('#do_bind').click(function(){
                            $.ajax({
                                type:'post',
                                url:ApiUrl+"/index.php?act=login&op=wxbind",
                                data:{wxinfo:wxinfo,token:token,form_submit:'ok'},
                                dataType:'json',
                                success:function(r){
                                    if(!r.datas.error){
                                        show_dialog('您的账号已绑定成功','知道了',ref);
                                    }else{
                                        show_dialog(r.datas.error,'确定','');
                                    }
                                }
                            });
                        });
                        $('#not_bind').click(function(){
                            location.href = ref;
                        });
                    }else{
                        location.href = ref;
                    }
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    location.href = ref;
                }
            });
        }
    });




});


