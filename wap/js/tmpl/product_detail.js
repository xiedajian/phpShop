$(function (){
    var unixTimeToDateString = function(ts, ex) {
        ts = parseFloat(ts) || 0;
        if (ts < 1) {
            return '';
        }
        var d = new Date();
        d.setTime(ts * 1e3);
        var s = '' + d.getFullYear() + '-' + (1 + d.getMonth()) + '-' + d.getDate();
        if (ex) {
            s += ' ' + d.getHours() + ':' + d.getMinutes() + ':' + d.getSeconds();
        }
        return s;
    };

    var buyLimitation = function(a, b) {
        a = parseInt(a) || 0;
        b = parseInt(b) || 0;
        var r = 0;
        if (a > 0) {
            r = a;
        }
        if (b > 0 && r > 0 && b < r) {
            r = b;
        }
        return r;
    };

    template.helper('isEmpty', function(o) {
        for (var i in o) {
            return false;
        }
        return true;
    });

     // 图片轮播
    function picSwipe(){
      var elem = $("#mySwipe")[0];
      window.mySwipe = Swipe(elem, {
        continuous: true,
        // disableScroll: true,
        stopPropagation: true,
        callback: function(index, element) {
          $(".pds-cursize").html(index+1);
        }
      });
    }
    var goods_id = GetQueryString("goods_id");
    //渲染页面
    $.ajax({
       url:ApiUrl+"/index.php?act=goods&op=goods_detail",
       type:"get",
       data:{goods_id:goods_id,key:getcookie('key')},
       dataType:"json",
       success:function(result){

          var data = result.datas;
          data.ApiUrl = ApiUrl;
          data.WapSiteUrl = WapSiteUrl;
          data.key = getcookie('key');
           data.url = location.href;
          if(!data.error){
            //修改title
            $('title').text(data.goods_info.goods_name);
            //商品图片格式化数据
            if(data.goods_image){
              var goods_image = data.goods_image.split(",");
              data.goods_image = goods_image;
            }else{
               data.goods_image = [];
            }
            //商品规格格式化数据
            if(data.goods_info.spec_name){
              var goods_map_spec = $.map(data.goods_info.spec_name,function (v,i){
                var goods_specs = {};
                goods_specs["goods_spec_id"] = i;
                goods_specs['goods_spec_name']=v;
                if(data.goods_info.spec_value){
	                $.map(data.goods_info.spec_value,function(vv,vi){
	                    if(i == vi){
	                      goods_specs['goods_spec_value'] = $.map(vv,function (vvv,vvi){
	                        var specs_value = {};
	                        specs_value["specs_value_id"] = vvi;
	                        specs_value["specs_value_name"] = vvv;
	                        return specs_value;
	                      });
	                    }
	                  });
	                  return goods_specs;
                }else{
                	data.goods_info.spec_value = [];
                }
              });
              data.goods_map_spec = goods_map_spec;
            }else {
              data.goods_map_spec = [];
            }
            // 虚拟商品限购时间和数量
            if (data.goods_info.is_virtual == '1') {
                data.goods_info.virtual_indate_str = unixTimeToDateString(data.goods_info.virtual_indate, true);
                data.goods_info.buyLimitation = buyLimitation(data.goods_info.virtual_limit, data.goods_info.upper_limit);
            }

            // 预售发货时间
            if (data.goods_info.is_presell == '1') {
                data.goods_info.presell_deliverdate_str = unixTimeToDateString(data.goods_info.presell_deliverdate);
            }
            if(data.pindan_info){
              data.pindan_info.scheduler_val=parseInt(data.pindan_info.current_num*100/data.pindan_info.success_num);
            }
            //data.spec_list=JSON.parse(data.spec_list);
            //渲染模板
            var html = template.render('product_detail', data);
            $("#product_detail_wp").html(html);

            // @add 手机端详情
            if (data.goods_info.mobile_body) {
                $('#mobile_body').html(data.goods_info.mobile_body);
            }

            //图片轮播
            picSwipe();
            //商品描述
            $(".pddcp-arrow").click(function (){
              $(this).parents(".pddcp-one-wp").toggleClass("current");
            });
            //规格属性
            var myData = {};
            myData["spec_list_mobile"] = data.spec_list_mobile;
            $(".pddc-stock a").click(function (){
              var self = this;
              arrowClick(self,myData);
            });
            //
            //购买数量，减
            // $(".minus-wp").click(function (){
            //    var buynum = $(".buy-num").val();
            //    if(buynum >1){
            //       $(".buy-num").val(parseInt(buynum-1));
            //    }
            // });
            // //购买数量加
            // $(".add-wp").click(function (){
            //    var buynum = parseInt($(".buy-num").val());
            //    if(buynum < data.goods_info.goods_storage){
            //       $(".buy-num").val(parseInt(buynum+1));
            //    }
            // });
            // // 一个F码限制只能购买一件商品 所以限制数量为1
            // if (data.goods_info.is_fcode == '1') {
            //     $('.minus-wp').hide();
            //     $('.add-wp').hide();
            //     $(".buy-num").attr('readOnly', true);
            // }
            //收藏
            $(".pd-collect").click(function (){
                var key = getcookie('key');//登录标记
                if(key==''){
                  window.location.href = WapSiteUrl+'/tmpl/member/login.html';
                }else {
                  $.ajax({
                    url:ApiUrl+"/index.php?act=member_favorites&op=favorites_add",
                    type:"post",
                    dataType:"json",
                    data:{goods_id:goods_id,key:key},
                    success:function (fData){
                     if(checklogin(fData.login)){
                        if(!fData.datas.error){
                          $.sDialog({
                            skin:"green",
                            content:"收藏成功！",
                            okBtn:false,
                            cancelBtn:false
                          });
                        }else{
                          $.sDialog({
                            skin:"red",
                            content:fData.datas.error,
                            okBtn:false,
                            cancelBtn:false
                          });
                        }
                      }
                    }
                  });
                }
            });
            
            //加入进货单
            $("#btn_show_goods_select").bind('click',function(){
               var key = getcookie('key');//登录标记
               if(key==''){
                 location.href ='member/login.html';
                 return;
               }
               var dialog=$(".add-to-cart-select-box"),spec_list=dialog.find(".goods-spec-list");
               dialog.show();
               $(".goods-common").show();
               $(".goods-spec-list").show();
               $(".bottom-confirm").show();
               dialog.css("bottom",-dialog.height());
               var spec_list_h=spec_list.height(),window_h=$(window).height(),max_spec_list_h=window_h*0.6;
               if(spec_list_h>max_spec_list_h){
                 //$("body").css({"overflow":"hidden","position":"fixed"});
                 spec_list.height(max_spec_list_h);
                 spec_list.css("overflow","scroll");
               }
               dialog.animate({
                 bottom:0
               },300, 'ease');
               var bg=$(".add-to-cart-select-box-bg");
               bg.show();
               bg.animate({
                 opacity:0.5
               },300, 'ease');
            });
            $("#btn_close_goods_select").bind('click',function(){
               //$("body").removeAttr("style");
               var dialog=$(".add-to-cart-select-box"),bg=$(".add-to-cart-select-box-bg");
                bg.animate({
                 opacity:0
               },300, 'ease',function(){
                  bg.hide();
               });
               dialog.animate({
                 bottom:-dialog.height()
               },300, 'ease',function(){
                   dialog.hide();
               });
            });
            $(".goods-spec-list .checkbox").click(function(){
               var tr=$(this).closest("tr");
               if(tr.is(".selected")){
                 tr.removeClass("selected");
               }else{
                 tr.addClass("selected");
               }
            });
            $(".goods-spec-list input[type='button']").click(function(){
                 var count_obj=$(this).parent().find("input[type='tel']"); now_val=parseInt(count_obj.val());
                if($(this).is(".reduce")){
                  if(now_val>parseInt($(this).attr("min_value"))){
                     now_val--;
                  }
                }else{
                   now_val++;
                }
                count_obj.val(now_val);
            });
            var common_goods_moq=data.goods_info.goods_common_moq;
            $("#btn_select_yes").click(function(){
                var  addcart_goods_ids=[],addcart_quantitys=[],all_count=0;
                $(".goods-spec-list .selected").each(function(){
                    var selected=$(this),goods_id=selected.find(".checkbox").attr("data_id"),quantity=parseInt(selected.find(".input-moq").val());
                    addcart_goods_ids.push(goods_id);
                    addcart_quantitys.push(quantity);
                    all_count+=quantity;
                });
                if(addcart_goods_ids.length==0){
                  showSelectNotice("您未选择任何规格",3000);
                  return;
                }
                if(all_count<common_goods_moq){
                  showSelectNotice("未达到此商品的最小混批量"+common_goods_moq+"件",3000);
                  return;
                }
                var commonid=$(this).attr("commonid");
                var key=getcookie('key');
                $.ajax({
                   url:ApiUrl+"/index.php?act=member_cart&op=cart_add",
                   data:{key:key,goods_id:addcart_goods_ids.join(","),quantity:addcart_quantitys.join(","),commonid:commonid},
                   type:"post",
                   success:function (result){
                      var rData = $.parseJSON(result);
                      if(checklogin(rData.login)){
                        if(!rData.datas.error){
                            if(rData.datas.text){
                              showSelectNotice("<span>"+rData.datas.text+"</span>");
                            }else{
                              showSelectNotice("<span>成功加入进货单</span>");
                            }
                            $(".go-to-cart-list").show();
                            $(".goods-common").hide();
                            $(".goods-spec-list").hide();
                            $(".bottom-confirm").hide();
                            $(".add-to-cart-select-box-bg").hide();
                            $(function () {
                                setTimeout(function () {
                                	$(".notice-row").hide();
                                    $(".go-to-cart-list").hide();
                                    $(".add-to-cart-select-box").hide();
                                }, 10000);
                            })
                            
                        }else{
                          showSelectNotice(rData.datas.error,3000);
                        }
                      }
                   }
                });
            });
            $(window).scroll(function (){
                  if($(window).scrollTop()==$(document).height()-$(window).height()){
                      if($("#detail").css('display')=='none'){
                          $.ajax({
                              url: ApiUrl + "/index.php?act=goods&op=goods_body",
                              data: {goods_id: goods_id},
                              type: "get",
                              success: function(result) {
                                  $("#detail").html(result);
                                  $("#detail").show();
                                  $(window).unbind();
                              }
                          });
                      }
                  }
            });
              //样品购买
              $('.sample-reduce-btn').click(function(){
                  var n = Number($('.sample-goods-num').val());
                  if(isNaN(n)){
                      n=1;
                  }
                  n--;
                  var min = $('.sample-goods-num').attr('min');
                  if(n<min){
                      n=min;
                  }
                  $('.sample-goods-num').val(n);
              });
              $('.sample-add-btn').click(function(){
                  var n = Number($('.sample-goods-num').val());
                  if(isNaN(n)){
                      n=1;
                  }
                  n++;
                  var max = $('.sample-goods-num').attr('max');
                  if(n>max){
                      n=max;
                  }
                  $('.sample-goods-num').val(n);
              });
              $('.sample-goods-num').change(function(){
                  var min= $(this).attr('min');
                  var max= $(this).attr('max');
                  var val = Number($(this).val());
                  if(isNaN(val)){
                      val=min;
                  }else if(val<min){
                      val=min;
                  }else if(val>max){
                      val = max;
                  }
                  $(this).val(val);
              });
              $('.sample-buy-btn').click(function(){
                  var n = $('.sample-goods-num').val();
                  if(isNaN(n)){
                      n=1;
                  }
                  location.href=WapSiteUrl+'/tmpl/order/buy_step1.html?ifcart=0&sample_goods=1&cart_id='+data.goods_info.goods_id+'|'+n;
              });
            // var  addcart_goods_ids=[],addcart_quantitys=[];
            // $.each(data.spec_goods_moq,function(g,q){
            //   addcart_goods_ids.push(g);
            //   addcart_quantitys.push(q);
            // });
            //加入购物车
            // $(".add-to-cart").click(function (){
            //   var key = getcookie('key');//登录标记
            //    if(key==''){
            //       window.location.href = WapSiteUrl+'/tmpl/member/login.html';
            //    }else{
            //       var quantity = parseInt($(".buy-num").val());
            //       $.ajax({
            //          url:ApiUrl+"/index.php?act=member_cart&op=cart_add",
            //          data:{key:key,goods_id:addcart_goods_ids.join(","),quantity:addcart_quantitys.join(",")},
            //          type:"post",
            //          success:function (result){
            //             var rData = $.parseJSON(result);
            //             if(checklogin(rData.login)){
            //               if(!rData.datas.error){
            //                  $.sDialog({
            //                     skin:"block",
            //                     content:"添加进货单成功！</br>您可在进货单中统一选择规格</br>及确定采购数量。",
            //                     "okBtnText": "再逛逛",
            //                     "cancelBtnText": "去进货单",
            //                     okFn:function (){},
            //                     cancelFn:function (){
            //                       window.location.href = WapSiteUrl+'/tmpl/cart_list.html';
            //                     }
            //                   });
            //               }else{
            //                 $.sDialog({
            //                   skin:"red",
            //                   content:rData.datas.error,
            //                   okBtn:false,
            //                   cancelBtn:false
            //                 });
            //               }
            //             }
            //          }
            //       })
            //    }
            // });
            if(data.pindan_info){//如果存在拼单
               setInterval(function(){
                    var _this=$("#pindan_time");
                    var time_down=parseInt(_this.attr("time_down"));
                    if(time_down==0) return;
                    time_down--;
                    _this.attr("time_down",time_down);
                    var hour=parseInt(time_down/3600);
                    hour=hour>99?99:hour;
                    var minutes=parseInt((time_down%3600)/60),second=(time_down%3600)%60;
                    var time_str=hour>9?"<span>"+parseInt(hour/10)+"</span>"+"<span>"+(hour%10)+"</span>":"<span>0</span><span>"+hour+"</span>";
                    time_str+=":";
                    time_str+=minutes>9?"<span>"+parseInt(minutes/10)+"</span>"+"<span>"+(minutes%10)+"</span>":"<span>0</span><span>"+minutes+"</span>";
                    time_str+=":";
                    time_str+=second>9?"<span>"+parseInt(second/10)+"</span>"+"<span>"+(second%10)+"</span>":"<span>0</span><span>"+second+"</span>";
                    _this.html("距离本轮拼单结束&nbsp;&nbsp;"+time_str);
                },1000);
            }

          }else {

            $.sDialog({
                content: data.error + '！<br>请返回上一页继续操作…',
                okBtn:false,
                cancelBtnText:'返回',
                cancelFn: function() { history.back(); }
            });

            //var html = data.error;
            //$("#product_detail_wp").html(html);

          }

          //验证购买数量是不是数字
          $("#buynum").blur(buyNumer);
          AddView();
       }


    });
  function showSelectNotice(text,delay){
    var notice_row=$("#notice_row");
    notice_row.html(text);
    notice_row.show();
    if(delay){
      setTimeout(function(){notice_row.hide();},delay);
    }
  }
  //点击商品规格，获取新的商品
  function arrowClick(self,myData){
    $(self).addClass("current").siblings().removeClass("current");
    //拼接属性
    var curEle = $(".pddc-stock-spec").find("a.current");
    var curSpec = [];
    $.each(curEle,function (i,v){
      curSpec.push($(v).attr("specs_value_id"));
    });
    var spec_string = curSpec.sort().join("|");
    //获取商品ID
    var spec_goods_id = myData.spec_list_mobile[spec_string];
    window.location.href = "product_detail.html?goods_id="+spec_goods_id;
  }

  function AddView(){//增加浏览记录
	  var goods_info = getcookie('goods');
	  var goods_id = GetQueryString('goods_id');
	  if(goods_id<1){
		  return false;
	  }

	  if(goods_info==''){
		  goods_info+=goods_id;
	  }else{

		  var goodsarr = goods_info.split('@');
		  if(contains(goodsarr,goods_id)){
			  return false;
		  }
		  if(goodsarr.length<5){
			  goods_info+='@'+goods_id;
		  }else{
			  goodsarr.splice(0,1);
			  goodsarr.push(goods_id);
			  goods_info = goodsarr.join('@');
		  }
	  }

	  addcookie('goods',goods_info);
	  return false;
  }

  function contains(arr, str) {//检测goods_id是否存入
	    var i = arr.length;
	    while (i--) {
	           if (arr[i] === str) {
	           return true;
	           }
	    }
	    return false;
	}
  $.sValid.init({
        rules:{
            buynum:"digits"
        },
        messages:{
            buynum:"请输入正确的数字"
        },
        callback:function (eId,eMsg,eRules){
            if(eId.length >0){
                var errorHtml = "";
                $.map(eMsg,function (idx,item){
                    errorHtml += "<p>"+idx+"</p>";
                });
                $.sDialog({
                    skin:"red",
                    content:errorHtml,
                    okBtn:false,
                    cancelBtn:false
                });
            }
        }
    });
  //检测商品数目是否为正整数
  function buyNumer(){
    $.sValid();
  }




});
