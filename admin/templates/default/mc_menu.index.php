<style>
    .op{cursor: pointer}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>首页导航栏</h3>
            <?php echo $output['top_link'];?>
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
        <thead>
            <tr class="thead">
                <th>排序</th>
                <th>标题</th>
                <th>地址</th>
                <th>新窗口打开</th>
                <th>状态</th>
                <th style='width:100px;'>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($output['menu_list']) && is_array($output['menu_list'])){ ?>
                <?php foreach($output['menu_list'] as $k => $v){ ?>
                    <tr class="hover edit" field_id="<?php echo $v['id'];?>">
                        <td class="w48 sort">
                            <input name="sort" class="editable" value="<?php echo $v['sort'];?>" data-old="<?php echo $v['sort'];?>">
                        </td>
                        <td class="">
                            <?php echo $v['title'];?>
                        </td>
                        <td class="">
                            <a href="<?php echo $v['link'];?>"><?php echo $v['link'];?></a>
                        </td>
                        <td class="">
                            <?php echo $v['blank']==1?'是':'否';?>
                        </td>
                        <td>
                            <?php echo $v['state']==1?'正常':'禁用';?>
                        </td>
                        <td class="w84">
                            <a href="index.php?act=mc_menu&op=edit_menu&id=<?php echo $v['id'];?>"><span class="op edit-menu">编辑</span></a>|
                            <span class="op del-menu">删除</span>
                        </td>
                    </tr>
                <?php } ?>
            <?php }else { ?>
                <tr class="no_data">
                    <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>
<script>

    $(function(){
        $("input[name='sort']").blur(function(){
            var node = $(this);
            var id =$(this).closest('tr').attr('field_id');
            var value = node.val();
            if(value!= node.data('old')){
                if(node.parent().find('span').length==0){
                    node.parent().append('<span></span>');
                }
                $.post('index.php?act=mc_menu&op=edit',{id:id,type:'sort',value:value},function(data){
                    if(data==1) {
                        node.data('old',value);
                        node.parent().find('span').html('<font color="green">保存成功</font>').show().delay(1000).hide(0);return;
                    }else{
                        node.parent().find('span').html('<font color="red">保存失败</font>').show().delay(1000).hide(0);return;
                    }
                });
            }

        });

        $('.del-menu').click(function(){
            var node = $(this);
            if(confirm('确定删除？')){
                var id =$(this).closest('tr').attr('field_id');
                $.post('index.php?act=mc_category&op=del',{id:id},function(data){
                    if(data==1) {
                        node.closest('tr').remove();
                    }else{
                        alert('删除失败');
                    }
                });
            }
        });
    });



</script>