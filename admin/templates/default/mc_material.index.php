<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>内容管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=mc_material&op=add"><span><?php echo '新增';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
     <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="mc_material" name="act">
    <input type="hidden" value="index" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>
          <!--//zmr>v30-->
          选择分类：
          <select name="parent_1" id="level_1">
                            <option value="">请选择</option>
                            <?php if($output['category']){foreach($output['category'] as $val){?>
                                <option value="<?php echo $val['category']?>"  <?php if($val['category']==$output['get']['parent_1']){ ?>selected="selected"<?php } ?>><?php echo $val['name'];?><?php echo $val['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select>

                        <select name="parent_2" id="level_2">
                            <option value="">请选择</option>
                            <?php if($output['category2']){foreach($output['category2'] as $val2){?>
                                <option value="<?php echo $val2['category']?>"  <?php if($val2['category']==$output['get']['parent_2']){ ?>selected="selected"<?php } ?>><?php echo $val2['name'];?><?php echo $val2['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select>
                         <select name="parent_3" id="level_3">
                            <option value="">请选择</option>
                            <?php if($output['category3']){foreach($output['category3'] as $val3){?>
                                <option value="<?php echo $val3['category']?>"  <?php if($val3['category']==$output['get']['parent_3']){ ?>selected="selected"<?php } ?>><?php echo $val3['name'];?><?php echo $val3['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select></td>
          <td>标题：<input type="text" value="<?php echo $output['get']['title'];?>" name="title" class="txt"></td>
		  <td>类型：<select name="type" >
                            <option value="">请选择</option>
                                <option value="1"  <?php if($output['get']['type']==1){ ?>selected="selected"<?php } ?>>图文</option>
                                <option value="2"  <?php if($output['get']['type']==2){ ?>selected="selected"<?php } ?>>视频</option>
                                <option value="3"  <?php if($output['get']['type']==3){ ?>selected="selected"<?php } ?>>图片</option>
                        </select>
			</td>
			<td>付费类型：
			<select name="price" >
                            <option value="">请选择</option>
                                <option value="1"  <?php if($output['get']['price']==1){ ?>selected="selected"<?php } ?>>收费</option>
                                <option value="2"  <?php if($output['get']['price']==2){ ?>selected="selected"<?php } ?>>免费</option>
                        </select>
			</td>
			<td>精品课堂：
			<select name="is_jp" >
                            <option value="">请选择</option>
                                <option value="1"  <?php if($output['get']['is_jp']==1){ ?>selected="selected"<?php } ?>>是</option>
                                <option value="2"  <?php if($output['get']['is_jp']==2){ ?>selected="selected"<?php } ?>>否</option>
                        </select>
			</td>
          <td><a href="javascript:document.formSearch.submit();" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?act=mc_material&op=index" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
            <?php }?>
          </td>

        </tr>
      </tbody>
    </table>
  </form>
  
  
<!--    <table class="table tb-type2" id="prompt">-->
<!--     <tbody> -->
<!--       <tr class="space odd"> -->
<!--       <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>-->
<!--       </tr> -->
<!--       <tr> -->
<!--         <td> -->
<!--         <ul> -->
<!--             <li>排序越小越靠前，可以控制板块显示先后。</li> -->
<!--             <li>色彩风格和前台的样式一致，在基本设置中选择更换。</li> -->
<!--             <li>色彩风格是css样式中已经有的，如果需要修改名称则相关程序也要同时改变才会有效果。</li> -->
<!--           </ul></td> -->
<!--       </tr> -->
<!--     </tbody> -->
<!--   </table> -->
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
        <th class="w24"></th>
          <th>编号</th>
          <th>分类</th>
          <th>标题</th>
          <th class="align-center">图片</th>
          <th class="align-center">tag标签</th>
          <th class="align-center">类型</th>
          <th>收费</th>
          <th>发布人</th>
          <th>精品课堂</th>
          <th>发布时间</th>
          <th>浏览次数</th>
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['materiallist']) && is_array($output['materiallist'])){ ?>
        <?php foreach($output['materiallist'] as $k => $v){ ?>
        <tr class="hover">
         <td><input type="checkbox" name='material_id' value="<?php echo $v['id']; ?>" class="checkitem"></td>
          <td class="w24 sort">
              <input type="text" value="<?php echo $v['id'];?>" class="editable">
          </td>
          <td><?php echo $v['category'];?></td>
          <td><?php echo $v['title'];?></td>
          <td class="w150 align-center">
          <div class="size-64x64">
          <span class="thumb"><i></i><img src="<?php echo $v['thumb'];?>" alt="<?php echo $v['title']; ?>" width="64px" height="64px"/></span>
          </div>
          </td>
          <td class="w150 align-center"><?php echo $v['tag'];?></td>
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
          <td><?php echo $v['publish_name']; ?></td>
          <td>
          <?php if($v['is_jp']){ ?>
          	是
          <?php }else{ ?>
          	否
          <?php } ?> 
          </td>
          <td><?php echo date('Y-m-d H:i:s',$v['add_time']); ?></td>
          <td><?php echo $v['view_times']; ?></td>
          <td> <a href="index.php?act=mc_material&op=material_edit&id=<?php echo $v['id'];?>">编辑</a> |
          <a href="javascript:if(confirm('确定删除该内容？'))window.location = 'index.php?act=mc_material&op=material_del&id=<?php echo $v['id'];?>';">删除</a>
          </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="13"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['materiallist']) && is_array($output['materiallist'])){ ?>
        <tr class="tfoot">
         <td><label for="checkall1">
              <input type="checkbox" class="checkall" id="checkall_2">
            </label></td>
          <td colspan="2"><label for="checkall_2"><?php echo $lang['nc_select_all'];?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn_del" ><span><?php echo $lang['nc_del'];?></span></a>
         &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn_category" ><span>变更分类</span></a></td>
          <td colspan="13">
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
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
                               // $("#level_1").change();
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
                               // $("#level_2").change();
      $('.btn_del').click(function(){
                                    //var node = $(this);
        var id=[];
        $("input[name=material_id]:checked").each(function(i) {  
            							//if ($(this).attr("checked")) {  
        id[i]=$(this).val();  
          								  //}  
        });
    	if(id.length==0){
		alert('请选择要删除的内容');
		return;
        }
    								
        if(confirm('确定删除？')){
                                       
        $.post('index.php?act=mc_material&op=del',{'id':id},function(data){
         if(data==1) {
          alert('删除成功');
          window.location.reload();
         }else{
          alert('删除失败');
          }
         });
         }
       });

      //修改分类
      $('.btn_category').click(function(){
          //var node = $(this);
				var id=[];
			$("input[name=material_id]:checked").each(function(i) {  
				//if ($(this).attr("checked")) {  
					id[i]=$(this).val();  
				  //}  
				});
				if(id.length==0){
				alert('请选择要变更的内容');
					return;
				}
			location.href='index.php?act=mc_material&op=editcategory&id='+id;
				
			});

     });
</script>