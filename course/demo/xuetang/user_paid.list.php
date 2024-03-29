<?php defined('InShopNC') or exit('Access Invalid!');?>
<?php if(!empty($output['paid_list']) && is_array($output['paid_list'])){ ?>
<div class="table-list">
    <table width="100%">
        <thead>
        <th></th>
        <th>图片</th>
        <th>课程名称</th>
        <th>分类</th>
        <th>类型</th>
        <th>价格</th>
        <th>购买时间</th>
        <th>操作</th>
        </thead>
        <tbody>
        <?php foreach($output['paid_list'] as $k => $v){ ?>
            <tr>
                <td width="30px" >
                    <input type="checkbox" name='paid_id' value="<?php echo $v['id']; ?>" class="checkitem">
                </td>
                <td width="150px"><img src ="<?php echo $v['thumb'] ?>" style="width:100px;height: 60px;" ></td>
                <td ><?php echo $v['title'] ?></td>
                <td ><?php echo $v['category'] ?></td>
                <td ><?php echo $v['type'] ?></td>
                <td ><?php echo $v['price'] ?></td>
                <td ><?php echo date('Y-m-d H:i:s',$v['buy_time']); ?></td>
                <td ><span class="blue_color"><a href="#">查看</a></span><span class="blue_color pd_left15">
                                        <a href="javascript:void(0);">下载</a></span></td>
            </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <tr class="tfoot"><label for="checkall1">
                <td ><input type="checkbox" class="checkall" id="checkall_2"></label></td>
            <td style="text-align:left"><label for="checkall_2">全选</label>
                &nbsp;&nbsp;<a href="JavaScript:void(0);" class="pg_btn"><span>批量下载</span></a>
            </td>
            <td colspan="6">
                <div class="pagination"> <?php echo $output['page'];?> </div>
            </td>
        </tr>

        <?php }else { ?>
            <tr class="no_data">
                <td colspan="13"><?php echo $lang['nc_no_record'];?></td>
            </tr>
        <?php } ?>
        </tfoot>
    </table>
</div>

<script type="text/javascript">

    $('.myclass_list .demo').ajaxContent({
        target:'.myclass_list'
    });
    $('.pg_btn').click(function(){
        //var node = $(this);
        var id=[];
        $("input[name=usercollection_id]:checked").each(function(i) {
            //if ($(this).attr("checked")) {
            id[i]=$(this).val();
            //}
        });
        if(id.length==0){
            alert('请选择要取消收藏的素材');
            return;
        }
        if(confirm('确定要取消收藏吗？')){
            $.post('index.php?act=myclass&op=usercollection_offs',{'id':id,'state':0,'tab':'collection'},function(data){
                if(!data.err_code) {
                    alert('取消收藏成功');
                    window.location.reload();
                }else{
                    alert('取消收藏失败');
                }
            });
        }
    });
</script>
<script src="<?php echo COURSE_TEMPLATES_URL;?>/js/common.js"></script>