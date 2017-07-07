<style type="text/css">
.body-bg{position: fixed;width: 100%;height: 100%;background: #000000;opacity:0.5;top: 0;left: 0;z-index:100000;}
.spec-goods-dialog{width:700px;position:fixed;left:50%;margin-left:-350px;background:#FFF;top:10%;z-index:100001;border-radius:3px;}
.spec-goods-dialog .dialog-top{height:35px;line-height:35px;border-bottom:1px solid #CCC;padding-left:8px;font-size:13px;color:#000;position:relative;}
.spec-goods-dialog .dialog-top .moq-text{position:absolute;right:50px;font-size:12px;color:#999;}
.spec-goods-dialog .dialog-top .dialog-close{position:absolute;right:5px;color:#999;top: 3px;cursor: pointer;}
.spec-goods-dialog table{width:670px;border:0;border-collapse:collapse;border-spacing:0;line-height:0;table-layout: fixed;margin:0 15px 10px 15px;}
.spec-goods-dialog table td{text-align:center;}
.spec-goods-dialog table thead{border-bottom:2px solid #CCC;}
.spec-goods-dialog table thead tr{height:40px;font-size:14px;}
.spec-goods-dialog table tbody tr{border-top:1px solid #CCC;height:50px;}
.spec-goods-dialog table tbody tr.selected{background:#fff3df;}
.spec-goods-dialog table tbody tr:last-child{border-bottom:1px solid #CCC;}
.spec-goods-dialog table tbody .g-name{line-height:20px;}
.b-r-p{background:#333;width:30px;height:30px;color:#FFF;border:0px;  cursor: pointer;}
.b-r-p.reduce{background:#d7d7d7;}
.spec-goods-dialog .input_goods_moq{width:60px;text-align:center;  font: 12px/20px Tahoma; color: #777;background-color: #FFF;vertical-align: top;display: inline-block;height: 20px;padding: 4px;border: solid 1px #CCC;outline: 0 none;}
.spec-goods-dialog .dialog-footer{height:36px;font-size:13px;color:#999;padding-left:15px;}
.spec-goods-dialog .dialog-footer .count-num,.spec-goods-dialog .dialog-footer .count-price{color:#f34a9f;font-size:14px;}
.spec-goods-dialog #btn_add_to_cart,.spec-goods-dialog #btn_add_to_cart:hover{  text-decoration: initial;color:#FFF;padding:6px 60px;float:right;border-radius:3px;background:#f34a9f;margin-right:15px;}
.spec-goods-dialog .dialog-error{text-align:right;padding:0 15px 5px 0;color:#f34a9f;}
.spec-goods-dialog .common-name{padding:5px 0 5px 15px;color:#f34a9f;font-size:15px;}
</style>
<div style="display:none;" class="body-bg"></div>
<div style="display:none;" class="spec-goods-dialog">
   <div class="dialog-top">请选择商品规格及数量<span class="moq-text">该商品的混合起订量为<span id="goods_common_moq_num"></span>包</span><i onclick="closeAddCartDialog()" class="icon-remove icon-2x dialog-close"></i></div>
   <div class="common-name"></div>
   <div>
     <table>
       <thead>
         <tr><td style="width:30px;"><input type="checkbox" to="all" checked="checked"></td><td style="text-align:left;">规格</td><td style="width:80px;">价格</td><td style="width:80px;">库存量</td><td style="width:140px;">购买数量</td><td style="width:80px;">小计(元)</td></tr>
       </thead>
       <tbody>
       </tbody>
     </table>
   </div>
   <div class="dialog-footer">
     <span>该商品共计购买<span class="count-num"></span>种，合计<span class="count-price"></span>元</span></span>
     <a id="btn_add_to_cart" href="javascript:void(0)">加入进货单</a>
   </div>
   <div class="dialog-error"></div>
</div>
<script type="text/javascript">
    function showAddCartDialog(){
    	$(".body-bg").show();
    	var dialog=$(".spec-goods-dialog");
    	dialog.show();
    	var d_h=dialog.height(),w_h=$(window).height(),m_w_h=w_h*0.8;
    	if(d_h>=m_w_h){ 
    		var box=dialog.find("table").parent();
    		
    		box.height(m_w_h-115);
    		box.css("overflow-y","scroll");
    		dialog.css("top",((w_h-m_w_h)/2)+"px");
    		dialog.width(710);
        }else{
        	dialog.css("top",((w_h-d_h)/2)+"px");
        }
        $("body").css("overflow-y","hidden");
    }
    function closeAddCartDialog(){
    	$(".body-bg").hide();
    	var dialog=$(".spec-goods-dialog");
    	dialog.find("tbody").html("");
    	dialog.removeAttr("style");
    	dialog.find("table").parent().removeAttr("style");
    	dialog.hide();
    	$("body").removeAttr("style");
    }
    function addCartDialogCountPrice(){
    	var goods_count=0,all_price=0;
    	$(".spec-goods-dialog tbody tr").each(function(){
    		var tr=$(this);
    		var price=parseFloat(tr.attr("data_goods_price")),num=parseInt(tr.find(".input_goods_moq").val()),
    		row_price=price*num;
    		tr.find("td:last").text(row_price.toFixed(2));
    		if(tr.is(".selected")){goods_count++;all_price+=row_price;}
        });
        $(".spec-goods-dialog .dialog-footer .count-num").text(goods_count);
        $(".spec-goods-dialog .dialog-footer .count-price").text(all_price.toFixed(2));
    }
    /* 加入购物车 */
    function list_addcart(goods_id,quantity,commonid) {
        var url = 'index.php?act=cart&op=add';
        $.getJSON(url, {'goods_id':goods_id, 'quantity':quantity,"commonid":commonid}, function(data) {
            if (data != null) {
                if (data.state) {
                	closeAddCartDialog();
                    //animatenTop(current_add_cart_obj.offset().top, current_add_cart_obj.offset().left);
                    // 头部加载购物车信息
                    load_cart_information();
    				$('#rtoolbar_cartlist').load('index.php?act=cart&op=ajax_load&type=html');
    				if(data.text){
        				 alert(data.text);
        		    }
                } else {
                    $(".spec-goods-dialog .dialog-error").text(data.msg);
                    setTimeout(function(){
                 	   $(".spec-goods-dialog .dialog-error").html("");
                    },5000);
                }
            }
        });
    }
    //选择弹出框
    $(".spec-goods-dialog table").bind('click',function(e){
        e.stopPropagation();
        var target=$(e.target),table=$(this);
        if(target.is("input[type='checkbox']")){
            if(target.attr("to")=="all"){
                if(target.attr("checked")){
            	  table.find("tbody input[type='checkbox']").attr("checked",true);
            	  table.find("tbody tr").addClass("selected");
                }else{
              	  table.find("tbody input[type='checkbox']").attr("checked",false);
            	  table.find("tbody tr").removeClass("selected");
                }
            }else{
            	if(target.attr("checked")){
                  target.closest("tr").addClass("selected");
            	}else{
                  target.closest("tr").removeClass("selected");
                }
            }
            addCartDialogCountPrice();
        }else if(target.is(".b-r-p")){
    	   if(target.is(".reduce")){
    		   var num=parseInt(target.next().val()),min_num=parseInt(target.attr("min_value"));
    		   if(num<=min_num){
        		   return;
        	   }
    		   target.next().val(--num);
    		   addCartDialogCountPrice();
    	   }else{
    		   target.prev().val(parseInt(target.prev().val())+1);
    		   addCartDialogCountPrice();
    	   }
        }
    });
    $("#btn_add_to_cart").bind('click',function(){
        var goods_ids=[],quantitys=[],all_quantitys=0,check_quantitys=parseInt($(".spec-goods-dialog #goods_common_moq_num").text());
    	$(".spec-goods-dialog tbody tr.selected").each(function(){
        	var tr=$(this);
    		goods_ids.push(tr.attr("data_goods_id"));
    		var n_q=parseInt(tr.find(".input_goods_moq").val());
    		quantitys.push(n_q);
    		all_quantitys+=n_q;
        });
        if(goods_ids.length==0){
           $(".spec-goods-dialog .dialog-error").text("您未选择购买的规格");
           setTimeout(function(){
        	   $(".spec-goods-dialog .dialog-error").html("");
           },5000);
           return;
        }
        if(all_quantitys<check_quantitys){
            $(".spec-goods-dialog .dialog-error").text("您选中规格的起订量总和未达到此商品"+check_quantitys+"的混批量");
            setTimeout(function(){
         	   $(".spec-goods-dialog .dialog-error").html("");
            },5000);
            return; 
        }
        list_addcart(goods_ids.join(','),quantitys.join(','),$(".spec-goods-dialog").data("commonid"));
    });
    function show_goods_spec_dialog(commonid){
        $.getJSON('index.php?act=goods&op=ajax_goods_spec_list&commonid='+commonid,{commonid:commonid},function(data){
            var list_html="";
            $.each(data.spec_list,function(i,item){
            	list_html+="<tr class='selected' data_goods_id='"+item.goods_id+"'  data_goods_price='"+item.goods_price+"'>";
            	list_html+="<td><input type=\"checkbox\" checked=\"checked\"></td>";
            	list_html+="<td class=\"g-name\" style=\"text-align:left;\">"+(item.goods_spec_name||data.common_info.goods_name)+"</td>";
            	list_html+="<td>"+item.goods_price+"</td>";
            	list_html+="<td>"+item.goods_storage+"</td>";
            	list_html+="<td><button type=\"button\" min_value=\""+item.goods_moq+"\" class=\"b-r-p reduce\">-</button><input type=\"text\" class=\"input_goods_moq\"   value=\""+item.goods_moq+"\"/><button type=\"button\" class=\"b-r-p plus\">+</button></td>";
                list_html+="<td></td></tr>";
            });
            var dialog=$(".spec-goods-dialog");
            dialog.find("thead input[type='checkbox']").attr("checked",true);
            dialog.find("tbody").html(list_html);
            addCartDialogCountPrice();
            dialog.find("#goods_common_moq_num").text(data.common_info.goods_common_moq);
            dialog.find(".common-name").text(data.common_info.goods_name);
            showAddCartDialog();
            dialog.find(".input_goods_moq").blur(function(){
            	addCartDialogCountPrice();
            });
            dialog.data("commonid",commonid);
        });
    }
</script> 

