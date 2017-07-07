
<div class="tabmenu">
  <?php include template('layout/submenu');?>
  <a href="<?php echo urlShop("store_goods_online","getAllOnlyBuyPowerOrg",array("commonid"=>$_GET["commonid"]));?>" class="ncsc-btn ncsc-btn-green" >增加经销店铺</a></div>
<table class="ncsc-default-table">
  <thead>
    <tr nc_type="table_header">
       <th>区域</th>
       <th>店铺名称</th>
       <th>联系人</th>
       <th>联系电话</th>
       <th>删除</th>
    </tr>
   </thead>
   <?php if(!empty($output["data_list"])){?>
    <tbody>
      <?php foreach ($output["data_list"] as $val){?>
         <tr>
           <td><?php echo $val["area_name"];?></td>
           <td><?php echo $val["member_orgname"];?></td>
           <td><?php echo $val["conneter"];?></td>
           <td><?php echo $val["conneter_tel"];?></td>
           <td><a href="<?php echo urlShop("store_goods_online","delOnlyBuyPowerOrg",array("id"=>$val["id"],"commonid"=>$_GET["commonid"]));?>">删除</a></td>
         </tr>
      <?php } ?>
    </tbody>
   <?php } ?>
</table>
</div>
