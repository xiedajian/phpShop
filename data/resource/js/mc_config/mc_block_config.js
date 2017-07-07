/**
 * Created by Administrator on 2016/1/14 0014.
 */
function select_materials(id) {//商品选择
    var obj = $("ul.dialog-goodslist-s1");
    if(obj.find("img[select_meterials_id]").size()>=8) return;
    if(obj.find("img[select_meterials_id='"+id+"']").size()>0) return;//避免重复
    var goods = $("#show_materials_list img[select_meterials_id='"+id+"']");
    var text_append = '';
    var goods_pic = goods.attr("src");
    var goods_name = goods.attr("title");
    text_append += '<div ondblclick="del_materials('+id+');" class="goods-pic">';
    text_append += '<span class="ac-ico" onclick="del_materials('+id+');"></span>';
    text_append += '<span class="thumb size-200x124">';
    text_append += '<i></i>';
    text_append += '<img select_meterials_id="'+id+'" title="'+goods_name+'" src="'+goods_pic+'" onload="javascript:DrawImage(this,200,124);" />';
    text_append += '</span></div>';
    text_append += '<div class="materials-name">';
//        text_append += '<a href="'+SHOP_SITE_URL+'/index.php?act=goods&goods_id='+goods_id+'" target="_blank">';
        text_append += goods_name;
    text_append += '</div>';
    text_append += '<input name="recommend_goods_id[]" value="'+id+'" type="hidden">';
    text_append += '<input name="recommend_goods_name[]" value="'+goods_name+'" type="hidden">';
    text_append += '<input name="recommend_goods_pic[]" value="'+goods_pic+'" type="hidden">';
    $("ul.dialog-goodslist-s1").append('<li id="select_recommend_'+id+'_goods_'+id+'">'+text_append+'</li>');
}
function del_materials(id) {//删除已选商品
    var obj = $("ul.dialog-goodslist-s1");
    obj.find("img[select_meterials_id='"+id+"']").parent().parent().parent().remove();
}

