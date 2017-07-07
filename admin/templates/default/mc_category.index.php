<style>
    .del-category{cursor: pointer}
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>分类管理</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=mc_category&op=add"><span><?php echo '新增';?></span></a></li>
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
        <thead>
            <tr class="thead">
                <th></th>
                <th>排序</th>
                <th>分类名称</th>
                <th>状态</th>
                <th style='width:100px;'>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php if(!empty($output['class_list']) && is_array($output['class_list'])){ ?>
                <?php foreach($output['class_list'] as $k => $v){ ?>
                    <tr class="hover edit" field_id="<?php echo $v['id'];?>">
                        <td class="w24">
                            <?php if($v['sub_category']){?>
                                <span op="tree-switch" class="tree-list-open"></span>
                            <?php }?>
                        </td>
                        <td class="w48 sort">
                            <input name="sort" class="editable" value="<?php echo $v['sort'];?>" data-old="<?php echo $v['sort'];?>">
                        </td>
                        <td class="w50pre name">
                            <input name="name" class="editable" value="<?php echo $v['name'];?>" data-old="<?php echo $v['name'];?>">
                        </td>
                        <td>
                            <label for="" class="r cb-enable<?php if($v['state']==1)echo ' selected'; ?>"><span>启用</span></label>
                            <label for="" class="r cb-disable<?php if($v['state']!=1)echo ' selected'; ?>"><span>禁用</span></label>
                        </td>
                        <td class="w84">
                            <span class="del-category">删除</span>
                        </td>
                    </tr>
                    <?php if($v['sub_category']&&is_array($v['sub_category'])){foreach($v['sub_category'] as $vv){?>
                        <tr class="hover edit" parent_id="<?php echo $v['id'];?>" field_id="<?php echo $vv['id'];?>" style="display: none">
                            <td class="w24">
                                <?php if($vv['sub_category']){?>
                                    <span op="tree-switch" class="tree-list-open"></span>
                                <?php }?>
                            <td class="w48 sort">
                                <input name="sort" class="editable" value="<?php echo $vv['sort'];?>" data-old="<?php echo $vv['sort'];?>">
                            </td>
                            <td class="w50pre name">
                                <img fieldid="<?php echo $vv['id'];?>" status="open" nc_type="flex" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-item1.gif">
                                <input name="name" class="editable" value="<?php echo $vv['name'];?>" data-old="<?php echo $vv['name'];?>">
                            </td>
                            <td>
                                <label for="" class="r cb-enable<?php if($vv['state']==1)echo ' selected'; ?>"><span>启用</span></label>
                                <label for="" class="r cb-disable<?php if($vv['state']!=1)echo ' selected'; ?>"><span>禁用</span></label>
                            </td>
                            <td class="w84">
                                <span class="del-category">删除</span>
                            </td>
                        </tr>
                        <?php if($vv['sub_category']&&is_array($vv['sub_category'])){foreach($vv['sub_category'] as $vvv){?>
                            <tr class="hover edit" field_id="<?php echo $vvv['id'];?>" parent_id="<?php echo $vv['id'];?>" style="display: none">
                                <td class="w24"></td>
                                <td class="w48 sort">
                                    <input name="sort" class="editable" value="<?php echo $vvv['sort'];?>" data-old="<?php echo $vvv['sort'];?>">
                                </td>
                                <td class="w50pre name">
                                    <img class="preimg" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/vertline.gif">
                                    <img fieldid="<?php echo $vvv['id'];?>" status="open" nc_type="flex" src="<?php echo ADMIN_TEMPLATES_URL;?>/images/tv-item1.gif">
                                    <input name="name" class="editable" value="<?php echo $vvv['name'];?>" data-old="<?php echo $vvv['name'];?>">
                                </td>
                                <td>
                                    <label for="" class="r cb-enable<?php if($vvv['state']==1)echo ' selected'; ?>"><span>启用</span></label>
                                    <label for="" class="r cb-disable<?php if($vvv['state']!=1)echo ' selected'; ?>"><span>禁用</span></label>
                                </td>
                                <td class="w84">
                                    <span class="del-category">删除</span>
                                </td>
                            </tr>
                        <?php }}?>
                    <?php }}?>
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
    function change_state(obj,open){
        var tr = obj.closest('tr');
        var id = tr.attr('field_id');
        if(open){
            $(obj).removeClass('tree-list-open');
            $(obj).addClass('tree-list-close');
        }else{
            $(obj).removeClass('tree-list-close');
            $(obj).addClass('tree-list-open');
        }
        $(obj).closest('table').find("tr[parent_id='"+id+"']").each(function(){
            if(open){
                $(this).show();
            }else{
                $(this).hide();
            }
            if(!open)
                change_state($(this).find("span[op='tree-switch']"),open);
        });
    }
    $(function(){
        $("span[op='tree-switch']").click(function(){
           var open;
           if($(this).hasClass('tree-list-open')){
               open = true;
           }else{
               open = false;
           }
           change_state($(this),open);
       });
        $(".r").click(function(){
            var node = $(this);
            var id =$(this).closest('tr').attr('field_id');
            var value = 0;
            if($(this).hasClass('cb-enable')){
                value = 1;
            }
            $.post('index.php?act=mc_category&op=edit',{id:id,type:'state',value:value},function(data){
                if(data==1){
                    node.parent().find('.r').removeClass('selected');
                    node.addClass('selected');
                }else{
                    node.removeClass('selected');
                }

            });
        });
        $("input[name='name']").blur(function(){
            var node = $(this);
            var id =$(this).closest('tr').attr('field_id');
            var value = node.val();
            if(value!= node.data('old')){
                if(node.parent().find('span').length==0){
                    node.parent().append('<span></span>');
                }
                $.post('index.php?act=mc_category&op=edit',{id:id,type:'name',value:value},function(data){
                    if(data==1) {
                        node.data('old',value);
                        node.parent().find('span').html('<font color="green">保存成功</font>').show().delay(1000).hide(0);return;
                    }else{
                        node.parent().find('span').html('<font color="red">保存失败</font>').show().delay(1000).hide(0);return;
                    }
                });
            }

        });
        $("input[name='sort']").blur(function(){
            var node = $(this);
            var id =$(this).closest('tr').attr('field_id');
            var value = node.val();
            if(value!= node.data('old')){
                if(node.parent().find('span').length==0){
                    node.parent().append('<span></span>');
                }
                $.post('index.php?act=mc_category&op=edit',{id:id,type:'sort',value:value},function(data){
                    if(data==1) {
                        node.data('old',value);
                        node.parent().find('span').html('<font color="green">保存成功</font>').show().delay(1000).hide(0);return;
                    }else{
                        node.parent().find('span').html('<font color="red">保存失败</font>').show().delay(1000).hide(0);return;
                    }
                });
            }

        });
        $('.del-category').click(function(){
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