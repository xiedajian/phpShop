$(function (){
    var memberHtml ='';// '<a class="btn mr5" href="'+WapSiteUrl+'/tmpl/member/member.html?act=member">个人中心</a>';
    var act = GetQueryString("act");
    if(act && act == "member"){
        memberHtml = '<a class="btn mr5" id="logoutbtn" href="javascript:void(0);">注销账号</a>';
    }
    var tmpl = '<div class="footer">'
        +'<div class="footer-top">'
            +'<div class="footer-tleft">'+ memberHtml +'</div>'
            +'<a href="javascript:void(0);"class="gotop">'
                +'<span class="gotop-icon"></span>'
                +'<p>回顶部</p>'
            +'</a>'
        +'</div>'
    +'</div>';
	 var tmpl2 = '<div id="bottom">'
		+'<div>'
  +'<div id="nav-tab" style="bottom:-40px;">'
            +'<div id="nav-tab-btn"><i class="fa fa-chevron-down"></i></div>'
            +'<div class="clearfix tab-line nav">'
      +'<div class="tab-line-item" style="width:25%;" ><a href="'+WapSiteUrl+'"><i class="buttom-menu-icon-1"></i><br>首页</a></div>'
      +'<div class="tab-line-item" style="width:25%;" ><a href="'+WapSiteUrl+'/tmpl/product_first_categroy.html"><i class="buttom-menu-icon-2"></i><br>分类</a></div>'
      //+'<div class="tab-line-item get_down" style="width:12%;line-height:40px;padding-top:5px;" ><i style="font-size:30px;" class="fa fa-chevron-circle-down"></i><br></div>'
      +'<div class="tab-line-item" style="width:25%;position: relative;" ><a href="'+WapSiteUrl+'/tmpl/cart_list.html"><i class="buttom-menu-icon-3"></i><br>进货单</a></div>'
      +'<div class="tab-line-item" style="width:25%;" ><a href="'+WapSiteUrl+'/tmpl/member/member.html?act=member"><i class="buttom-menu-icon-4"></i><br>个人中心</a></div>'
    +'</div>'
   +'</div>'
+'</div>'
		+'<div style="z-index: 10000; border-radius: 3px; position: fixed; background: none repeat scroll 0% 0% rgb(255, 255, 255); display: none;" id="myAlert" class="modal hide fade">'
  +'<div style="text-align: center;padding: 15px 0 0;" class="title"></div>'
  +'<div style="min-height: 40px;padding: 15px;" class="modal-body"></div>'
  +'<div style="padding:3px;height: 35px;line-height: 35px;" class="alert-footer">'
  +'<a style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;border-right: 1px solid #ddd;margin-right: -1px;" class="confirm" href="javascript:;">Save changes</a><a aria-hidden="true" data-dismiss="modal" class="cancel" style="padding-top: 4px;border-top: 1px solid #ddd;display: block;float: left;width: 50%;text-align: center;" href="javascript:;">关闭</a></div>'
+'</div>'
		+'<div style="display:none;" class="tips"><i class="fa fa-info-circle fa-lg"></i><span style="margin-left:5px" class="tips_text"></span></div>'
		+'<div class="bgbg" id="bgbg" style="display: none;"></div>'
        +'</div>'
	+'</div>';
	//var render = template.compile(tmpl);
	//var html = render();
	//$("#footer").html(html+tmpl2);
    $("#footer").html(tmpl2);
    //回到顶部
    $(".gotop").click(function (){
        $(window).scrollTop(0);
    });
    var key = getcookie('key');
	$('#logoutbtn').click(function(){
		var username = getcookie('username');
		var key = getcookie('key');
		var client = 'wap';
		$.ajax({
			type:'get',
			url:ApiUrl+'/index.php?act=logout',
			data:{username:username,key:key,client:client},
			success:function(result){
				if(result){
					delCookie('username');
					delCookie('key');
					location.href = WapSiteUrl+'/tmpl/member/login.html';
				}
			}
		});
	});
	$("#bottom .tab-line-item a").each(function(){
		if(this.href==location.href||location.href==this.href+'/'){
			$(this).closest(".tab-line-item").css('background-color','#E1017E');
			return false;
		}
	});
});

//bottom nav 33 hao-v3 by 33h ao.com Qq 1244 986 40
//$(function() {
//	setTimeout(function(){
//		if($("#content .container").height()<$(window).height())
//		{
//			$("#content .container").css("min-height",$(window).height());
//		}
//	},300);
//	$("#bottom .nav .get_down").click(function(){
//		$("#bottom .nav").animate({"bottom":"-50px"});
//		$("#nav-tab").animate({"bottom":"0px"});
//	});
//	$("#nav-tab-btn").click(function(){
//		$("#bottom .nav").animate({"bottom":"0px"});
//		$("#nav-tab").animate({"bottom":"-40px"});
		
//	});
//	setTimeout(function(){$("#bottom .nav .get_down").click();},500);
//	$("#scrollUp").click(function(t) {
//		$("html, body").scrollTop(300);
//		$("html, body").animate( {
//			scrollTop : 0
//		}, 300);
//		t.preventDefault()
//	});
//});
