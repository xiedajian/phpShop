
<form method="get" action="index.php">
  <table class="search-form">
    <input type="hidden" name="act" value="store_goods_online">
    <input type="hidden" name="op" value="getAllOnlyBuyPowerOrg">
    <input type="hidden" name="commonid" value="<?php echo $_GET["commonid"];?>" >
    <tbody><tr>
      <td>&nbsp;</td>
      <th>店铺名称</th>
      <td class="w160"><input type="text" class="text w150" name="org_name" value="<?php echo $_GET['org_name']; ?>"></td>
      <td class="tc w70"><label class="submit-border">
          <input type="submit" class="submit" value="搜索">
        </label></td>
    </tr>
  </tbody></table>
</form>
<table class="ncsc-default-table">
  <thead>
    <tr nc_type="table_header">
       <th>区域</th>
       <th>店铺名称</th>
       <th>联系人</th>
       <th>联系电话</th>
       <th>操作</th>
    </tr>
   </thead>
   <?php if(!empty($output["data_list"])){?>
    <tbody>
      <?php foreach ($output["data_list"] as $val){?>
         <tr>
           <td><?php echo $val["area_name"];?></td>
           <td><?php echo $val["org_name"];?></td>
           <td><?php echo $val["conneter"];?></td>
           <td><?php echo $val["conneter_tel"];?></td>
           <td><a href="<?php echo urlShop("store_goods_online","addOnlyBuyPowerOrg",array("org_id"=>$val["org_id"],"commonid"=>$_GET["commonid"]));?>">设置为该区域独家经销</a></td>
         </tr>
      <?php } ?>
    </tbody>
      <tfoot>
	    <tr>
	      <td colspan="20"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
	    </tr>
	  </tfoot>
   <?php } ?>
</table>

