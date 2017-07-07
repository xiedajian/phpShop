$(function(){
		var key = getcookie('key');
		if(key==''){
			location.href = 'login.html';
		}
		$.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=member_index",	
			data:{key:key},
			dataType:'json',
			//jsonp:'callback',
			success:function(result){
				checklogin(result.login);
                var goods = getcookie('goods');
                var goods_info = goods.split('@');
                var history_count  = 0;
                if(goods){
                    history_count = goods_info.length;
                }
				$('#username').html(result.datas.member_info.user_name);
				$('#point').html(result.datas.member_info.point);
                $('#predepoit').html("￥"+result.datas.member_info.predepoit);
                $('#available_rc_balance').html("￥"+result.datas.member_info.available_rc_balance);
				$('#avatar').attr("src",result.datas.member_info.avator);
                $('#favorites_count').html(result.datas.member_info.favorites_count);
                $('#history').html(history_count);
                $('#auth'+result.datas.member_info.auth).show();
                if(result.datas.member_info.auth==1){
                    $('#auth'+result.datas.member_info.auth).parent('div').children(':first').removeClass('wdprz-icon').addClass('dprz-icon');
                }
                if(result.datas.member_info.is_vip){
                    $('#vip').addClass('vip-icon');
                }
                $('#nopay').html(result.datas.member_info.order_nopay_count);
                $('#nosend').html(result.datas.member_info.order_nosend_count);
                $('#norecevice').html(result.datas.member_info.order_norecevice_count);
                $('#refund').html(result.datas.member_info.order_refund_count);

				return false;
			}
		});

});