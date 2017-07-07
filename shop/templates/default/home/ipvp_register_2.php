<!doctype html>
<html lang="zh">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>欢迎注册拼单网</title>
<meta name="keywords" content="拼单网注册成功" />
<meta name="description" content="拼单网注册成功" />
<style  type="text/css">
   .body-content{width:1200px;margin:auto;}
   #footer {color: #666;font-size: 12px !important;text-align: center;margin: 0 auto;padding:20px 0 10px 0;overflow: hidden;width: 100%;clear:both;}
   #footer p {color: #666;word-spacing: 5px;padding: 10px 0;}
   #footer a {color: #666;text-decoration: none;}
   .reg-success-top{width:100%;text-align:center;height:180px;background:url('./templates/default/images/reg-succ-icon.png') no-repeat top;border-bottom:1px dashed #CCC;}
   .reg-success-top h1{position:relative;top:125px;color:#f34a9f;}
   .reg-success-body{margin-left:442px;font-size:13px;color:#333;line-height:20px;}
   .reg-success-body .tel{color:#f34a9f;padding-top:10px;font-size:18px;}
   .go-ipvp-index{  display: inline-block;color: #FFF;text-decoration: inherit;font-size: 14px;background: #ec007c;height: 28px;line-height: 28px;width: 300px;text-align: center;margin-top: 20px;}
 </style>
</head>
 <body>
    <div class="body-content">
       <?php require_once template('home/ipvp_reg_top');?>
       <div class="reg-success-top">
           <h1>账号注册成功!</h1>
       </div>
       <div class="reg-success-body">
          <?php if($_GET["is_auth"]){?>
            <p>
                                               我们将在一个工作日内为您审核完成，请您密切关注！
             </br>
                                             认证审核通过后，您可在拼单网上以低于您当地供应商的价格下单购买哦！
            </p>
          <?php }else{?>
            <p>很高兴认识您!</br>您尚未申请店铺认证，无法畅享拼单网更多优惠价格及不能下单购买哦！</p>
            <p><a href="index.php?act=member_org&op=">马上去认证</a></p>
          <?php }?>
          <p>如有问题请联系</br> <span class="tel">400-090-8982</span></p>
          <p>
             <a class="go-ipvp-index" href="<?php echo BASE_SITE_URL;?>">好啦,去逛逛拼单网</a>
          </p>
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
 </body>
</html>
