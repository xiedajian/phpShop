<style  type="text/css">
   .zhiyoubao-title{font-size:18px;padding-left:20px;}
   .zhiyoubao-idenitfy{padding:20px 0 40px 20px;}
   .zhiyoubao-idenitfy span{color:#07993d;}
   .zhiyoubao-idenitfy a{text-decoration: initial;color: #FFF;padding:10px 40px; display:inline-block;background:#4a9fd1;border-radius:4px;}
</style>
<div class="zhiyoubao-title">直邮宝服务</div>
<div class="zhiyoubao-idenitfy">
 <?php if($output["is_zhiyoubao"]){?>
     <span>已加入直邮宝</span>
 <?php }else{ ?>
    <a href="javascript:showZhiYouBaoProtocol()">我要加入直邮宝</a>
 <?php } ?>
</div>
<div class="zhiyoubao-remark">
 <img alt="" src="<?php echo SHOP_TEMPLATES_URL?>/images/member/zhiyoubao_remark.png">
</div>
 <?php if(!$output["is_zhiyoubao"]){?>
   <style  type="text/css">
   .zhiyoubao-dialog-bg{position:fixed;left:0;top:0;width:100%;height:100%;background:#000;filter:alpha(opacity=50);-moz-opacity:0.5;-khtml-opacity: 0.5; opacity: 0.5;z-index:1000000; }
   .zhiyoubao-protocol-dialog{width:600px;background:#FFF;position:fixed;left:50%;margin-left:-300px;top:10%;border-radius:3px;z-index:1000001;}
   .zhiyoubao-protocol-dialog .top{text-align:center;font-size:18px;position:relative;padding:10px 0;}
   .zhiyoubao-protocol-dialog .top a{position:absolute;right:5px;top:2px;text-decoration: initial;color: #666;padding: 5px; display: inline-block;font-size:30px;}
   .zhiyoubao-protocol-dialog .content{height:400px;overflow-y:scroll;padding:10px 15px;}
   .zhiyoubao-protocol-dialog .footer .protocol-check{padding:10px 0 10px 15px;}
   .zhiyoubao-protocol-dialog .footer .btn-agree{postion:relative;bottom:0;background:#4a9fd1;color:#FFF;width:100%;display:inline-block;border-top:1px solid #CCC;text-align:center;text-decoration: initial;padding:10px 0;font-size:16px;}
   </style>
    <div style="display:none;"  class="zhiyoubao-dialog-bg"></div>
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
       function showZhiYouBaoProtocol(){
    	   $(".zhiyoubao-dialog-bg").show();
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
            	   location.reload();
               }
           });
       }
    </script>
 <?php }?>