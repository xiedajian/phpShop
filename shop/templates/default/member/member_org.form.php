<style type="text/css">
    .body-content .top-remark{color:#999;font-size:13px;margin-top:20px;width:100%;text-align:center;}
    
    .top-remark i{color:red;margin-right:5px;}
    .auth-form .d-title{width:120px;border-bottom:30px solid #d7d7d7;border-right:30px solid transparent;}
    .auth-form .d-title span{position:relative;top:26px;left:10px;font-size:15px;color:#333;}
    .auth-form .d-body{padding-left:30px;}
    .body-content .auth-form{  margin-left:328px;margin-top:30px;}
    
    .auth-form dl{float:left;  margin: 10px 0;clear:both;}
    .auth-form dl dt{width:120px;float:left;  text-align: left;color: #999;font-size: 13px;line-height: 30px;position:relative;}
    .auth-form dl dt i{background-color:#ff3674;margin-right:6px;position:absolute;top:10px;left:-18px;display:block;width:8px;height:8px;  border-radius: 50%;}
    .auth-form dl dd{float:left;margin-left:0;}
    .auth-form dl dd label{color:#EC007C;margin-left:5px;font-size:13px;  display: inline-block;}
    .auth-form input{height:28px;line-height:28px;border:1px solid #CCC;width:300px;padding:0 0 0 5px;}
    .b-r-p{background:#333;width:30px;height:30px;color:#FFF;border:0px;}
    .b-r-p.disabled{background:#d7d7d7;}
    .btn-image-select,.btn-image-select:hover{display:inline-block;width:120px;height:80px;background:#00a1e9;line-height:80px;text-align:center;color:#FFF;  text-decoration: inherit;}
    .auth-form .img-dl dt{height:80px;line-height:80px;}
    .auth-form .img-dl dt i{top:36px;}
    .auth-form .img-dl dd span{position:relative;display: inline-block;}
    .auth-form .img-dl dd .upload_view_img{position:absolute;top:-44px;left:10px;}
    .body-content  #btn_pass_auth,#btn_submit_auth{display:inline-block;color:#FFF;padding:8px 50px;text-decoration: inherit;font-size:14px;}
    .body-content  #btn_pass_auth{background:#cdcbcb;margin-right:40px;}
    .body-content  #btn_submit_auth{background:#ec007c;}
    .body-content  .auth-header-step{display:none;}
   .auth-header-step{margin-top:25px;margin-left:45px;}
   .auth-header-step .step>div{float:left;} 
   .auth-header-step .step .step-1,.auth-header-step .step .step-2,.auth-header-step .step .step-3{background-color:#d7d7d7;height:40px; width:280px;text-align:center;line-height:40px;color:#747373;}
   .auth-header-step .step .step-2{width:260px;}
   .auth-header-step .step-current .step-1,.auth-header-step .step-current .step-2,.auth-header-step .step-current .step-3{background-color:#029ee8;color:#fff;}
   .auth-header-step .step .step-right-arrow{width: 0;height: 0;border-top: 20px solid transparent;border-left: 20px solid #d7d7d7;border-bottom: 20px solid transparent;}
   .auth-header-step .step .step-right-arrow-0{width: 0;height: 0;border-top: 20px solid #d7d7d7;border-left: 20px solid transparent;border-bottom: 20px solid #d7d7d7;}
   .auth-header-step .step-current .step-right-arrow{border-left-color:#029ee8;}
   .auth-header-step .step-current .step-right-arrow-0{border-top-color:#029ee8;border-bottom-color:#029ee8;}
   .auth-header-step .step .step-2 span{line-height: 16px;display: block;padding-top: 4px;}
    
    .right-layout .auth-form{  margin-left:45px;}
    .right-layout #btn_pass_auth{display:none;}
    .right-layout .top-remark{padding-left:60px;}
    .right-layout #btn_submit_auth{background:#ec007c;margin-left:150px;width:306px;height:30px;line-height:30px;display:inline-block;color:#FFF;text-decoration: inherit;font-size:14px;padding:0;text-align:center;}
    .upload-remark{width:165px;position:absolute;top:300px;right:70px;}
    .upload-remark p{margin:0;}
    .upload-remark p i{color:red;font-size:1.3em;margin-right:3px;}
    .upload-remark span{color:#E1017E}
    .upload-remark>p:first-child{padding:5px 0;}
    .upload-remark>p:last-child{padding-left:10px;}
</style>
<div style="position: relative;">
  <div class="upload-remark">
      <p><i>*</i>注意事项：</p>
      <p>证件必须是清晰彩色原件电子版，可以是扫描件或者数码拍 摄照片。仅支持 jpg/jpeg/gif/png 的图片 。</br>
      <span>图片大小不超过4M。</p>
  </div>
</div>
<div class="top-remark">
  <i>*</i>承诺所传身份证明、营业执照等只用于购买本平台商品并为您优先处理退款退货等售后问题，不作他途使用，其他任何人均无法查看。
</div>
<div class="auth-header-step">
    <div class="step step-current">
        <div class="step-1"><span>第一步：店铺认证申请</span></div>
        <div class="step-right-arrow"></div>
    </div>
    <div class="step">
        <div class="step-right-arrow-0"></div>
        <div class="step-2"><span>第二步：工作人员审核</br>(一个工作日内申请)</span></div>
        <div class="step-right-arrow"></div>
    </div>
    <div class="step">
        <div class="step-right-arrow-0"></div>
        <div class="step-3"><span>第三步：认证成功</span></div>
    </div>
</div>
<div>
  <form class="auth-form" action="index.php?act=member_org&op=org_auth" method="POST">
    <div class="d-title"><span>门店信息</span></div>
    <div class="d-body">
	    <dl>
	       <dt><i></i>门店名称</dt>
	       <dd><input type="text" name="org_name" value="<?php echo $output["member_org_info"]["org_name"];?>"/><label></label></dd>
	     </dl>
	      <dl>
	       <dt><i></i>门店数</dt>
	       <dd><button type="button" class="b-r-p <?php echo !empty($output["member_org_info"])&&$output["member_org_info"]["store_count"]>1?"":"disabled";?> reduce">-</button><input type="text" name="store_count" readonly="true" style="width:80px;margin:0 5px;" value="<?php echo $output["member_org_info"]?$output["member_org_info"]["store_count"]:"1";?>"/><button type="button" class="b-r-p plus">+</button><label></label></dd>
	     </dl>
	     <dl class="img-dl">
	       <dt><i></i>营业执照</dt>
	       <dd>
	          <input type="hidden" id="license_img" name="license_img" value="<?php echo $output["member_org_info"]["license_img"];?>" multiple/>
	          <input type="file" id="license" name="license" style="display:none"/>
	          <a href="javascript:void(0);" class="btn-image-select">选择图片</a>
	          <span>
	           <?php if(!empty($output["member_org_info"]["license_img"])){?>
	               <img class='upload_view_img' alt="" src="<?php echo $output["member_org_info"]["license_img_path"];?>"/>
	           <?php } ?>
	          </span>
	          <label></label>
	       </dd>
	     </dl>
	     <dl class="img-dl">
	       <dt>组织机构代码证</dt>
	       <dd>
	       	  <input type="hidden" id="organization_certificate_img" name="organization_certificate_img" value="<?php echo $output["member_org_info"]["organization_certificate_img"];?>"/>
	          <input type="file" id="organization_certificate" name="organization_certificate" style="display:none"/>
	          <a href="javascript:void(0);" class="btn-image-select">选择图片</a>
	          <span>
	          <?php if(!empty($output["member_org_info"]["organization_certificate_img"])){?>
	               <img class='upload_view_img' alt="" src="<?php echo $output["member_org_info"]["organization_certificate_img_path"];?>"/>
	           <?php } ?>
	          </span>
	          <label></label>
	       </dd>
	     </dl>
	     <dl class="img-dl">
	       <dt>税务登记证</dt>
	       <dd>
	       	  <input type="hidden" id="tax_certificate_img" name="tax_certificate_img" value="<?php echo $output["member_org_info"]["tax_certificate_img"];?>"/>
	          <input type="file" id="tax_certificate" name="tax_certificate"  style="display:none"/>
	          <a href="javascript:void(0);" class="btn-image-select">选择图片</a>
	          <span>
	           <?php if(!empty($output["member_org_info"]["tax_certificate_img"])){?>
	               <img class='upload_view_img' alt="" src="<?php echo $output["member_org_info"]["tax_certificate_img_path"];?>"/>
	           <?php } ?>
	          </span>
	          <label></label>
	       </dd>
	     </dl>
    </div>
    <div class="d-title"><span>法人身份信息</span></div>
    <div class="d-body"> 
	    <dl>
	       <dt><i></i>姓名</dt>
	       <dd><input type="text" name="corporate" value="<?php echo $output["member_org_info"]["corporate"];?>" /><label></label></dd>
	     </dl>
	     <dl class="img-dl">
	       <dt><i></i>身份证</dt>
	       <dd>
	       	  <input type="hidden" id="corporate_idcart_img" name="corporate_idcart_img" value="<?php echo $output["member_org_info"]["corporate_idcart_img"];?>"/>
	          <input type="file" id="corporate_idcart" name="corporate_idcart" style="display:none"/>
	          <a href="javascript:void(0);" class="btn-image-select">选择正面</a>
	          <span>
	           <?php if(!empty($output["member_org_info"]["corporate_idcart_img"])){?>
	               <img class='upload_view_img' alt="" src="<?php echo $output["member_org_info"]["corporate_idcart_img_path"];?>"/>
	           <?php } ?>
	          </span>
	          <label></label>
	       </dd>
	     </dl>
        <dl class="img-dl">
            <dt></dt>
            <dd>
                <input type="hidden" id="corporate_idcartf_img" name="corporate_idcartf_img" value="<?php echo $output["member_org_info"]["corporate_idcartf_img"];?>"/>
                <input type="file" id="corporate_idcartf" name="corporate_idcartf" style="display:none"/>
                <a href="javascript:void(0);" class="btn-image-select">选择反面</a>
	        <span>
	         <?php if(!empty($output["member_org_info"]["corporate_idcartf_img"])){?>
                 <img class='upload_view_img' alt="" src="<?php echo $output["member_org_info"]["corporate_idcartf_img_path"];?>"/>
             <?php } ?>
	        </span>
                <label></label>
            </dd>
        </dl>
    </div>
    <div class="d-title"><span>业务联系人信息</span></div>
    <div class="d-body">
	    <dl>
	       <dt><i></i>联系人姓名</dt>
	       <dd><input type="text" name="conneter"  value="<?php echo $output["member_org_info"]["conneter"];?>"/><label></label></dd>
	    </dl>
	    <dl>
	       <dt><i></i>联系人电话</dt>
	       <dd><input type="text" name="conneter_tel"  value="<?php echo $output["member_org_info"]["conneter_tel"];?>"/><label></label></dd>
	    </dl>
	    <dl>
	       <dt>联系人QQ</dt>
	       <dd><input type="text" name="conneter_qq" value="<?php echo $output["member_org_info"]["conneter_qq"];?>"/></dd>
	    </dl>
    </div>
    <div style="clear:both;padding-top:30px;">
       <a id="btn_pass_auth" href="index.php?act=login&op=ipvp_register_succ">暂不认证,跳过这一步</a>
       <a id="btn_submit_auth" href="javascript:void(0);">提交认证</a>
    </div>
  </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.xdr-transport.js" charset="utf-8"></script>
<script type="text/javascript">
function getfilesize(target){
    //检测上传文件的大小
    var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
    var fileSize = 0;
    if (isIE && !target.files){
        var filePath = target.value;
        var fileSystem = new ActiveXObject("Scripting.FileSystemObject");
        var file = fileSystem.GetFile (filePath);
        fileSize = file.Size;
    } else {
        fileSize = target.files[0].size;
    }
    var size = fileSize;//字节
    //var size=10000;
    return size;
}
function addImg(e,data){
	var dlObj=$(e.target).parent();
    if(getfilesize(data)>=4*1024*1024){
        if(dlObj.find("img").length>0){
        	dlObj.find("label").html("<span style='padding-left:150px;'>上传图片不能超过4M</span>");
        }else{
    	   dlObj.find("label").html("上传图片不能超过4M");
        }
        return;
    }else{
	    dlObj.find("label").text("");
    }
	dlObj.find("span").html("<img src='<?php echo SHOP_TEMPLATES_URL;?>/images/loading.gif' />");
    data.submit();
}
function doneImg(e,data){
    var param = data.result;
    if (typeof(param.error) != 'undefined') {
        alert(param.error);
        $(e.target).parent().find("span").html("");
    } else {
        var input=$('#'+e.target.name+"_img");
        input.val(param.img_name);
        param.img_path += "?rand="+Math.random();
        $(e.target).parent().find("span").html("<img class='upload_view_img' src='"+param.img_path+"' />");
    }
}
/*图片ajax上传 */
$('#license').fileupload({
     multipart:true,
     sequentialUploads:true,
     dataType: 'json',
     url: 'index.php?act=member_org&op=image_upload',
     formData: {name:'license'},
     add: addImg,
     done: doneImg
});
$('#organization_certificate').fileupload({
	  dataType: 'json',
	  url: 'index.php?act=member_org&op=image_upload',
	  formData: {name:'organization_certificate'},
	  add: addImg,
	  done: doneImg
});
$('#tax_certificate').fileupload({
	  dataType: 'json',
	  url: 'index.php?act=member_org&op=image_upload',
	  formData: {name:'tax_certificate'},
	  add: addImg,
	  done: doneImg
});
$('#corporate_idcart').fileupload({
	  dataType: 'json',
	  url: 'index.php?act=member_org&op=image_upload',
	  formData: {name:'corporate_idcart'},
	  add: addImg,
	  done: doneImg
});
$('#corporate_idcartf').fileupload({
    dataType: 'json',
    url: 'index.php?act=member_org&op=image_upload',
    formData: {name:'corporate_idcartf'},
    add: addImg,
    done: doneImg
});
//注册表单验证

$(function(){
  $(".btn-image-select").bind('click',function(){
    $(this).parent().find(':file').trigger('click');
  });
  jQuery.validator.addMethod("tel_no", function(value, element) { 
		return this.optional(element) || (/(^\d{3,4}-\d{7,8}(-\d{3,4})?$)|(^1\d{10}$)/.test(value)); 
  }, "请输入正确电话 固话：xxx-xxxxxxxx 手机号：xxxxxxxxxxx"); 
  $(".auth-form").validate({
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
      rules : {
    	  org_name : {
			  required:true
          },
          store_count : {
              required : true,
              min: 1
          },
          license_img : {
              required : true
          },
          corporate : {
              required : true
          },
          corporate_idcart_img : {
              required : true
          },
          corporate_idcartf_img : {
              required : true
          },
          conneter:{
        	  required : true
          },
          conneter_tel:{
        	  required : true,
        	  tel_no:true
          }
      },
      messages : {
    	  org_name : {
              required : '请输入门店名称'
          },
          store_count  : {
              required : '请输入门店数'
          },
          license_img : {
              required : '请上传营业执照'
          },
          corporate : {
              required : '请输入法人姓名'
          },
          corporate_idcart_img : {
              required : '请上传法人身份证正面'
          },
          corporate_idcartf_img : {
              required : '请上传法人身份证反面'
          },
          conneter:{
        	  required : '请输入联系人姓名'
          },
          conneter_tel:{
        	  required : '请输入联系人电话',
        	  tel_no:'请输入正确电话 固话：xxx-xxxxxxxx 手机号：xxxxxxxxxxx'
          }
      }
  });
  $(".b-r-p").bind('click',function(){
	   var e_target=$(this);
	   if(e_target.is('.disabled')){
		   return;
	   }
	   var store_count=parseInt($("input[name='store_count']").val());
	   if(e_target.is(".reduce")){
		   store_count--;
		   $("input[name='store_count']").val(store_count);
		   if(store_count==1){
			   e_target.addClass("disabled");
		   }
	   }else{
		   store_count++;
		   $("input[name='store_count']").val(store_count);
		   $(".b-r-p.reduce").removeClass("disabled");
	   }
  });
  $("#btn_submit_auth").click(function(){
     $(".auth-form").submit();
  });
});

</script>
