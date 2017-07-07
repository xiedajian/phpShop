<?php defined('InShopNC') or exit('Access Invalid!');?>
<style>
    #timeAlert{
        color:red;
    }
</style>
<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>公告管理</h3>
            <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo '新增';?></span></a></li>
            </ul>
    </div>
    <form id="nitice_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <table class="table tb-type2">
            <tbody>
                <tr class="noborder">
                    <td colspan="2" class="required"><label class="validation" for="title">发布内容:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" value="" name="content" class="txt" required>
                    </td>
                    <td class="vatop tips"></td>
                </tr>

                <tr>
                    <td colspan="2"  class="required"><label class="validation" for="onshelf_time">计划发布日期</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" id="onshelf_time" name="onshelf_time" class="txt date " readonly="readonly" required>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2"  class="required"><label class="validation" for="offshelf_time">计划下架日期</label></td>

                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" id="offshelf_time" name="offshelf_time" class="txt date " readonly="readonly" required>
                    <td class="vatop tips" id="timeAlert"></td>
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
    <script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
    <link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script>
    $(function(){
        $('#menu_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
                content : {
                    required : true
                },
                onshelf_time : {
                    required : true
                },
                offshelf_time : {
                    required : true
                }

            },
            messages : {
                title : {
                    required : '请输入内容'
                },
                onshelf_time : {
                    required : '请选择计划发布日期'
                },
                offshelf_time : {
                    required   : '请选择计划下架日期'
                }
            }
        });
        $("#submitBtn").click(function(){
            if($("#nitice_form").valid()){
                $("#nitice_form").submit();
            }
        });
        $("#onshelf_time").datepicker({dateFormat: 'yy-mm-dd'});
        $("#offshelf_time").datepicker({dateFormat: 'yy-mm-dd'});
        $('#offshelf_time').change(function () {
            var endDate = $(this).val();
            var startDate = $('#onshelf_time').val();

            if(endDate<startDate)
            {
                $('#timeAlert').text('下架日期不能小于发布日期');

            }
        });
    });
</script> 
