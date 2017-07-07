<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
  .block-list-title{
    font-weight: 700;
    padding-left: 16px;
  }
  .dialog-goodslist-s1 li,.dialog-goodslist-s2 li{
    width:220px;
    height: 170px;
  }
  .dialog-goodslist-s1 .goods-pic,
  .dialog-goodslist-s2 .goods-pic{ background: rgba(255,255,255,0.8); margin: 1px; border-width: 1px; position:relative; overflow:hidden; z-index:1; zoom:1; cursor: pointer;}
  .dialog-goodslist-s1 .goods-pic:hover,
  .dialog-goodslist-s2 .goods-pic:hover{ border-style: solid; border-width: 2px; margin:0; }
  .dialog-goodslist-s1 .goods-pic:hover{ box-shadow: 2px 2px 0 rgba(153,153,153,0.25);}
  .dialog-goodslist-s2 .goods-pic:hover{ box-shadow: 2px 2px 0 rgba(153,153,153,0.25);}
  .dialog-goodslist-s1 .goods-pic { width: 200px; height: 124px; border-style: solid; border-color: #CBE9F3; }
  .dialog-goodslist-s2 .goods-pic { width: 200px; height: 124px; border-style: solid; border-color: #CBE9F3; }
  .dialog-goodslist-s1 .goods-pic:hover { border-color: #19AEDE;}
  .dialog-goodslist-s2 .goods-pic:hover { border-color: #19AEDE;}
  .dialog-goodslist-s1 .materials-name { width: 220px; margin-top: 10px;text-align: center;}
  .dialog-goodslist-s2 .materials-name { width: 220px; margin-top: 10px;text-align: center;}
  .thumb{text-align: center;}
  .size-200x124{width:193px;height: 124px;}
</style>

<script type="text/javascript">
var SHOP_SITE_URL = "<?php echo SHOP_SITE_URL; ?>";
var UPLOAD_SITE_URL = "<?php echo UPLOAD_SITE_URL; ?>";
</script>
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>首页管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=mc_block&op=index"><span><?php echo '板块区';?></span></a></li>
        <li><a href="index.php?act=mc_block&op=add_block"><span><?php echo '新增板块';?></span></a></li>
        <li><a href="index.php?act=mc_block&op=edit_block_content" class="current"><span><?php echo '编辑板块';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="block_list_form" method="post" name="blockeditForm">
    <input type="hidden" name="form_submit" value="ok" />
    <label>板块名称：<?php echo $output['val']['name'];?>&nbsp;&nbsp;色彩风格：<?php echo $output['style'];?></label>
    <div id="recommend_input_list" style="display:none;"><!-- 推荐拖动排序 --></div>
            <dl>
              <dd>
                <h4 class="dialog-handle-title">推荐商品</h4>
                  <div class="s-tips"><i></i>小提示：单击查询出的商品选中，双击已选择的可以删除，最多8个，保存后生效。</div>
                <ul class="dialog-goodslist-s1 goods-list">
                  <?php $val= $output['val']['content'] ; if(!empty($val) && is_array($val)) { ?>
                      <?php foreach($val as $k => $v) { ?>
                      <li id="select_recommend_<?php echo $key;?>_goods_<?php echo $k;?>">
                        <div ondblclick="del_materials(<?php echo $v['id'];?>);" class="goods-pic">
                        <span class="ac-ico" onclick="del_materials(<?php echo $v['id'];?>);"></span> <span class="thumb size-200x124"><i></i><img select_meterials_id="<?php echo $v['id'];?>" title="<?php echo $v['title'];?>" src="<?php echo $v['pic']?>" onload="javascript:DrawImage(this,200,124);" /></span></div>
                        <div class="materials-name"><?php echo $v[title]?></a></div>
                        <input name="recommend_goods_id[]" value="<?php echo $v['id'];?>" type="hidden">
                        <input name="recommend_goods_name[]" value="<?php echo $v['title'];?>" type="hidden">
                        <input name="recommend_goods_pic[]" value="<?php echo $v['pic'];?>" type="hidden">
                      </li>
                      <?php } ?>
                  <?php }  ?>
                </ul>
              </dd>
            </dl>

    <div id="add_recommend_list" style="display:none;"></div>
    <h4 class="dialog-handle-title">选择要展示的素材</h4>
    <div class="dialog-show-box">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th><label>选择分类</label></th>
          <td class="dialog-select-bar" id="recommend_gcategory">
              <select name="parent_1" id="level_1">
                <option value="0">请选择</option>
                <?php if($output['category']){foreach($output['category'] as $val){?>
                  <option value="<?php echo $val['category']?>"><?php echo $val['name'];?><?php echo $val['state']==0?'（禁用）':'';?></option>
                <?php }}?>
              </select>
              <select name="parent_2" id="level_2">
                <option value="0">请选择</option>
              </select>
              <select name="parent_3" id="level_3">
                <option value="0">请选择</option>
              </select>
		      </td>
        </tr>
        <tr>
          <th><label for="materials_name">素材标题</label></th>
          <td><input type="text" value="" name="materials_name" id="materials_name" class="txt">
		        <a href="JavaScript:void(0);" onclick="get_materials();" class="btn-search "  title="<?php echo $lang['nc_query'];?>"></a>
          	</td>
        </tr>
      </tbody>
    </table>
      <div id="show_materials_list" class="show-recommend-goods-list"></div>
      <div class="clear"></div>
    </div>
    <div class="clear"></div>
    <a href="JavaScript:void(0);" class="btn" id="submitBtn" onclick="$('#block_list_form').submit();"><span><?php echo $lang['nc_submit'];?></span></a>
  </form>
</div>
<script>
  $(function() {
    $("#level_1").change(function () {
      var code = $("#level_1").val();
      if (!code) {
        $("#level_2").empty();
      } else {
        $.getJSON('index.php?act=mc_category&op=ajax_category&code=' + code, function (data) {
          $("#level_2").empty();
          $("#level_2").append('<option value="0">请选择</option>');
          for (var i = 0; i < data.length; i++) {
            $("#level_2").append('<option value="' + data[i]['category'] + '">' + data[i]['name'] + (data[i]['state'] == 0 ? '(禁用)' : '') + '</option>');
          }
        });
      }
    });
    $("#level_2").change(function () {
      var code = $("#level_2").val();
      if (!code) {
        $("#level_3").empty();
      } else {
        $.getJSON('index.php?act=mc_category&op=ajax_category&code=' + code, function (data) {
          $("#level_3").empty();
          $("#level_3").append('<option value="0">请选择</option>');
          for (var i = 0; i < data.length; i++) {
            $("#level_3").append('<option value="' + data[i]['category'] + '">' + data[i]['name'] + (data[i]['state'] == 0 ? '(禁用)' : '') + '</option>');
          }
        });
      }
    });

    $("#level_1").change();
    $("#level_2").change();

  });
</script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/waypoints.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/mc_config/mc_block_config.js"></script>
<script>
  function get_materials() {//查询商品
    var id = 0;
    $('#recommend_gcategory > select').each(function () {
      if ($(this).val() > 0) id = $(this).val();
    });
    var materials_name = $.trim($('#materials_name').val());
    if (id > 0 || materials_name != '') {
      $("#show_materials_list").load('index.php?act=mc_block&op=materials_list&' + $.param({
            'id': id,
            'materials_name': materials_name
          }));
    }
  }
</script>
