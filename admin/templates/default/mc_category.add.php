<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>分类管理</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo '新增';?></span></a></li>
            </ul>
    </div>
    <div class="fixed-empty"></div>
    <form id="menu_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="id" value="<?php echo $output['val']['id'];?>" />
        <table class="table tb-type2">
            <tbody>
                <tr class="noborder">
                    <td colspan="2" class="required"><label class="validation" for="parent">上级分类:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <div style="width: 600px;">
                        <select name="parent_1" id="level_1">
                            <option value="0">请选择</option>
                            <?php if($output['category']){foreach($output['category'] as $val){?>
                                <option value="<?php echo $val['category']?>"><?php echo $val['name'];?><?php echo $val['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select>

                        <select name="parent_2" id="level_2">
                            <option value="0">请选择</option>
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
                                });
                                $("#level_1").change();
                            });
                        </script>
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr class="noborder">
                    <td colspan="2" class="required"><label class="validation" for="name">分类名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" value="" name="name" class="txt">
                    </td>
                    <td class="vatop tips"></td>
                </tr>

                <tr class="noborder">
                    <td colspan="2" class="required"><label class="validation" for="sort">排序:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" value="0" name="sort" class="txt">
                    </td>
                    <td class="vatop tips">数字范围为0~255，数字越小越靠前</td>
                </tr>
                <tr>
                    <td colspan="2"><label for="">状态:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="radio" value="1" name="state" id="state-yes" checked="checked"><label for="state-yes">正常</label>
                        <input type="radio" value="0" name="state" id="state-no"><label for="state-no">禁用</label>
                    </td>
                    <td class="vatop tips"></td>
                </tr>

            </tbody>
            <tfoot>
            <tr>
                <td colspan="2"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script>
    $(function(){
        $('#menu_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
                name : {
                    required : true
                }
            },
            messages : {
                name : {
                    required : '请输入板块名称'
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
