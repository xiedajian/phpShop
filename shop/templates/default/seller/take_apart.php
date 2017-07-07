<?php defined('InShopNC') or exit('Access Invalid!');?>
<style>
    ul li{display: inline-block}
    ul{height: 100% !important;}
    .show-dialog{
        position:fixed;
        top:50%;
        left:50%;
        z-index:1100;
        padding: 10px;
        background-color: #ffffff;
        border-radius: 5px;
        border: 1px solid #dddddd;
    }
    .tip{color: red}
    .address-error{color: red !important;background-color: #BBBBBB !important;}

    .order-info{background-color: #DBF8FF;border: 1px solid #dddddd;line-height: 28px;}
    .order-info span{margin-left: 50px;}
    .order_goods_container{padding: 10px;border: 1px solid #dddddd;border-top: none;max-height: 200px;min-height: 200px;height:200px;overflow: auto}

    .goods{margin-right: 10px;border: 1px solid #dddddd;width: 80px;padding: 10px;}
    .goods-image{width: 80px;height: 80px;}
    .goods-num{color: red}
    .toolBar{text-align: right;border: 1px solid #dddddd;border-top: none;line-height: 25px;padding: 10px 10px 10px 0px;}
    .add-sub-order{background-color: #FF7C5B;border-radius: 5px;color: #ffffff;text-align: center;display: inline-block;width: 100px;cursor: pointer}

    .default-sub-order{display: none !important;}
    .step2{margin-top: 260px;padding-top: 20px;}
    .sub-order-container{border: 1px solid #dddddd;z-index: 90;padding: 20px 0px;vertical-align: top}
    .sub-order{width: 46%;display: inline-block;margin-left: 2%;margin-bottom: 20px;vertical-align: top}
    .sub-order-title{background-color: #DBF8FF;padding: 5px 10px;border: 1px solid #dddddd;}
    .remove-sub-order{display: inline-block;width: 20px;height: 20px;line-height: 20px;text-align: center;float: right;cursor: pointer}
    .sub-order-goods{height:180px;border-left: 1px solid #dddddd;border-right: 1px solid #dddddd;overflow: scroll}
    .sub-toolBar{background-color: #009900;height: 30px;}
    .sub-toolBar div{display: inline-block;color: #ffffff;text-align: center;width: 49%;padding: 5px 0px;cursor: pointer;background-color: #009900;}


    .back-btn{display: inline-block;margin-right: 20px;width: 150px;text-align: center;border: 1px solid #dddddd;padding: 5px;cursor: pointer}
    .submit-btn{display: inline-block;color: #ffffff;background-color: #0099FF;width: 150px;text-align: center;padding: 5px;cursor: pointer}

    .address-info{display: none}
    .delivery-info{display: none}
    .address-info table{width: 100%; }
    .address-info .short{width: 80px;}
    .address-info .title{padding: 10px 0px;font-size: 14px;background-color: #FAFAFA;margin-top: 10px;}
    .address-info input{margin: 3px;width: 100%;text-indent: 10px;padding: 4px 0px;}
    .address-info select{margin: 3px;max-width: 80px;}
    .address-info table tr td{width: 20%;}
    .address-info table tr td:first-child{min-width: 80px;}
    .address-info table tr td:last-child{width: 100px;}

    .delivery-info table{width: 100%; }
    .delivery-info .title{padding: 10px 0px;font-size: 14px; background-color: #FAFAFA;margin-top: 10px;}
    .delivery-info input{margin: 3px;width: 100%;text-indent: 10px;padding: 4px 0px;}
    .delivery-info select{margin: 3px;}
    .delivery-info table tr td:first-child{width: 80px;}
    .area-edit{padding: 4px 8px;background-color:  #dddddd;border-radius: 5px;margin-left: 10px;cursor: pointer}
</style>
<form method="post" id="form" action="index.php?act=store_deliver&op=take_apart&order_id=<?php echo $output['order_info']['order_id'];?>">
    <input type="hidden" name="form_submit" value="ok">
<div class="tabmenu" >
        <div class="pngFix" style="background-color: #ffffff;z-index: 100">
            <div>
            <div class="order-info">
                <span style="margin-left: 20px;">订单编号：</span><?php echo $output['order_info']['order_sn'];?>
                <span>下单时间：</span><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']);?>
                <span>买家：</span><?php echo $output['order_info']['buyer_name'];?>
            </div>
            <div class="order-info" style="text-align: center;border-top: none">
                待拆单商品
            </div>
            <div class="order_goods_container">
                <input type="hidden" name="refund_order" class="goods-id-num">
                <ul drop_id="1">
                    <?php if($output['order_info']['extend_order_goods']){foreach($output['order_info']['extend_order_goods'] as $goods){?>
                        <li>
                            <div class="goods" num="<?php echo $goods['goods_num'];?>" goods_id="<?php echo $goods['goods_id'];?>">
                                <img src="<?php echo $goods['image_240_url'];?>" class="goods-image">
                                <p><?php echo $goods['goods_name'];?><span class="goods-num">X<?php echo $goods['goods_num'];?></span></p>
                            </div>
                        </li>
                    <?php }}?>
                </ul>
            </div>
            <div class="toolBar">
                <input type="checkbox" id="default_refund" name="default_refund"><label for="default_refund">&nbsp;&nbsp;剩余商品退款处理</label>
                <span class="add-sub-order">添加子单</span>
            </div>
        </div>
        </div>
    </div>

<div class="step2">
    <div class="sub-order-container">

    </div>
</div>
</form>


<div style="margin-top: 20px;text-align: center;">
    <div class="back-btn" onclick="history.back()">返回</div>
    <div class="submit-btn">提交</div>
</div>


<div class="sub-order default-sub-order">
    <div class="sub-order-title">
        <span class="sub-order-name">订单1</span>
        <span class="remove-sub-order">X</span>
    </div>
    <div class="sub-order-goods">
        <input type="hidden" name="sub_order[]" class="goods-id-num">
        <ul>

        </ul>
    </div>
    <div class="sub-toolBar">
        <div style="border-right: 1px solid #ffffff" class="set-address address-error">设置身份及收货信息</div>
        <div class="set-delivery">设置物流信息</div>
    </div>
    <div class="address-info">
        <div class="title">设置身份及收货信息</div>
        <table>

            <tr>
                <td>身份信息</td>
                <td><input class="short" name="buyer_name[]" placeholder="买家姓名"></td>
                <td colspan="2"><input name="buyer_id_card[]" placeholder="买家身份证"></td>
                <td><span class="tip"></span></td>
            </tr>
            <tr>
                <td>收货信息</td>
                <td><input class="short" name="rec_name[]" placeholder="收货人" value="<?php echo $output['order_info']['extend_order_common']['reciver_name'];?>"></td>
                <td colspan="2"><input name="rec_mobile[]" placeholder="收货人手机号" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['phone'];?>"></td>
                <td><span class="tip"></span></td>
            </tr>
            <tr>
                <td>所在地区</td>
                <td colspan="3">
                    <input type="hidden" name="area[]">
                    <div id="region">
                        <select></select>
                    </div>
                </td>
                <td><span class="tip"></span></td>
            </tr>
            <tr>
                <td>街道地址</td>
                <td colspan="3"><input name="addr[]" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['street'];?>"></td>
                <td><span class="tip"></span></td>
            </tr>
            <tr>
                <td>邮编</td>
                <td><input name="postcode[]" value="<?php echo $output['order_info']['extend_order_common']['reciver_info']['postcode'];?>"></td>
                <td><span class="tip"></span></td>
            </tr>
            <tr>
                <td>备注</td>
                <td colspan="3"><input name="remark[]"></td>
                <td><span class="tip"></span></td>
            </tr>
        </table>
    </div>
    <div class="delivery-info">
        <div class="title">设置物流信息</div>
        <table>
            <tr>
                <td>供应商</td>
                <td colspan="2"><input name="seller_name[]"></td>
                <td></td>
                <td><span class="tip"></span></td>
            </tr>
            <tr>
                <td>外部订单号</td>
                <td colspan="2"><input name="other_sn[]"></td>
                <td></td>
                <td><span class="tip"></span></td>
            </tr>
            <tr>
                <td>物流公司</td>
                <td>
                    <select name="express_id[]">
                        <?php if($output['my_express_list']){foreach($output['my_express_list'] as $k=>$v){?>
                            <?php if($output['express_list'][$v]['e_name']=='中通快递'){?>
                                <option value="<?php echo $v;?>"><?php echo $output['express_list'][$v]['e_name'];?></option>
                            <?php unset($output['my_express_list'][$k]);break;}?>
                        <?php }}?>
                        <?php if($output['my_express_list']){foreach($output['my_express_list'] as $v){?>
                            <option value="<?php echo $v;?>"><?php echo $output['express_list'][$v]['e_name'];?></option>
                        <?php }}?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>物流单号</td>
                <td><input name="shipping_code[]"></td>
            </tr>
        </table>
    </div>
</div>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script>

var sub_order_id = 1;
function check_address(sub_order){
    //地区
    var area = sub_order.find("#region");
    var p_id = Number(area.find('select').eq(0).val());
    var c_id = Number(area.find('select').eq(1).val());
    var q_id = Number(area.find('select').eq(2).val());
    if(!isNaN(p_id)&&!isNaN(c_id)&&!isNaN(q_id)){
        sub_order.find("input[name='area[]']").val(p_id+','+c_id+','+q_id);area.closest('tr').find('.tip').hide();
    }else{area.closest('tr').find('.tip').html('请选择地区').show();return false;}
    return true;
    var seller_name = sub_order.find("input[name='seller_name[]']");
    if(!seller_name.val()){seller_name.closest('tr').find('.tip').html('请输入供应商名称').show();return false;}else{seller_name.closest('tr').find('.tip').hide();}

    var other_sn = sub_order.find("input[name='other_sn[]']");
    if(!other_sn.val()){other_sn.closest('tr').find('.tip').html('请输入外部单号').show();return false;}else{other_sn.closest('tr').find('.tip').hide();}

    var buyer_name = sub_order.find("input[name='buyer_name[]']");
    if(!buyer_name.val()){buyer_name.closest('tr').find('.tip').html('请输入买家姓名').show();return false;}else{buyer_name.closest('tr').find('.tip').hide();}

    var buyer_id_card = sub_order.find("input[name='buyer_id_card[]']");
    if(!buyer_id_card.val()){buyer_id_card.closest('tr').find('.tip').html('请输入买家身份证号').show();return false;}else{buyer_id_card.closest('tr').find('.tip').hide();}

    var rec_name = sub_order.find("input[name='rec_name[]']");
    if(!rec_name.val()){rec_name.closest('tr').find('.tip').html('请输入收货人姓名').show();return false;}else{rec_name.closest('tr').find('.tip').hide();}

    var rec_mobile = sub_order.find("input[name='rec_mobile[]']");
    if(!rec_mobile.val()){rec_mobile.closest('tr').find('.tip').html('请输入收货人手机号').show();return false;}else{rec_mobile.closest('tr').find('.tip').hide();}
    //地区
    var area = sub_order.find("#region");
    var p_id = Number(area.find('select').eq(0).val());
    var c_id = Number(area.find('select').eq(1).val());
    var q_id = Number(area.find('select').eq(2).val());
    if(!isNaN(p_id)&&!isNaN(c_id)&&!isNaN(q_id)){
        sub_order.find("input[name='area[]']").val(p_id+','+c_id+','+q_id);area.closest('tr').find('.tip').hide();
    }else{area.closest('tr').find('.tip').html('请选择地区').show();return false;}

    var addr = sub_order.find("input[name='addr[]']");
    if(!addr.val()){addr.closest('tr').find('.tip').html('请输入街道地址').show();return false;}else{addr.closest('tr').find('.tip').hide();}
    return true;
}

$(function(){
    regionInit("region");

    //添加子单
    $('.add-sub-order').click(function(){
        var sub = $('.default-sub-order').clone(true);
        sub.removeClass('default-sub-order');
        sub.find('.sub-order-name').html('订单'+sub_order_id++);
        sub.find('ul').attr('drop_id',sub_order_id);
        $('.sub-order-container').append(sub);
        $('.sub-order-container').find('ul').droppable({
            accept: "li",
            scroll:true,
            drop: function( event, ui ) {
                var origin = ui.draggable.parent();//源
                if(origin.attr('drop_id')==$(this).attr('drop_id'))return;
                var goods_num = ui.draggable.find('.goods').attr('num');
                var goods_id = ui.draggable.find('.goods').attr('goods_id');
                var num = 1;
                if(goods_num>=10){
                    var text = prompt('选择移动商品数量（最大'+goods_num+'个）',goods_num);
                    num = Number(text);
                    if(isNaN(num))return;
                    if(num<0)return;
                    if(num>goods_num)return;
                }
                if(num<goods_num){
                    var target= ui.draggable.clone();//移动剩余对象
                    target.draggable({
                        revert: "invalid",
                        containment: "document",
                        helper: "clone",
                        cursor: "move",
                        scroll:true
                    });
                    target.find('.goods').attr('num',goods_num-num);
                    target.find('.goods-num').html('X'+(goods_num-num));
                    origin.append(target);
                    ui.draggable.find('.goods').attr('num',num);
                    ui.draggable.find('.goods-num').html('X'+(num));
                }
                var same = $(this).find("div[goods_id='"+goods_id+"']");
                if(same.length>0){
                    var n =same.attr('num');
                    num=Number(n)+Number(num);
                    ui.draggable.find('.goods').attr('num',num);
                    ui.draggable.find('.goods-num').html('X'+(num));
                    same.parent().remove();
                }
                $(this).append(ui.draggable);
            }
        });
    });
    //删除子单
    $('.remove-sub-order').click(function(){
       var sub_order = $(this).closest('.sub-order');
       var title = sub_order.find('.sub-order-name').html();
       if(confirm('是否删除'+title+'?')){
           sub_order.find('.goods').each(function(){
              var goods_id = $(this).attr('goods_id');
              var goods_num = $(this).attr('num');
               var exist= $('.order_goods_container').find("div[goods_id='"+goods_id+"']");
               if(exist.length>0){
                   var n = Number(exist.attr('num'))+Number(goods_num);
                   exist.attr('num',n);
                   exist.find('.goods-num').html('X'+n);
               }else{
                   $(this).parent().appendTo($('.order_goods_container').find('ul'));
               }
           });
           sub_order.remove();
       }
    });
    //设置身份及收货信息
    $('.set-address').click(function(){
        var sub_order = $(this).closest('.sub-order');
        var dialog = sub_order.find('.address-info');
        if(dialog.css('display')=='none'){
            dialog.show();
        }else{
            if(check_address(sub_order)){
                dialog.find('.tip').hide();

                $(this).removeClass('address-error');
            }else{
                $(this).addClass('address-error');
            }
            dialog.hide();
        }
    });
    //设置物流
    $('.set-delivery').click(function(){
        var sub_order = $(this).closest('.sub-order');
        var dialog = sub_order.find('.delivery-info');
        if(dialog.css('display')=='none'){
            dialog.show();
        }else{
            dialog.hide();
        }
    });
    //待拆商品拖动
    $(".order_goods_container>ul>li").draggable({
        revert: "invalid",
        containment: "document",
        helper: "clone",
        cursor: "move"
    });
    $( ".order_goods_container>ul" ).droppable({
        accept: "li",
        drop: function( event, ui ) {
            var origin = ui.draggable.parent();//源
            if(origin.attr('drop_id')==$(this).attr('drop_id'))return;
            var goods_num = ui.draggable.find('.goods').attr('num');
            var goods_id = ui.draggable.find('.goods').attr('goods_id');
            var num = 1;
            if(goods_num>=10){
                var text = prompt('选择移动商品数量（最大'+goods_num+'个）',goods_num);
                num = Number(text);
                if(isNaN(num))return;
                if(num<0)return;
                if(num>goods_num)return;
            }
            if(num<goods_num){
                var target= ui.draggable.clone();//移动剩余对象
                target.draggable({
                    revert: "invalid",
                    containment: "document",
                    helper: "clone",
                    cursor: "move",
                    scroll:true
                });
                target.find('.goods').attr('num',goods_num-num);
                target.find('.goods-num').html('X'+(goods_num-num));
                origin.append(target);
                ui.draggable.find('.goods').attr('num',num);
                ui.draggable.find('.goods-num').html('X'+(num));
            }
            var same = $(this).find("div[goods_id='"+goods_id+"']");
            if(same.length>0){
                var n =same.attr('num');
                num=Number(n)+Number(num);
                ui.draggable.find('.goods').attr('num',num);
                ui.draggable.find('.goods-num').html('X'+(num));
                same.parent().remove();
            }
            $(this).append(ui.draggable);
        }

    });
    //提交拆单
    $('.submit-btn').click(function(){
        //更新商品分配
        $(".goods-id-num").each(function(){
            var id_num = [];
           $(this).parent().find('li').each(function(){
               var num = $(this).find('.goods').attr('num');
               var goods_id = $(this).find('.goods').attr('goods_id');
               id_num.push(goods_id+'='+num);
           });
            var val = id_num.join(',');
            if(!val){$(this).closest('.sub-order').not('.default-sub-order').remove();}else{
                $(this).val(val);
            }
        });
        if($(".sub-order-container").find('.address-error').length>0){
            if(!confirm('子单收货信息及买家身份未设置,是否继续？'))
                return;
        }
        if($(".order_goods_container").find('.goods').length>0){
            if($("#default_refund:checked").val()!='on'){
                alert('请勾选剩余商品退款处理，或继续拆单！');return;
            }
        }
        ajaxpost('form', '', '', 'onerror');
    });
});

</script>