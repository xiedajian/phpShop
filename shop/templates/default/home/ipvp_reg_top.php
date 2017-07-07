<style  type="text/css">
    body {
     font: 12px/20px "Hiragino Sans GB","Microsoft Yahei",arial,宋体,"Helvetica Neue",Helvetica,STHeiTi,sans-serif;
    }
   .reg-header .left{width:280px;float:left;padding-top:18px;text-align:center;}
   .reg-header .left span{color:#bababa;font-size:13px;}
   .fttff29{float:right;}
   .mod_agree {text-align: center;}
   .mod_agree_con {overflow: hidden;zoom: 1;}
   .mod_agree_item { display: inline; float: left;width: 130px;}
   .mod_agree_item a {color: #666;font: 12px/1.5 "Lucida Grande",tahoma,arial,\5b8b\4f53; display: block;padding-top: 5px;line-height: 18px;}
   .mod_agree_item a img{width:50px;}
   .mod_agree_item strong{color:#bababa;  font-size: 13px;font-weight: normal;  line-height:32px;}
   .header-step{margin-top:25px;}
   .header-step .step>div{float:left;} 
   .header-step .step .step-1,.header-step .step .step-2,.header-step .step .step-3{background-color:#d7d7d7;height:40px; width:380px;text-align:center;line-height:40px;color:#747373;}
   .header-step .step .step-2{width:360px;}
   .header-step .step-current .step-1,.header-step .step-current .step-2,.header-step .step-current .step-3{background-color:#029ee8;color:#fff;}
   .header-step .step .step-right-arrow{width: 0;height: 0;border-top: 20px solid transparent;border-left: 20px solid #d7d7d7;border-bottom: 20px solid transparent;}
   .header-step .step .step-right-arrow-0{width: 0;height: 0;border-top: 20px solid #d7d7d7;border-left: 20px solid transparent;border-bottom: 20px solid #d7d7d7;}
   .header-step .step-current .step-right-arrow{border-left-color:#029ee8;}
   .header-step .step-current .step-right-arrow-0{border-top-color:#029ee8;border-bottom-color:#029ee8;}
   .connet-view{position:fixed;top:35%;right:10px;width:130px;}
   .connet-view .view-1{background:#E1017E;text-align:center;padding:8px 0 3px 0;color:#FFF;}
   .connet-view .view-1 p{margin:1px 0;}
   .connet-view .view-1 img{width:90%;}
   .connet-view .view-2{background:url('./templates/default/images/reg-email-icon.png') no-repeat 0px 2px; background-size: 18px;color:#E1017E;font-size:12px;padding-left:20px;margin:8px 0;}
   .connet-view .view-3{background:url('./templates/default/images/reg-tel-icon.png') no-repeat; background-size: 18px;color:#E1017E;font-size:14px;font-weight:bold; padding-left:25px;}
</style>
    <div class="connet-view">
        <div class="view-1">
         <p>扫一扫 关注我们</p>
         <p><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$GLOBALS['setting_config']['site_logowx']; ?>" ></p>
         <p>WWW.IPVP.CN</p>
        </div>
        <div class="view-2">pindanwang@126.com</div>
        <div class="view-3">400-090-8982</div>
    </div>
      <div class="reg-header">
          <div class="left">
             <a href="<?php echo BASE_SITE_URL;?>"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$output['setting_config']['site_logo']; ?>"></a>
             </br>
             <span>中国最大的母婴联合采购平台</span>
          </div>
          <?php require_once template('layout/index_ensure');?>
          <div style="clear:both;"></div>
      </div>
      <div class="header-step">
        <div class="step step-current">
          <div class="step-1"><span>第一步：设置登录账号</span></div>
          <div class="step-right-arrow"></div>
        </div>
         <div class="step <?php if($_GET['op']=='ipvp_auth'||$_GET["is_auth"]==1){ echo "step-current"; }?>">
          <div class="step-right-arrow-0"></div>
          <div class="step-2"><span>第二步：店铺认证</span></div>
          <div class="step-right-arrow"></div>
        </div>
        <div class="step <?php if($_GET['op']=='ipvp_register_succ'){ echo "step-current"; }?>">
          <div class="step-right-arrow-0"></div>
          <div class="step-3"><span>第三步：注册完成</span></div>
        </div>
      </div>
      <div style="clear:both;"></div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js"></script>