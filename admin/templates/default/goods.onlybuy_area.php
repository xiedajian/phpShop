<style type="text/css">
  .area_box{padding:10px 5px;border:1px solid #CCC;margin-top:20px;border-radius:5px;}
</style>
<div class="page">
   <div>
   <table id="onlybuy_area_table" class="table tb-type2">
      <thead>
        <tr class="thead">
           <th class="w300">区域</th>
           <th>操作</th>
        </tr>
      </thead>
      <tbody>
     <?php if(!empty($output["data_list"])){?>
      <?php foreach ($output["data_list"] as $v){?>
       <tr>
         <td><?php echo $v["area_name"];?></td>
         <td><a onclick="onlybuy_del(this,<?php echo $v["id"];?>,<?php echo $v["goods_commonid"];?>)" href="javascript:void(0);">删除</a></td>
       </tr>
       <?php } ?>
     <?php }?>
     </tbody>
   </table>
   </div>  
   <div class="area_box">
     <select name="province_name" id="province_id"></select>
   </div>
   <div>
       <a href="JavaScript:onlybuy_add(<?php echo $_GET["goods_commonid"];?>);" class="btn"><span>添加</span></a>
   </div>
</div>
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
function onlybuy_add(commonid){
	var area_id=$("#province_id").val(),city_id=0,district_id=0;
	var area_name=$("#province_id option[value='"+area_id+"']").text();
	if(!area_name) return;
	city_id=$("#city_id").val();
	if(city_id!='0'){
		area_id=city_id;
		district_id=$("#district_id").val();
		if(district_id!='0'){
			area_id=district_id;
		}
    }
    $.getJSON("index.php?act=goods&op=onlybuy_area_add",{commonid:commonid,area_id:area_id},function(result){
         if(result.success){
             $("#onlybuy_area_table tbody").append("<tr><td>"+result.area_name+"</td><td><a onclick=\"onlybuy_del(this,"+result.id+","+result.goods_commonid+")\" href=\"javascript:void(0);\">删除</a></td></tr>");
         }
    });
}
function onlybuy_del(sender,id,commonid){
    $.getJSON("index.php?act=goods&op=onlybuy_area_del&id="+id+"&commonid="+commonid,function(result){
        if(result.success){
            sender.closest("tr").remove();
        }
   });
}
</script>