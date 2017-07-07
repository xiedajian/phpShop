<style type="text/css">
   .top-remark{padding-left:60px;}
   .top-remark i{color:red;margin-right:5px;}
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

    .auth-view{margin-left:145px;margin-top:20px;}
    .auth-view dl{float:left;  margin: 10px 0;clear:both;}
    .auth-view dl dt{width:150px;float:left;  text-align: left;color: #999;font-size: 13px;line-height: 30px;position:relative;}
    .auth-view dl dd{float:left;margin-left:0;height:30px;line-height:30px;font-size:15px;color:#333;}
    .auth-view .img-dl dt{height:80px;line-height:80px;}
    .auth-fail-error{margin-top:30px;}
    .auth-fail-error h1{background:url('./templates/default/images/member-auth-error-icon.png') no-repeat 50px 6px ;color:#f70903;padding-left:120px;height:65px;line-height:65px;font-size:2em;}
    .auth-fail-error span{margin-left:145px;display:block;}
    .auth-fail-error span i{width:8px;height:8px;border-radius:50%;background:#666;  display: inline-block;margin-right:5px;}
    .auth-fail-error a{margin-left:145px;background:#ec007c;width:206px;height:30px;line-height:30px;display:inline-block;color:#FFF;text-decoration: inherit;font-size:14px;padding:0;text-align:center;}
    
    .submit-auth-remark{background:url('./templates/default/images/member-authing-icon.png') no-repeat 250px 80px ;color:#333;padding-left:380px;height:300px;padding-top:78px;}
     .submit-auth-remark h2{font-size:2em;color:#f34a9f;line-height:40px;}
     .submit-auth-remark h3{font-size:1.2em;color:#f34a9f;}
    </style>
<div class="top-remark">
  <i>*</i>承诺所传身份证明、营业执照等只用于购买本平台商品并为您优先处理退款退货等售后问题，不作他途使用，其他任何人均无法查看。
</div>
<div class="auth-header-step">
    <div class="step step-current">
        <div class="step-1"><span>第一步：店铺认证申请</span></div>
        <div class="step-right-arrow"></div>
    </div>
    <div class="step step-current">
        <div class="step-right-arrow-0"></div>
        <div class="step-2"><span>第二步：工作人员审核</br>(一个工作日内审核)</span></div>
        <div class="step-right-arrow"></div>
    </div>
    <div class="step">
        <div class="step-right-arrow-0"></div>
        <div class="step-3"><span>第三步：认证成功</span></div>
    </div>
</div>
<div style="clear:both;"></div>
<?php if($_GET["submit"]){?>
     <div class="submit-auth-remark">
          <h2>您的认证申请已提交！</h2>
                                   我们将在一个工作日内为您审核。
          </br>
                                  如有疑问，请联系拼单网客服热线
          <h3>400-090-8982</h3>
     </div>
<?php } else {?>
<?php if($output['member_org_info']['auth_statu']==2){?>
	<div class="auth-fail-error">
	   <h1><?php echo $output['member_org_info']['auth_remark'];?></h1>
	   </br>
	   <span><i></i>请确保这些信息的完整、真实和准确</span>
	   </br>
	   <a href="index.php?act=member_org&reset_auth=1">重新认证</a>
	   </br>
	   </br>
	</div>
<?php }?>

<div class="auth-view">
	    <dl>
	       <dt>门店名称</dt>
	       <dd><?php echo $output["member_org_info"]["org_name"];?></dd>
	     </dl>
	      <dl>
	       <dt>门店数</dt>
	       <dd><?php echo $output["member_org_info"]["store_count"];?></dd>
	     </dl>
	     <dl class="img-dl">
	       <dt>营业执照</dt>
	       <dd>
	          <?php if($output["member_org_info"]["license_img"]){?>
	             <img alt="" src="<?php echo $output["member_org_info"]["license_img_path"];?>"/>
	          <?php }?>
	       </dd>
	     </dl>
	     <dl class="img-dl">
	       <dt>组织机构代码证</dt>
	       <dd>
	          <?php if($output["member_org_info"]["organization_certificate_img"]){?>
	             <img alt="" src="<?php echo $output["member_org_info"]["organization_certificate_img_path"];?>"/>
	          <?php }?>
	       </dd>
	     </dl>
	     <dl class="img-dl">
	       <dt>税务登记证</dt>
	       <dd>
	          <?php if($output["member_org_info"]["tax_certificate_img"]){?>
	             <img alt="" src="<?php echo $output["member_org_info"]["tax_certificate_img_path"];?>"/>
	          <?php }?>
	       </dd>
	     </dl>

	    <dl>
	       <dt>法人姓名</dt>
	       <dd><?php echo $output["member_org_info"]["corporate"];?></dd>
	     </dl>
	     <dl class="img-dl">
	       <dt><i></i>法人身份证</dt>
	       <dd>
	          <?php if($output["member_org_info"]["corporate_idcart_img"]){?>
	             <img alt="" src="<?php echo $output["member_org_info"]["corporate_idcart_img_path"];?>"/>
	          <?php }?>
	       </dd>
	     </dl>

	    <dl>
	       <dt>联系人姓名</dt>
	       <dd><?php echo $output["member_org_info"]["conneter"];?></dd>
	    </dl>
	    <dl>
	       <dt>联系人电话</dt>
	       <dd><?php echo $output["member_org_info"]["conneter_tel"];?></dd>
	    </dl>
	    <dl>
	       <dt>联系人QQ</dt>
	       <dd><?php echo $output["member_org_info"]["conneter_qq"];?></dd>
	    </dl>
</div>
<?php } ?>
