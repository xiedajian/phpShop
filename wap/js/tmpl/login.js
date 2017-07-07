$(function(){
	$('#loginbtn').click(function(){//会员登陆
		var username = $('#username').val();
		alert(username);
		if(!username){
           $('#username').focus();
           return;
		}else{
			if(!/^1\d{10}$/.test(username)){
				$('#username').focus();
				$(".login-error").html("请正确输入手机号");
				return;
			}
		}
		var pwd = $('#userpwd').val();
		if(!pwd){
		   $('#userpwd').focus();
           return;
		}
		var client = 'wap';
	      $.ajax({
			type:'post',
			url:ApiUrl+"/index.php?act=login",	
			data:{username:username,password:pwd,client:client},
			dataType:'json',
			success:function(result){
				if(!result.datas.error){
					if(typeof(result.datas.key)=='undefined'){
						return false;
					}else{
						addcookie('username',result.datas.username);
						addcookie('key',result.datas.key);
						location.href = document.referrer;
					}
					$(".login-error").hide();
				}else{
					$(".login-error").html(result.datas.error).show();
				}
			}
		 });  
        
	});
});