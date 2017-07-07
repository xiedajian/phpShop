<?php defined('InShopNC') or exit('Access Invalid!');?>

<style type="text/css">
.evo-colorind-ie{
	position:relative; *top:0/*IE6,7*/ !important;
}
</style>


<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>首页管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=mc_block&op=index"><span><?php echo '板块区';?></span></a></li>
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo '焦点区';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li><?php echo '焦点大图区可设置背景颜色，三张联动区一组三个图片。';?></li>
            <li><?php echo '所有相关设置完成，使用底部的“更新板块内容”前台展示页面才会变化。';?></li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <div class="homepage-focus" id="homepageFocusTab">
    <ul class="tab-menu">
      <li class="current" form="upload_screen_form"><?php echo '全屏(背景)焦点大图';?></li>
    </ul>

  </div>
    <table>
        <tr>
            <td>
                <div class="full-screen-slides">
                    <ul class="ui-sortable">
                        <?php if($output['focus_list']){foreach($output['focus_list'] as $val){?>
                        <li title="可上下拖拽更改显示顺序" data-detail='<?php echo json_encode($val)?>' class="focus-item" focus-id="<?php echo $val['id'];?>">
                            图片调用
                            <a class="del del-focus-item" title="删除">X</a>
                            <div class="focus-thumb" title="点击编辑选中区域内容">
                                <img src="<?php echo '/'.DIR_UPLOAD.'/'.ATTACH_EDITOR.'/'.$val['image_url'];?>">
                            </div>
                        </li>
                        <?php }}?>
                    </ul>
                    <div class="add-focus">
                        <a class="btn-add-nofloat add-focus-btn">图片调用</a>
                        <span class="s-tips"><i></i>小提示：单击图片选中修改，拖动可以排序，添加最多不超过5个，保存后生效。</span>
                    </div>
                </div>
            </td>
            <td>
                <div style="margin-left: 20px;">
                    <form id="form" enctype="multipart/form-data" method="post">
                        <table id="focus-item-detail" class="table tb-type2" style="display:none;">
                            <tbody>
                            <tr>
                                <td colspan="2" class="required"><?php echo '文字标题'.$lang['nc_colon'];?></td>
                            </tr>
                            <tr class="noborder">
                                <td class="vatop rowform">
                                    <input type="hidden" name="form_submit" value="ok" />
                                    <input type="text" name="title" value="" class="txt">
                                    <input type="hidden" name="id" value="" class="txt">
                                <td class="vatop tips">图片标题文字将作为图片Alt形式显示。</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="required"><label>图片跳转链接:</label></td>
                            </tr>
                            <tr class="noborder">
                                <td class="vatop rowform">
                                    <input type="text" name="link" value="" class="txt">
                                </td>
                                <td class="vatop tips">输入图片要跳转的URL地址，正确格式应以"http://"开头，点击后将以"_blank"形式另打开页面。</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="required"><label>排序:</label></td>
                            </tr>
                            <tr class="noborder">
                                <td class="vatop rowform">
                                    <input type="text" name="sort" value="0" class="txt">
                                </td>
                                <td class="vatop tips">0-255数字越小排在越前面</td>
                            </tr>
                            <tr>
                                <td colspan="2" class="required">广告图片上传:</td>
                            </tr>
                            <tr class="noborder">
                                <td class="vatop rowform">
                                <span class="type-file-box">
                                    <input name="image_url" type="file">
                                </span>
                                </td>
                                <td class="vatop tips">为确保显示效果正确，请选择最小不低于W:776px H:300px、最大不超过W:1920px H:481px的清晰图片作为全屏焦点图。</td>
                            </tr>
                            <tr>
                                <td>
                                    <a href="JavaScript:void(0);" id="save_focus" class="btn"><span>提交</span></a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>

            </td>
        </tr>
        <tr>
            <td>
                <a href="JavaScript:void(0);" id="save_all" class="btn"><span>保存</span></a>
                <span>保存排序，删除等</span>
            </td>
            <td>

            </td>
        </tr>
    </table>
</div>

<div style="display: none">
    <li title="可上下拖拽更改显示顺序" data-focus-detail="" id="focus-example" class="focus-item">
        图片调用
        <a class="del del-focus-item" title="删除">X</a>
        <div class="focus-thumb" title="点击编辑选中区域内容">
            <img src="">
        </div>
    </li>
</div>


<iframe style="display:none;" src="" name="upload_pic"></iframe>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.css" rel="stylesheet" type="text/css">
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/colorpicker/evol.colorpicker.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<style>
    .add-focus-btn{cursor: pointer}
</style>
<script>
    function select_focus_item(data){
        var form = $("#focus-item-detail");
        form.show();
        if(typeof data == 'undefined'){
            form.find("input[name='id']").val('');
            form.find("input[name='title']").val('');
            form.find("input[name='link']").val('');
            form.find("input[name='sort']").val('');
        }else{
            form.find("input[name='id']").val(typeof data['id']=='undefined'?'':data['id']);
            form.find("input[name='title']").val(typeof data['title']=='undefined'?'':data['title']);
            form.find("input[name='link']").val(typeof data['link']=='undefined'?'':data['link']);
            form.find("input[name='sort']").val(typeof data['link']=='undefined'?'':data['sort']);
        }

    }
    $(function(){
        $(".ui-sortable").sortable({revert:true});
        $(".focus-item").live("click",function(){
            $(".focus-item").removeClass('selected');
            $(this).addClass('selected');
            var item = $(this).data('detail');
            select_focus_item(item);
        });
        $('.del-focus-item').live("click",function(event){
            event.stopPropagation();
           $(this).closest('li').remove();
        });
        $(".add-focus-btn").click(function(){
            $('.full-screen-slides').find('ul').append($('#focus-example').clone());
        });

        $("#save_focus").click(function(){
           $("#form").submit();
        });

        $("#save_all").click(function(){
            var ids = [];
            $(".full-screen-slides").find('li').each(function(){
                ids.push($(this).attr('focus-id'));
            });
            $.post('index.php?act=mc_block&op=all_focus_edit',{ids:ids.join(',')},function(data){
                location.reload();
            })
        });
    });

</script>

