<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>首页管理</h3>
            <ul class="tab-base">
                <li><a href="index.php?act=mc_block&op=index"><span><?php echo '板块区';?></span></a></li>
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo '新增板块';?></span></a></li>
                <li><a href="index.php?act=mc_block&op=focus_edit"><span><?php echo '焦点区';?></span></a></li>
            </ul>
    </div>
    <div class="fixed-empty"></div>
    <form id="menu_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="id" value="<?php echo $output['val']['id'];?>" />
        <table class="table tb-type2">
            <tbody>
                <tr class="noborder">
                    <td colspan="2" class="required"><label class="validation" for="name">板块名称:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" value="" name="name" class="txt">
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2" class="required"><label class="validation">色彩风格:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform" style=" height:48px;">
                        <script>
                            $(function(){
                                $('.home-templates-board-style').find('li').click(function(){
                                    $('.home-templates-board-style').find('li').removeClass('selected');
                                    $("#style_name").val($(this).attr('class'));
                                    $(this).addClass('selected');
                                });
                            });
                        </script>
                        <input type="hidden" value="default" name="style_name" id="style_name">
                        <ul class="home-templates-board-style">
                            <li class="red"><em></em><i class="icon-ok"></i>红色</li>
                            <li class="pink"><em></em><i class="icon-ok"></i>粉色</li>
                            <li class="orange"><em></em><i class="icon-ok"></i>橘色</li>
                            <li class="green"><em></em><i class="icon-ok"></i>绿色</li>
                            <li class="blue"><em></em><i class="icon-ok"></i>蓝色</li>
                            <li class="purple"><em></em><i class="icon-ok"></i>紫色</li>
                            <li class="brown"><em></em><i class="icon-ok"></i>褐色</li>
                            <li class="default selected"><em></em><i class="icon-ok"></i>默认</li>
                        </ul></td>
                    <td class="vatop tips">选择板块色彩风格将影响商城首页模板该区域的边框、背景色、字体色彩，但不会影响板块的内容布局。</td>
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
