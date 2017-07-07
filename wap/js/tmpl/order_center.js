

	function initPage(page,curpage){
		$.ajax({
			type:'post',
            url:ApiUrl+"/index.php?act=member_order&op="+op+"&page="+page+"&curpage="+curpage,
			data:{key:key},
			dataType:'json',
			success:function(result){
                checklogin(result.login);//检测是否登录了
                var data = result.datas;
                data.hasmore = result.hasmore;//是不是可以用下一页的功能，传到页面里去判断下一页是否可以用
                data.WapSiteUrl = WapSiteUrl;//页面地址
                data.curpage = curpage;//当前页，判断是否上一页的disabled是否显示
                data.ApiUrl = ApiUrl;
                data.page_total = result.page_total;
                data.key = getcookie('key');
                template.helper('$getLocalTime', function (nS) {
                    var d = new Date(parseInt(nS) * 1000);
                    var s = '';
                    s += d.getFullYear() + '-';
                    s += (d.getMonth() + 1) + '-';
                    s += d.getDate() + ' ';
                    if( d.getHours()<10){
                        s += '0'+d.getHours() + ':';
                    }else{
                        s += d.getHours() + ':';
                    }
                   if(d.getMinutes()<10){
                       s += "0"+d.getMinutes();
                   }else{
                       s += d.getMinutes();
                   }

                    return s;
                });
                template.helper('p2f', function(s) {
                    return (parseFloat(s) || 0).toFixed(2);
                });
                var html = template.render('record', data);
                $("#order_list").html(html);
                //显示全部商品图片
                $(".main-goods-img").click(show_goods);
                //再次购买
                //$(".purchase-again").click(purchaseAgain);
                //确认收货
                $(".receive-btn").click(sureOrder);
                //查看物流
                $(".view-delivery").click(viewOrderDelivery);
                //取消订单
                $(".cancel-btn").click(cancel_form_show);
                //下一页
                $(".next-page").click(nextPage);
                //上一页
                $(".pre-page").click(prePage);
                //退货退款
                $(".refund-btn").click(refund);
                $(window).scrollTop(0);
			}
		});

	}

    function refund(){
        var order_id = $(this).attr('order_id');
        location.href = WapSiteUrl+'/tmpl/member/refund_all.html?order_id='+order_id;
    }

    function aliPay(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();
        //ele.href 是GET到支付宝收银台的URL
        _AP.pay(e.target.href);
        return false;
    }
    //查看物流
    function viewOrderDelivery() {
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/order_delivery.html?order_id=' + orderId;
    }

    //显示商品图片
    function show_goods(){
        var list = $(this).closest('.order').find('.goods-list-warp');
        if(list.css('display')=='none'&&list.attr('show')=='show'){
            list.show();
        }else{
            list.hide();
        }
    }
    //再次购买
    function purchaseAgain(){
        var self = $(this);
        var goods_ids = self.attr('goods_ids');
        var goods_count = self.attr('goods_count');
        show_dialog('confirm','确定再次购买？',function(d){
            var yes = d.find('#dialog_btn_y');
            yes.unbind();
            yes.click(function(){
                purchase_again(goods_ids,goods_count);
            });
        });
    }
    function purchase_again(goods_ids,goods_count){
        $.ajax({
            url:ApiUrl+"/index.php?act=member_cart&op=cart_add",
            data:{key:key,goods_id:goods_ids,quantity:goods_count},
            type:"post",
            success:function (result){
                var rData = $.parseJSON(result);
                if(checklogin(rData.login)){
                    var msg = '已加入购物车！';
                    if(rData.datas.error){
                        msg = rData.datas.error;
                    }
                    show_dialog('alert',msg,function(d){
                        var ok = d.find('#dialog_btn_a');
                        ok.unbind();
                        ok.click(function(){
                            close_dialog();
                        });
                    });
                }
            }
        });
    }

    //删除订单
    function delOrder(){
        var order_id = $(this).attr('order_id');
        show_dialog('confirm','确认删除订单？',function(d){
            var yes = d.find('#dialog_btn_y');
            yes.unbind();
            yes.click(function(){
                del_order(order_id);
            });
        });
    }
    function del_order(order_id){
        $.ajax({
            type:"post",
            url:ApiUrl+"/index.php?act=member_order&op=order_del",
            data:{order_id:order_id,key:key},
            dataType:"json",
            success:function(result){
                if(!result.datas.error){
                    show_dialog('alert',result.datas.msg,function(d){
                        var ok = d.find('#dialog_btn_a');
                        ok.unbind();
                        ok.click(function(){
                            history.back();
                        });
                    });
                }else{
                    show_dialog('alert',result.datas.error,function(d){
                        var ok = d.find('#dialog_btn_a');
                        ok.unbind();
                        ok.click(function(){
                            close_dialog();
                        });
                    });
                }
            }
        });
    }
    //确认收货
    function sureOrder(){
        var order_id = $(this).attr("order_id");
        show_dialog('confirm','确认收货？',function(d){
            var yes = d.find('#dialog_btn_y');
            yes.unbind();
            yes.click(function(){
                sureOrderId(order_id);
            });
        });
    }
    function sureOrderId(order_id) {
        $.ajax({
            type:"post",
            url:ApiUrl+"/index.php?act=member_order&op=order_receive",
            data:{order_id:order_id,key:key},
            dataType:"json",
            success:function(result){
                if(result.datas && result.datas == 1){
                    close_dialog();
                    initPage(page,curpage);

                }
            }
        });
    }

    //取消订单
    $('input[type="radio"]').click(function(){
        if($(this).val()=='other'){
            $('#other').show();
        }else{
            $('#other').hide();
        }
    });
    $('#docancel').click(function(){
        cancel_order();
    });
    $('#close').click(function(){
        cancel_form_close();
    });
    function cancelOrderId(order_id,reason) {
        $.ajax({
            type:"post",
            url:ApiUrl+"/index.php?act=member_order&op=order_cancel",
            data:{order_id:order_id,key:getcookie('key'),reason:reason},
            dataType:"json",
            success:function(result){
                if(result.datas && result.datas == 1){
                    cancel_form_close();
                    initPage(page,curpage);
                }
            }
        });
    }
    function cancel_order(){
        var reason = $(":checked").val();
        if(reason=='other'){
            reason = $("#other").find('input').val();
        }
        if(!reason){
            $("#other").find('input').attr('placeholder','请输入原因...');
            return;
        }
        var order_id = $('#order_id').val();
        cancelOrderId(order_id,reason);
    }
    function cancel_form_close(){
        $('#modal').hide();
        $('#form').hide();
    }
    function cancel_form_show(){
        if($(":checked").val()=='other'){
            $('#other').show();
        }else{
            $('#other').hide();
        }
        $('#order_id').val($(this).attr("order_id"));
        if($(this).attr("order_sn")!=null){
            $('#order_sn').html("订单编号："+$(this).attr("order_sn"));
        }else{
            $('#order_sn').html("支付单号："+$(this).attr("pay_sn"));
        }

        $('#other').find('input').val('');
        $('#modal').show();
        $('#form').show();
    }

    //下一页
    function nextPage (){
        var self = $(this);
        var hasMore = self.attr("has_more");
        if(hasMore == "true"){
            curpage = curpage+1;
            initPage(page,curpage);
        }
    }
    //上一页
    function prePage (){
        var self = $(this);
        if(curpage >1){
            self.removeClass("disabled");
            curpage = curpage-1;
            initPage(page,curpage);
        }
    }

    //查看物流
    function viewOrderDelivery() {
        var orderId = $(this).attr('order_id');
        location.href = WapSiteUrl + '/tmpl/member/order_delivery.html?order_id=' + orderId;
    }