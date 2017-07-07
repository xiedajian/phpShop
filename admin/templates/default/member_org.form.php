<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
    $(document).ready(function(){
        $('a[nctype="nyroModal"]').nyroModal();
    });
</script>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>店铺认证</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=member_org&op=index"><span>未认证</span></a></li>
        <li><a href="index.php?act=member_org&op=index&auth_statu=1" ><span>通过</span></a></li>
        <li><a href="index.php?act=member_org&op=index&auth_statu=2" ><span>不通过</span></a></li>
        <li><a href="javascript:void(0);" class="current"><span>修改</span></a></li>
      </ul>
    </div>
  </div>
  <form id="member_org_form" action="index.php?act=member_org&op=member_org_edit_save" method="post">
  <input type="hidden" name="org_id" value="<?php echo $output['member_org_info']['org_id'];?>">
<table style="margin-top:80px;" border="0" cellpadding="0" cellspacing="0" class="store-joinin">
    <thead>
      <tr>
        <th colspan="20">店铺认证信息修改</th>
      </tr>
    </thead>
    <tbody>
      <tr class="rowform">
        <th>门店名称：</th>
        <td><input  type="text" name="org_name" value="<?php echo $output['member_org_info']['org_name'];?>" class="txt" ></td>
     </tr>
     <tr class="rowform">
        <th>门店数：</th>
        <td><input  type="text" name="store_count" value="<?php echo $output['member_org_info']['store_count'];?>"  class="txt" ></td>
     </tr>
      <tr>
        <th>营业执照：</th>
        <td colspan="20">
          <input type="file" id="license" name="license" />
          <input type="hidden" id="license_img" name="license_img" value="<?php echo $output['member_org_info']['license_img'];?>" />
          <span>
         <?php if($output['member_org_info']['license_img']){?>
              <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['license_img_path']);?>"> <img src="<?php echo $output['member_org_info']['license_img_path'];?>" alt="" /> </a>
          <?php } ?>
          </span>
         </td>
      </tr>
      <tr>
        <th>组织机构代码证：</th>
        <td colspan="20">
          <input type="file" id="organization_certificate" name="organization_certificate" />
          <input type="hidden" id="organization_certificate_img" name="organization_certificate_img" value="<?php echo $output['member_org_info']['organization_certificate_img'];?>" />
          <span>
         <?php if($output['member_org_info']['organization_certificate_img']){?>
              <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['organization_certificate_img_path']);?>"> <img src="<?php echo $output['member_org_info']['organization_certificate_img_path'];?>" alt="" /> </a>
         <?php } ?>
         </span>
         </td>
      </tr>
      <tr>
        <th>税务登记证：</th>
        <td colspan="20">
          <input type="file" id="tax_certificate" name="tax_certificate" />
          <input type="hidden" id="tax_certificate_img" name="tax_certificate_img" value="<?php echo $output['member_org_info']['tax_certificate_img'];?>"/>
          <span>
          <?php if($output['member_org_info']['tax_certificate_img']){?>
              <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['tax_certificate_img_path']);?>"> <img src="<?php echo $output['member_org_info']['tax_certificate_img_path'];?>" alt="" /> </a>
          <?php } ?>
          </span>
          </td>
      </tr>
      <tr class="rowform">
        <th>法人姓名：</th><td><input  type="text" name="corporate" value="<?php echo $output['member_org_info']['corporate'];?>"  class="txt" ></td>
      </tr>
      <tr>
        <th>法人身份证正面：</th>
        <td colspan="20">
           <input type="file" id="corporate_idcart" name="corporate_idcart" />
           <input type="hidden" id="corporate_idcart_img" name="corporate_idcart_img"  value="<?php echo $output['member_org_info']['corporate_idcart_img'];?>"/>
           <span>
          <?php if($output['member_org_info']['corporate_idcart_img']){?>
               <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['corporate_idcart_img_path']);?>"> <img src="<?php echo $output['member_org_info']['corporate_idcart_img_path'];?>" alt="" /></a>
           <?php } ?>
           </span>
          </td>
      </tr>
      <tr>
          <th>法人身份证反面：</th>
          <td colspan="20">
              <input type="file" id="corporate_idcartf" name="corporate_idcartf" />
              <input type="hidden" id="corporate_idcartf_img" name="corporate_idcartf_img"  value="<?php echo $output['member_org_info']['corporate_idcartf_img'];?>"/>
           <span>
          <?php if($output['member_org_info']['corporate_idcartf_img']){?>
              <a nctype="nyroModal"  href="<?php echo str_replace("_s","_b",$output['member_org_info']['corporate_idcartf_img_path']);?>"> <img src="<?php echo $output['member_org_info']['corporate_idcartf_img_path'];?>" alt="" /></a>
          <?php } ?>
           </span>
          </td>
      </tr>
       <tr class="rowform">
        <th>联系人：</th><td><input  type="text" name="conneter" value="<?php echo $output['member_org_info']['conneter'];?>"  class="txt" ></td>
      </tr>
       <tr class="rowform">
        <th>联系人电话：</th><td><input  type="text" name="conneter_tel" value="<?php echo $output['member_org_info']['conneter_tel'];?>"  class="txt" ></td>
      </tr>
       <tr class="rowform">
        <th>联系人QQ：</th><td><input  type="text" name="conneter_qq" value="<?php echo $output['member_org_info']['conneter_qq'];?>"  class="txt" ></td>
      </tr>
      <tr>
        <th>报备地区：</th><td>
        <select id="province_id" name="province_id"></select>
         <?php if($output['member_org_info']['city_id']){ ?>
         <select id="city_id" name="city_id"></select>
         <?php }?>
         <?php if($output['member_org_info']['district_id']){ ?>
         <select id="district_id" name="district_id"></select>
         <?php }?>
        </td>
      </tr>
      <tr>
         <td colspan="21" style="text-align:center;">
         <a href="JavaScript:history.go(-1);"  class="btn"><span>返回</span></a>
         <a href="JavaScript:void(0);" onclick="$('#member_org_form').submit();" class="btn"><span>提交</span></a>
         </td>
      </tr>
    </tbody>
  </table>
  </form>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
  <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
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
	    return size;
	}
	function addImg(e,data){
		var dlObj=$(e.target).parent()
	    if(getfilesize(data)>=4*1024*1024){
	        alert("上传图片不能超过4M");
	        return;
	    }
		dlObj.find("span").text("上传中。。。。");
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
	        $(e.target).parent().find("span").html("<img src='"+param.img_path+"' />");
	    }
	}
	/*图片ajax上传 */
	$('#license').fileupload({
	     dataType: 'json',
	     url: 'index.php?act=member_org&op=member_org_image',
	     formData: {name:'license',member_id:<?php echo $output['member_org_info']['applicant_id'];?>},
	     add: addImg,
	     done: doneImg
	});
	$('#organization_certificate').fileupload({
		  dataType: 'json',
		  url: 'index.php?act=member_org&op=member_org_image',
		  formData: {name:'organization_certificate',member_id:<?php echo $output['member_org_info']['applicant_id'];?>},
		  add: addImg,
		  done: doneImg
	});
	$('#tax_certificate').fileupload({
		  dataType: 'json',
		  url: 'index.php?act=member_org&op=member_org_image',
		  formData: {name:'tax_certificate',member_id:<?php echo $output['member_org_info']['applicant_id'];?>},
		  add: addImg,
		  done: doneImg
	});
	$('#corporate_idcart').fileupload({
		  dataType: 'json',
		  url: 'index.php?act=member_org&op=member_org_image',
		  formData: {name:'corporate_idcart',member_id:<?php echo $output['member_org_info']['applicant_id'];?>},
		  add: addImg,
		  done: doneImg
	});
  $('#corporate_idcartf').fileupload({
      dataType: 'json',
      url: 'index.php?act=member_org&op=member_org_image',
      formData: {name:'corporate_idcartf',member_id:<?php echo $output['member_org_info']['applicant_id'];?>},
      add: addImg,
      done: doneImg
  });
  var area_select = $("#province_id");
  $.ajaxSetup({async:false});
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
  $("#province_id").val(<?php echo $output['member_org_info']['province_id'];?>);
  <?php if($output['member_org_info']['city_id']){ ?>
  areaInit($("#city_id"),<?php echo $output['member_org_info']['province_id'];?>);
  $("#city_id").val(<?php echo $output['member_org_info']['city_id'];?>);
  <?php }?>
  <?php if($output['member_org_info']['district_id']){ ?>
  areaInit($("#district_id"),<?php echo $output['member_org_info']['city_id'];?>);
  $("#district_id").val(<?php echo $output['member_org_info']['district_id'];?>);
  <?php }?>
  $.ajaxSetup({async:true});
  </script>
 </div>
