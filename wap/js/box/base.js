/**
 * Created by ygy on 16-1-22.
 */
function _form_show(){
    $('.modal-form-pop').show();
    $('.modal-form').show();
}
function _form_hide(){
    $('.modal-form-pop').hide();
    $('.modal-form').hide();
}
function _check_form(width,height){
    var load = $('body').find('.modal-form').length;
    if(!load){
        var html = '<div class="modal-form-pop"></div>';
        html += '<div class="modal-form">';
        html += '<div class="form-title-bar">';
        html += '<div class="title-content"></div>';
        html += '<div class="form-close">x</div>';
        html += '</div>';
        html += '<div class="form-body">';
        html += '<div class="form-body-content">';
        html += '</div>';
        html += '</div>';
        html += '</div>';
        $('body').append(html);
        $('.modal-form').find('.form-close').click(function(){
            _form_hide();
        });
    }
    $('.modal-form').draggable({handle:"div.form-title-bar"});
    $('.modal-form').css('width',width+'px');
    $('.modal-form').css('height',height+'px');
    $('.modal-form').find('.form-body-content').css('height',(height-30)+'px');
    $('.modal-form').css('margin-top','-'+(height/2)+'px');
    $('.modal-form').css('margin-left','-'+(width/2)+'px');
}
function load_url(url,title,width,height){
    _check_form(width,height);
    $('.modal-form').find('.title-content').html(title);
    _form_show();
    $('.modal-form').find('.form-body-content').html('<div class="loading">加载中...</div>');
    $('.modal-form').find('.form-body-content').load(url,function(){

    });

}
function load_dom(dom,title,width,height){
    _check_form(width,height);
    $('.modal-form').find('.title-content').html(title);
    _form_show();
    $('.modal-form').find('.form-body-content').html('<div class="loading">加载中...</div>');
    $('.modal-form').find('.form-body-content').html(dom);
}

//手机端提示框
function msg_box_mobile(msg, type, yes, no) {
    var id = 'modal-back';
    var modal_bg_class = "position: fixed;top: 0px;left: 0px;width: 100%;  height: 100%;background-color: #000;filter: alpha(opacity=65);opacity: 0.65;z-index: 20000000;display: none;";
    var modal_content_class = "position: fixed;background-color: #FFF;z-index: 30000000;max-width: 300px;width:80%;margin:0 auto 0;left:0;right:0;top:200px;display: none;border-radius: 5px;box-shadow:1px 1px 1px #dddddd";
    var head_class = "";
    var content_class = "padding: 15px;line-height: 25px;text-align:center";
    if ($('body').find('#' + id).length == 0) {
        $('body').append('<div id="modal-back" class="pop" style="' + modal_bg_class + '"></div>');
        $('body').append('<div id="' + id + '-content" class="box" style="' + modal_content_class + '">'
            + '<div class="head" style="' + head_class + '"></div>'
            + '<div class="content" style="' + content_class + '"></div>'
            + '<div style="width;100%;border-top: 1px solid #dddddd"></div>'
            + '<div class="op op-alert" style="text-align: center;cursor: pointer"><div class="ok" style="padding: 10px 0px;color: #bbbbbb">知道了</div></div>'
            + '<div class="op op-confirm"><div class="yes" style="padding: 10px 0px;width: 50%;text-align: center;display: inline-block;border-right: 1px solid #dddddd;box-sizing: border-box;cursor: pointer">确定</div><div class="no" style="padding: 10px 0px;width: 50%;text-align: center;display: inline-block;cursor: pointer">取消</div></div>'
            + '</div>');
        $('#' + id).click(function () {
            $('#' + id).hide(); $('#' + id + '-content').hide();
        });
        $('#' + id + '-content').find('.ok').click(function () {
            $('#' + id).hide(); $('#' + id + '-content').hide();
        });
    }
    var bak = $('#' + id);
    var content = $('#' + id + '-content');
    content.find('.op').hide();
    content.find('.op-' + type).show();
    content.find('.content').html(msg);
    if(type=='confirm'){
        $('#' + id + '-content').find('.yes').bind("click", function () {
            $('#' + id).hide(); $('#' + id + '-content').hide();
            if (typeof yes == 'function') {
                yes();
            }
        });
        $('#' + id + '-content').find('.no').bind("click", function () {
            $('#' + id).hide(); $('#' + id + '-content').hide();
            if (typeof no == 'function') {
                no();
            }
        });
    }else if(type=='alert'){
        if (typeof yes == 'function') {
            $('#' + id + '-content').find('.ok').bind("click", function () {
                yes();
            });
        }
    }

    bak.show(); content.show();
}
