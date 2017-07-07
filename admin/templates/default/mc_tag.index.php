<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>标签管理</h3>
    </div>
  </div>
  <div class="fixed-empty"></div>
  
     <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="mc_tag" name="act">
    <input type="hidden" value="index" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>
          <!--//zmr>v30-->

          <td>标签名：<input type="text" value="<?php echo $output['get']['name'];?>" name="name" class="txt"></td>
           <td>状态：<select name="state" >
                            <option value="">请选择</option>
                                <option value="1"  <?php if($output['get']['state']==1){ ?>selected="selected"<?php } ?>>启用</option>
                                <option value="2"  <?php if($output['get']['state']==2){ ?>selected="selected"<?php } ?>>停用</option>
                        </select>
			</td>
          <td><a href="javascript:document.formSearch.submit();" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
            <?php if($output['search_field_value'] != '' or $output['search_sort'] != ''){?>
            <a href="index.php?act=mc_tag&op=index" class="btns "><span><?php echo $lang['nc_cancel_search']?></span></a>
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
        
          <th>标签名</th>
          <th>状态</th>
         
          <th>添加时间</th>
        
          <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['tag']) && is_array($output['tag'])){ ?>
        <?php foreach($output['tag'] as $k => $v){ ?>
        <tr class="hover">
         <td><input type="checkbox" name='material_id' value="<?php echo $v['id']; ?>" class="checkitem"></td>
        
          <td><?php echo $v['name'];?></td>
          
          <td >
              <?php if($v['state']==1){ ?>
              	启用
              <?php }else { ?>
              	停用
              <?php } ?>
          </td>
          <td><?php echo date('Y-m-d H:i:s',$v['add_time']); ?></td>
          
          <td>
          <?php if($v['state']==1){ ?>
          <a href="javascript:if(confirm('确定停用该标签？'))window.location = 'index.php?act=mc_tag&op=tag_del&state=0&id=<?php echo $v['id'];?>';">停用</a>
          <?php }else{ ?>
          <a href="javascript:if(confirm('确定启用该标签？'))window.location = 'index.php?act=mc_tag&op=tag_del&state=1&id=<?php echo $v['id'];?>';">启用</a>
          <?php } ?>
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
        <?php if(!empty($output['tag']) && is_array($output['tag'])){ ?>
        <tr class="tfoot">
         <td><label for="checkall1">
              <input type="checkbox" class="checkall" id="checkall_2">
            </label></td>
          <td colspan="2"><label for="checkall_2"><?php echo $lang['nc_select_all'];?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn_close" ><span>停用</span></a>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn_open" ><span>启用</span></a>
            
            </td>
        
          <td colspan="5">
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
</div>
<script>
                            $(function(){
                                $('.btn_close').click(function(){
                                    //var node = $(this);
                                   var id=[];
                                    $("input[name=material_id]:checked").each(function(i) {  
            							//if ($(this).attr("checked")) {  
               							 	id[i]=$(this).val();  
          								  //}  
        								});
    								if(id.length==0){
									alert('请选择要停用的标签');
									return;
        								}
    								
                                    if(confirm('确定停用？')){
                                       
                                        $.post('index.php?act=mc_tag&op=state',{'id':id,'state':0},function(data){
                                             if(data==1) {
                                               alert('停用成功');
                                               window.location.reload();
                                             }else{
                                                 alert('停用失败');
                                             }
                                        });
                                    }
                                });

                                $('.btn_open').click(function(){
                                    //var node = $(this);
                                   var id=[];
                                    $("input[name=material_id]:checked").each(function(i) {  
            							//if ($(this).attr("checked")) {  
               							 	id[i]=$(this).val();  
          								  //}  
        								});
    								if(id.length==0){
									alert('请选择要启用的标签');
									return;
        								}
    								
                                    if(confirm('确定启用？')){
                                       
                                        $.post('index.php?act=mc_tag&op=state',{'id':id,'state':1},function(data){
                                             if(data==1) {
                                               alert('启用成功');
                                               window.location.reload();
                                             }else{
                                                 alert('启用失败');
                                             }
                                        });
                                    }
                                });
                            });
                        </script>