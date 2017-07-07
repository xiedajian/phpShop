<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎注册拼单网</title>
<meta name="keywords" content="拼单网注册" />
<meta name="description" content="拼单网注册" />
<style  type="text/css">
   .body-content{width:1200px;margin:auto;}
   #footer {color: #666;font-size: 12px !important;text-align: center;margin: 0 auto;padding:20px 0 10px 0;overflow: hidden;width: 100%;clear:both;}
   #footer p {color: #666;word-spacing: 5px;padding: 10px 0;}
   #footer a {color: #666;text-decoration: none;}
   .zhiyoubao-dialog-bg{position:fixed;left:0;top:0;width:100%;height:100%;background:#000;filter:alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity: 0.5; opacity: 0.5; }
   .zhiyoubao-confirm-dialog{width:400px;background:#FFF;position:fixed;left:50%;margin-left:-200px;top:35%;border-radius:3px;}
   .zhiyoubao-confirm-dialog .top{text-align:right;font-size:30px;position:relative;}
   .zhiyoubao-confirm-dialog .top a{  text-decoration: initial;color: #666;padding: 5px; display: inline-block;}
   .zhiyoubao-confirm-dialog .top .title{position:absolute;left:10px;top:5px;font-size:14px;color:#999;}
   .zhiyoubao-confirm-dialog .content{text-align:center;padding:20px 0 40px 0;}
   .zhiyoubao-confirm-dialog .footer>a{width:50%;display:inline-block;border-top:1px solid #CCC;text-align:center;text-decoration: initial;padding:10px 0;font-size:16px;}
   .zhiyoubao-confirm-dialog .footer .btn-no{color:#999;background:#fff;}
   .zhiyoubao-confirm-dialog .footer .btn-yes{background:#4a9fd1;color:#FFF;}
   
   .zhiyoubao-protocol-dialog{width:600px;background:#FFF;position:fixed;left:50%;margin-left:-300px;top:10%;border-radius:3px;}
   .zhiyoubao-protocol-dialog .top{text-align:center;font-size:18px;position:relative;padding:10px 0;}
   .zhiyoubao-protocol-dialog .top a{position:absolute;right:5px;top:2px;text-decoration: initial;color: #666;padding: 5px; display: inline-block;font-size:30px;}
   .zhiyoubao-protocol-dialog .content{height:400px;overflow-y:scroll;padding:10px 15px;}
   .zhiyoubao-protocol-dialog .footer .protocol-check{padding:10px 0 10px 15px;}
   .zhiyoubao-protocol-dialog .footer .btn-agree{postion:relative;bottom:0;background:#4a9fd1;color:#FFF;width:100%;display:inline-block;border-top:1px solid #CCC;text-align:center;text-decoration: initial;padding:10px 0;font-size:16px;}
   </style>
</head>
 <body>
    <div class="body-content">
       <?php require_once template('home/ipvp_reg_top');?>
       <?php require_once template('member/member_org.form');?>
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
  
    <div class="zhiyoubao-dialog-bg"></div>
    <div class="zhiyoubao-confirm-dialog">
       <div class="top"><span class="title">确认</span><a href="javascript:closeZhiYouBaoConfirm()">×</a></div>
       <div class="content">是否加入直邮宝?</div>
       <div class="footer"><a class="btn-no" href="javascript:closeZhiYouBaoConfirm()">不加入，跳过</a><a class="btn-yes" href="javascript:showZhiYouBaoProtocol()">我要加入直邮宝</a></div>
    </div>
    <div style="display:none;"  class="zhiyoubao-protocol-dialog">
       <div class="top">直邮宝协议<a href="javascript:closeZhiYouBaoProtocol()">×</a></div>
       <div class="content">
          <?php require_once template('home/zhiyoubao_protocol');?>          
       </div>
       <div class="footer">
          <div class="protocol-check"><input type="checkbox" onclick="checkZhiYouBaoProtocol(this)" checked="checked">已阅读并接受此协议</div>
          <div>
             <a class="btn-agree" href="javascript:agreeZhiYouBaoProtocol()">确认</a>
          </div>
       </div>
    </div>
    <script type="text/javascript">
       function closeZhiYouBaoConfirm(){
            $(".zhiyoubao-confirm-dialog").hide();
            $(".zhiyoubao-dialog-bg").hide();
       }
       function showZhiYouBaoProtocol(){
    	   $(".zhiyoubao-confirm-dialog").hide();
    	   var w_height=$(window).height()*0.8-140;
    	   $(".zhiyoubao-protocol-dialog .content").height(w_height);
    	   $("body").css("overflow","hidden");
    	   $(".zhiyoubao-protocol-dialog").show();
        }
       function closeZhiYouBaoProtocol(){
    	   $(".zhiyoubao-dialog-bg").hide();
    	   $("body").removeAttr("style");
    	   $(".zhiyoubao-protocol-dialog").hide();
       }
       function checkZhiYouBaoProtocol(sender){
           if(sender.checked){
        	   $(sender).closest(".footer").find(".btn-agree").removeAttr("style");
           }else{
        	   $(sender).closest(".footer").find(".btn-agree").css("background","#CCC");
           }
       }
       function agreeZhiYouBaoProtocol(){
           if(!$(".zhiyoubao-protocol-dialog .protocol-check :checkbox").attr("checked")) return;
           $.get("index.php?act=zhiyoubao&op=agree_protocol",function(result){
               if(result=="ok"){
            	   closeZhiYouBaoProtocol();
               }
           });
       }
    </script>
 </body>
</html>
