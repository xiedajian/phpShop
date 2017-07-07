<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎注册拼单网</title>
<meta name="keywords" content="拼单网注册" />
<meta name="description" content="拼单网注册" />
<style  type="text/css">
   .body-content{width:1200px;margin:auto;}
   .register-remark{color:#E1017E;font-size:13px;text-align:center;width:100%;padding-top:15px;  clear: both;}
   .register-form{  margin-left:368px;}
   .register-form dl{float:left;  margin: 10px 0;clear:both;}
   .register-form dl dt{width:80px;float:left;  text-align: left;color: #999;font-size: 13px;line-height: 30px;}
   .register-form dl dd{float:left;margin-left:0;}
   .register-form dl dd label{color:#EC007C;margin-left:5px;font-size:13px;  display: inline-block;}
   .register-input{height:28px;line-height:28px;border:1px solid #CCC;width:300px;padding-left:5px;}
    #btn_register{height:30px;line-height:30px;background:#EC007C;color:#FFF;width:300px;display:block;text-align:center;font-size:14px;  text-decoration: inherit;}
   .login-text{font-size:13px;color:#999;margin-left:312px;}
   .login-text a{text-decoration: inherit;color:#E1017E;margin-left:5px;}
   .provision-text{font-size:13px;}
   .provision-text a{text-decoration: inherit;}
   #btn_register_code{display:inline-block;background-color:#999;color:#FFF;text-decoration: inherit;width:150px;height:30px;text-align:center;border:0;}
   #btn_register_code.ok{background-color:#029ee8;}
   #footer {color: #666;font-size: 12px !important;text-align: center;margin: 0 auto;padding:20px 0 10px 0;overflow: hidden;width: 100%;clear:both;}
   #footer p {color: #666;word-spacing: 5px;padding: 10px 0;}
   #footer a {color: #666;text-decoration: none;}
</style>
</head>
 <body>
    <div class="body-content">
       <?php require_once 'ipvp_reg_top.php';?>
      <div class="register-remark">请输入手机号码注册账号，注册成功好此手机号码将作为您在拼单网的登录账号。</div>
      <div>
         <form class="register-form" method="post">
           <?php Security::getToken();?>
           <input type="hidden" name="form_submit" value="ok" />
          <dl style="margin:20px 0 0 0;">
            <dd><span class="login-text">已注册?<a href="index.php?act=login&op=index">登录</a></span></dd>
          </dl>
          <dl>
           <dt>手机号：</dt>
           <dd><input type="text" id="mobile" name="mobile" class="register-input" placeholder="请输入您的手机号"/><label></label></dd>
          </dl>
         <dl>
             <dt>图片验证码：</dt>
             <dd>

                 <input type="text" id="captcha" name="captcha" class="register-input" style="width:142px" placeholder="请输入图片验证码" maxlength="4" size="10"/>
                 <img id="codeimage" src="<?php echo SHOP_SITE_URL?>/index.php?act=mycode&op=makecode&hash=<?php echo _getNchash('login','ipvp_register');?>" style="cursor: pointer;float: right;margin: 2px 0px 0px 4px;" title="点击刷新验证码">
                 </br>

             </dd>
         </dl>
           <dl>
           <dt>短信验证码：</dt>
           <dd>
               <input type="text"  name="code" style="width:142px" class="register-input"/>
               <button type="button" id="btn_register_code">获取短信验证码</button>
              <label></label>
           </dd>
          </dl>
          <dl>
           <dt>真实姓名：</dt>
           <dd><input type="text" name="truename" class="register-input"/><label></label></dd>
          </dl>
           <dl>
           <dt>请设置密码：</dt>
           <dd><input type="password" id="password" name="password" class="register-input" placeholder="6-16位数字、字母或符号"/><label></label></dd>
          </dl>
           <dl>
           <dt>请确认密码：</dt>
           <dd><input type="password"  id="password_confirm" name="password_confirm" class="register-input"  placeholder="请再次输入密码"/><label></label></dd>
          </dl>
          <dl>
           <dt>&nbsp;</dt>
           <dd class="provision-text"><input name="agree" type="checkbox" checked="checked"/>已阅读并接受<a href="#">《拼单网服务条款》</a><label></label></dd>
          </dl>
           <dl style="margin:0;">
           <dt>&nbsp;</dt>
           <dd><a href="javascript:void($('.register-form').submit())" id="btn_register">下一步</a></dd>
          </dl>
         </form>
      </div>
    </div>
<div id="footer" class="wrapper">
  <p><a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlShop('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p>
  <?php echo $output['setting_config']['shopnc_version'];?> <?php echo $output['setting_config']['icp_number']; ?>
  </div>
<script type="text/javascript">
var wait=60;//时间
//注册表单验证
$(function(){
	jQuery.validator.addMethod("mobileTest", function(value, element) {
	  var test_result = /^1\d{10}$/.test(value);
	  //if(test_result){
	  //$("#btn_register_code").addClass("ok");
	  //}
	  return this.optional(element) || test_result;
	}, "请输入正确手机号");
    $(".register-form").validate({
        errorPlacement: function(error, element){
            var error_td = element.parent('dd');
            error_td.find('label').hide();
            error_td.append(error);
			if(error.attr("for")=="mobile"){
		      $("#btn_register_code").removeClass("ok");
		    }
        },
		errorElement:"label",
		errorClass:"inp-error",
        onkeyup: false,
        rules : {
            mobile : {
			    required:true,
                mobileTest : true,
                remote: {
                    url :'index.php?act=login&op=check_mobile_exist',
                    type:'get',
                    data:{
                        mobile : function(){return $('#mobile').val();}
                    },
                    beforeSend:function(){
                    	$("#btn_register_code").removeClass("ok");
                    },
					complete:function(result){
					    if(result.responseText=="true"&&$("#captcha").hasClass('valid')){
                            $("#btn_register_code").addClass("ok");
						}else{
							$("#btn_register_code").removeClass("ok");
						}
					}
                }
            }
            ,captcha : {
                remote   : {
                    url : '<?php echo SHOP_SITE_URL?>/index.php?act=mycode&op=check&hash=<?php echo _getNchash('login','ipvp_register');?>',
                    type: 'get',
                    data:{
                        code : function(){
                            return $('#captcha').val();
                        }
                    },
                    complete: function(data) {
                        if(data.responseText == 'false') {
                            $("#btn_register_code").removeClass("ok");
                            document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=mycode&op=makecode&hash=<?php echo _getNchash('login','ipvp_register');?>&r='+Math.random();
                        }else{
                            if($("#mobile").hasClass('valid')){
                                $("#btn_register_code").addClass("ok");
                            }
                            else{
                                $("#btn_register_code").removeClass("ok");
                            }
                        }
                    }
                }
            },
            password : {
                required : true,
                minlength: 6,
				maxlength: 16
            },
            password_confirm : {
                required : true,
                equalTo  : '#password'
            },
            code : {
                required : true,
                minlength: 6,
                maxlength: 6
            },
            agree : {
                required : true
            }
        },
        messages : {
            mobile : {
                required : '您输入的手机号码有误',
				mobileTest:'您输入的手机号码有误',
				remote: '您输入的手机号码已经存在'
            },
            captcha : {
                remote	 : '验证码错误'
            },
            password  : {
                required : '请输入密码',
                minlength: '请输入6-16位数字、字母或符号',
				maxlength: '请输入6-16位数字、字母或符号'
            },
            password_confirm : {
                required : '请输入确认密码',
                equalTo  : '两次密码输入不一致'
            },
            code : {
                required : '请输入手机校验码',
                minlength: '您输入的校验码有误',
                maxlength: '您输入的校验码有误',
                remote	 : '您输入的校验码有误'
            },
            agree : {
                required : '请同意拼单网用户服务协议'
            }
        }
    });

    $("#btn_register_code").bind('click',function(){
        if(!$(this).is(".ok")) return false;
        $.getJSON('index.php?act=login&op=send_register_code&mobile='+$("#mobile").val()+'&code='+$("#captcha").val(),function(result){
     	   if(result.state){
               document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?act=mycode&op=makecode&hash=<?php echo _getNchash('login','ipvp_register');?>&t=' + Math.random();
               $('#captcha').val('');
     			$("#btn_register_code").removeClass("ok");
     		    var t_id=setInterval(function(){
     		    	wait--;
     		    	$("#btn_register_code").text(wait + "秒后重新获取验证码");
     		    	if(wait==0){
     		    		clearInterval(t_id);
     		    		//$("#btn_register_code").addClass("ok");
     		    		$("#btn_register_code").text("获取短信验证码");
     		    		$("#mobile").focus();
     		    		$("#btn_register_code").focus();
     		    		wait = 60;
     		    	}
     		    },1000);
     	   }
            if(result.code==1){
                alert(result.msg);
            }
        });
    });

    //验证码
    $('#codeimage').click(function(){
        $(this).attr('src','<?php echo SHOP_SITE_URL?>/index.php?act=mycode&op=makecode&hash=<?php echo _getNchash('login','ipvp_register');?>&r='+Math.random());
    });
});
</script>
 </body>
</html>
