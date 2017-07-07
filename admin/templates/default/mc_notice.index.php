<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>公告管理</h3>
        <ul class="tab-base">
            <li><a href="index.php?act=mc_notice&op=add"><span><?php echo '新增';?></span></a></li>
        </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <table class="table tb-type2" id="prompt">
        <tbody>
        <tr class="space odd">
            <th class="nobg" colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
        </tr>
        <tr>
            <td>
                <ul>
                    <li><?php echo $lang['goods_class_index_help1'];?></li>
                </ul></td>
        </tr>
        </tbody>
    </table>
    <table class="table tb-type2">
      <thead >
        <tr class="thead">
        <th  class="w24"  ></th>
          <th>发布人</th>
          <th>发布内容</th>
          <th>计划发布日期</th>
          <th>下架日期</th>
            <th>操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['notice']) && is_array($output['notice'])){ ?>
        <?php foreach($output['notice'] as $k => $v){ ?>
        <tr class="hover">
         <td>
             <?php  if($v['state']!=2) {?>
             <input type="checkbox" name='notice_id' value="<?php echo $v['id']; ?>" class="checkitem">
                <?php } ?>
         </td>
          <td><?php echo $v['publish_name'];?></td>
          <td >
              <?php echo $v['content'];?>
          </td>
          <td><?php echo date('Y-m-d H:i:s',$v['onshelf_time']); ?></td>
            <td><?php echo date('Y-m-d H:i:s',$v['offshelf_time']); ?></td>
          <td>
              <?php if($v['state']!=2){ ?>
                <a href="javascript:if(confirm('你确定要下架该公告吗？'))window.location = 'index.php?act=mc_notice&op=notice_off&state=2&id=<?php echo $v['id'];?>';">下架</a>
                <?php }?>
          &nbsp;&nbsp;<a href="index.php?act=mc_notice&op=edit_notice&id=<?php echo $v['id'];?>';">编辑</a>
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
        <?php if(!empty($output['notice']) && is_array($output['notice'])){ ?>
        <tr class="tfoot">
         <td><label for="checkall1">
              <input type="checkbox" class="checkall" id="checkall_2">
            </label></td>
          <td colspan="2"><label for="checkall_2"><?php echo $lang['nc_select_all'];?></label>
            &nbsp;&nbsp;<a href="JavaScript:void(0);" class="btn_offshelf" ><span>下架</span></a>
          </td>
          <td colspan="6">
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
</div>
<script>
    $(function(){
    $('.btn_offshelf').click(function(){
        //var node = $(this);
       var id=[];
        $("input[name=notice_id]:checked").each(function(i) {
            //if ($(this).attr("checked")) {
                id[i]=$(this).val();
              //}
            });
        if(id.length==0){
        alert('请选择要下架的公告');
        return;
            }
        if(confirm('确定要下架吗？')){
            $.post('index.php?act=mc_notice&op=notice_offs',{'id':id,'state':2},function(data){
                 if(!data.err_code) {
                   alert('下架成功');
                   window.location.reload();
                 }else{
                     alert('下架失败');
                 }
            });
        }
    });
    });
</script>