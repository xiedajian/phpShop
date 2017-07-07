<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
    $(document).ready(function(){
        $('a[nctype="nyroModal"]').nyroModal();
    });
</script>
<div class="page">
<?php if(strpos($_SERVER['HTTP_REFERER'],"act=member_org")){?>
  <div class="fixed-bar">
    <div class="item-title">
      <h3>店铺认证</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=member_org&op=index"><span>未认证</span></a></li>
        <li><a href="index.php?act=member_org&op=index&auth_statu=1" ><span>通过</span></a></li>
        <li><a href="index.php?act=member_org&op=index&auth_statu=2" ><span>不通过</span></a></li>
        <li><a href="javascript:void(0);" class="current"><span><?php echo $output['member_org_info']['auth_statu']==0?"审核":"查看";?></span></a></li>
      </ul>
    </div>
  </div>
  <?php }?>
<table style="margin-top:80px;" border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="2">店铺认证信息</th>
      </tr>
    </thead>
    <tbody>
     <tr>
        <th>申请人：</th>
        <td><?php echo $output['member_org_info']['applicant_name'];?></td>
     </tr>
      <tr>
        <th>门店名称：</th>
        <td><?php echo $output['member_org_info']['org_name'];?></td>
     </tr>
     <tr>
        <th>门店数：</th>
        <td><?php echo $output['member_org_info']['store_count'];?></td>
     </tr>
      <tr>
        <th>营业执照：</th>
        <td>
         <?php if($output['member_org_info']['license_img']){?>
         <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['license_img']);?>"> <img src="<?php echo $output['member_org_info']['license_img'];?>" alt="" /> </a>
          <?php } ?>
         </td>
      </tr>
      <tr>
        <th>组织机构代码证：</th>
        <td>
         <?php if($output['member_org_info']['organization_certificate_img']){?>
          <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['organization_certificate_img']);?>"> <img src="<?php echo $output['member_org_info']['organization_certificate_img'];?>" alt="" /> </a>
         <?php } ?>
         </td>
      </tr>
      <tr>
        <th>税务登记证：</th>
        <td>
          <?php if($output['member_org_info']['tax_certificate_img']){?>
           <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['tax_certificate_img']);?>" alt="" /> <img src="<?php echo $output['member_org_info']['tax_certificate_img'];?>" alt="" /> </a>
          <?php } ?>
          </td>
      </tr>
      <tr>
        <th>法人姓名：</th><td><?php echo $output['member_org_info']['corporate'];?></td>
      </tr>
      <tr>
        <th>法人身份证：</th>
        <td>
          <?php if($output['member_org_info']['corporate_idcart_img']){?>
           <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['corporate_idcart_img']);?>"> <img src="<?php echo $output['member_org_info']['corporate_idcart_img'];?>" alt="" /> </a>
           <?php } ?>
           <?php if($output['member_org_info']['corporate_idcartf_img']){?>
                <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['corporate_idcartf_img']);?>"> <img src="<?php echo $output['member_org_info']['corporate_idcartf_img'];?>" alt="" /> </a>
            <?php } ?>
        </td>
      </tr>
       <tr>
        <th>联系人：</th><td><?php echo $output['member_org_info']['conneter'];?></td>
      </tr>
       <tr>
        <th>联系人电话：</th><td><?php echo $output['member_org_info']['conneter_tel'];?></td>
      </tr>
       <tr>
        <th>联系人QQ：</th><td><?php echo $output['member_org_info']['conneter_qq'];?></td>
      </tr>
       <tr>
        <th>提交时间：</th><td><?php echo date('Y-m-d H:i:s',$output['member_org_info']['create_time']);?></td>
      </tr>
      <?php if($output['member_org_info']['auth_statu']){?>
      <tr>
        <th>认证状态：</th><td><?php echo $output['member_org_info']['auth_statu']==1?"<font color='green'>通过</font>":"<font color='red'>不通过</font>";?></td>
      </tr>
      <tr>
        <th>报备地区：</th><td><?php echo $output['member_org_info']['area_name'];?></td>
      </tr>
      <tr>
        <th>认证说明：</th><td><?php echo $output['member_org_info']['auth_remark'];?></td>
      </tr>
      <tr>
        <th>认证管理员：</th><td><?php echo $output['member_org_info']['auth_admin'];?></td>
      </tr>
      <tr>
        <th>认证时间：</th><td><?php echo date('Y-m-d H:i:s',$output['member_org_info']['auth_time']);?></td>
      </tr>
      <?php }?>
    </tbody>
  </table>
  <?php if($output['member_org_info']['auth_statu']==0){?>
  <div>
     <style type="text/css">
        .review-form dl{float:left;width:100%;clear:both;padding-top:30px;}
        .review-form dl dt,.review-form dl dd{float:left;}
     </style>
     <form class="review-form" action="index.php?act=member_org&op=member_org_view_save" method="post">
        <input type="hidden" name="org_id" value="<?php echo $output['member_org_info']['org_id'];?>" />
        <dl><dt>审核意见：</dt><dd><input type="radio" checked="checked" name="auth_statu" value="1"/>通过  <input type="radio"  name="auth_statu" value="2"/>不通过</dd></dl>
        <dl><dt>报备地区：</dt><dd><select id="province_id" name="province_id"></select></dd></dl>
        <dl><dt>审核备注：</dt><dd><textarea name="auth_remark" style="width:500px;height:100px;"></textarea></dd></dl>
     </form>
     <div style="clear:both;"></div>
     <div style="padding:30px 0 50px 100px;">
        <a href="JavaScript:history.go(-1);" class="btn"><span>返回</span></a>
        <a href="JavaScript:$('.review-form').submit();" class="btn"><span>提交</span></a>
     </div>
  </div>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
  <script type="text/javascript">
  var area_select = $("#province_id");
  areaInit(area_select,0);//初始化地区
  function area_next(sender,id_name){
      // 删除后面的select
      $(sender).nextAll("select").remove();
      if (sender.value > 0){
          var text = $(sender).get(0).options[$(sender).get(0).selectedIndex].text;
          var area_id = sender.value;
          var EP = new Array();
          EP[1]= true;EP[2]= true;EP[9]= true;EP[22]= true;EP[34]= true;EP[35]= true;
          if(typeof(nc_a[area_id]) != 'undefined'){//数组存在
              var areas = new Array();
              var option = "";
              areas = nc_a[area_id];
          if (typeof(EP[area_id]) == 'undefined'){
              option = "<option value='0'>"+text+"(*)</option>";
          }
          $("<select name='"+id_name+"' id='"+id_name+"'>"+option+"</select>").insertAfter(sender).bind('change',function(){
        	  area_next(this,"district_id");
          });
           for (var i = 0; i <areas.length; i++){
                  $(sender).next("select").append("<option value='" + areas[i][0] + "'>" + areas[i][1] + "</option>");
           }
          }
      }
  }
  $("#province_id").change(function (){
	  area_next(this,"city_id");
   });
  </script>
  <?php } ?>
  
 </div>
