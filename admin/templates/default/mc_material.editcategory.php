<?php defined('InShopNC') or exit('Access Invalid!');?>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
       <h3>内容管理</h3>
      <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo '变更分类';?></span></a></li>
            </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <p>您已选择希望变更分类的内容列表：</p> 
      <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
       
          <th>编号</th>
          <th>分类</th>
          <th>标题</th>
         
          <th class="align-center">类型</th>
          <th>收费</th>
         
          <th>精品课堂</th>
         
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['list']) && is_array($output['list'])){ ?>
        <?php foreach($output['list'] as $k => $v){ ?>
        <tr class="hover">
      
          <td class="w24 sort">
              <?php echo $v['id'];?>
          </td>
          <td><?php echo $v['category'];?></td>
          <td><?php echo $v['title'];?></td>
         
          
          <td class="w150 align-center">
              <?php if($v['type']==1){ ?>
              	图文
              <?php }else if($v['type']==2){ ?>
              	视频
              <?php }else{ ?>
              	图片
              <?php } ?>
          </td>
          <td>
          <?php if($v['price']!=0){ ?>
          	是
          <?php }else{ ?>
          	否
          <?php } ?>
          </td>
       
          <td>
          <?php if($v['is_jp']){ ?>
          	是
          <?php }else{ ?>
          	否
          <?php } ?> 
          </td>
         
        </tr>
        <?php } ?>
        <?php } ?>
      </tbody>
    </table>
  <form id="menu_form" method="post" name="articleForm">
    <input type="hidden" name="form_submit" value="ok" />
     <input type="hidden" name="id" value="<?php echo $output['get']; ?>" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
              <td colspan="2" class="required"><label class="validation" for="parent_1">选择分类:</label></td>
         </tr>
     	<tr class="noborder">
                    <td class="vatop rowform">
                        <div style="width: 900px;">
                        <select name="parent_1" id="level_1">
                            <option value="">请选择</option>
                            <?php if($output['category']){foreach($output['category'] as $val){?>
                                <option value="<?php echo $val['category']?>"  ><?php echo $val['name'];?><?php echo $val['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select>

                        <select name="parent_2" id="level_2">
                            <option value="">请选择</option>
                            <?php if($output['category2']){foreach($output['category2'] as $val2){?>
                                <option value="<?php echo $val2['category']?>" ><?php echo $val2['name'];?><?php echo $val2['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select>
                         <select name="parent_3" id="level_3">
                            <option value="">请选择</option>
                            <?php if($output['category3']){foreach($output['category3'] as $val3){?>
                                <option value="<?php echo $val3['category']?>" ><?php echo $val3['name'];?><?php echo $val3['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select>
                        </div>
                        <script>
                            $(function(){
                                $("#level_1").change(function(){
                                    var code = $("#level_1").val();
                                    if(!code){
                                        $("#level_2").empty();
                                    }else{
                                        $.getJSON('index.php?act=mc_category&op=ajax_category&code='+code,function(data){
                                            $("#level_2").empty();
                                            $("#level_2").append('<option value="0">请选择</option>');
                                            for(var i=0;i<data.length;i++){
                                                $("#level_2").append('<option value="'+data[i]['category']+'">'+data[i]['name']+(data[i]['state']==0?'(禁用)':'')+'</option>');
                                            }
                                        });
                                    }
                                    $("#level_3").empty(); 
                                    $("#level_3").append('<option value="0">请选择</option>');
                                });
                             //   $("#level_1").change();

                                $("#level_2").change(function(){
                                    var code = $("#level_2").val();
                                    if(!code){
                                        $("#level_3").empty();
                                    }else{
                                        $.getJSON('index.php?act=mc_category&op=ajax_category&code='+code,function(data){
                                            $("#level_3").empty();
                                            $("#level_3").append('<option value="0">请选择</option>');
                                            for(var i=0;i<data.length;i++){
                                                $("#level_3").append('<option value="'+data[i]['category']+'">'+data[i]['name']+(data[i]['state']==0?'(禁用)':'')+'</option>');
                                            }
                                        });
                                    }
                                });
                          //      $("#level_2").change();
                            });
                        </script>
                    </td>
                  
         </tr>

      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
  
    $(function(){
 
        $('#menu_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
            	
               parent_1:{
                   required :true
                       }
                
            },
            messages : {
            	
                parent_1 :{
					required:'请选择分类'
                    }
            }
        });

        $("#submitBtn").click(function(){
          
            if($("#menu_form").valid()){
                $("#menu_form").submit();
            }
        });

    });

</script> 