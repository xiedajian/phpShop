<?php defined('InShopNC') or exit('Access Invalid!');?>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.ajaxContent.pack.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.charCount.js"></script>
<!--[if lt IE 8]>
  <script src="<?php echo RESOURCE_SITE_URL;?>/js/json2.js"></script>
<![endif]-->
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/store_goods_add.step2.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<style type="text/css">
#fixedNavBar { filter:progid:DXImageTransform.Microsoft.gradient(enabled='true',startColorstr='#CCFFFFFF', endColorstr='#CCFFFFFF');background:rgba(255,255,255,0.8); width: 90px; margin-left: 510px; border-radius: 4px; position: fixed; z-index: 999; top: 172px; left: 50%;}
#fixedNavBar h3 { font-size: 12px; line-height: 24px; text-align: center; margin-top: 4px;}
#fixedNavBar ul { width: 80px; margin: 0 auto 5px auto;}
#fixedNavBar li { margin-top: 5px;}
#fixedNavBar li a { font-family: Arial, Helvetica, sans-serif; font-size: 12px; line-height: 20px; background-color: #F5F5F5; color: #999; text-align: center; display: block;  height: 20px; border-radius: 10px;}
#fixedNavBar li a:hover { color: #FFF; text-decoration: none; background-color: #27a9e3;}
</style>


<?php if ($output['edit_goods_sign']) {?>
<div class="tabmenu">
  <?php include template('layout/submenu');?>
</div>
<?php } else {?>
<ul class="add-goods-step">
  <li><i class="icon icon-list-alt"></i>
    <h6>STEP.1</h6>
    <h2>选择商品分类</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li class="current"><i class="icon icon-edit"></i>
    <h6>STEP.2</h6>
    <h2>填写商品详情</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-camera-retro "></i>
    <h6>STEP.3</h6>
    <h2>上传商品图片</h2>
    <i class="arrow icon-angle-right"></i> </li>
  <li><i class="icon icon-ok-circle"></i>
    <h6>STEP.4</h6>
    <h2>商品发布成功</h2>
  </li>
</ul>
<?php }?>
<div class="item-publish">
  <form method="post" id="samples_goods_form" action="<?php if ($output['edit_goods_sign']) { echo urlShop('store_goods_online', 'save_samples');} else { echo urlShop('store_goods_add', 'save_goods');}?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" name="commonid" value="<?php echo $output['goods']['goods_commonid'];?>" />
    <input type="hidden" name="ref_url" value="<?php echo $_GET['ref_url'] ? $_GET['ref_url'] : getReferer();?>" />
    <div class="ncsc-form-goods">
      <h3 id="demo1">样品信息</h3>
      <dl>
        <dt><?php echo $lang['store_goods_index_goods_name'].$lang['nc_colon'];?></dt>
        <dd>
          <input type="text" class="text w400" value="<?php echo $output['goods']['goods_name']; ?>" readonly="readonly" style="background:#E7E7E7 none;"/>
          <span></span>
        </dd>
      </dl>
        <dl>
            <dt><i class="required">*</i><?php echo '是否提供样品'.$lang['nc_colon'];?></dt>
            <dd>
                <label>
                    <input id="have_sample" type="radio" name="open" value="1" <?php if($output['sample_goods_info']['enable']==1)echo 'checked="checked"';?>/>&nbsp;提供
                </label>
                <label id="no_sample" style="margin-left: 10px;">
                    <input type="radio" name="open" value="0" <?php if($output['sample_goods_info']['enable']!=1)echo 'checked="checked"';?>/>&nbsp;不提供
                </label>
                <span></span>
            </dd>
        </dl>
      <dl class="have-sample">
        <dt nc_type="no_spec"><i class="required">*</i><?php echo '样品价格'.$lang['nc_colon'];?></dt>
        <dd nc_type="no_spec">
          <input id="sample_goods_price" name="sample_goods_price" value="<?php echo $output['sample_goods_info']['goods_price']; ?>" type="text"  class="text w60" /><em class="add-on"><i class="icon-renminbi"></i></em> <span></span>
          <p class="hint"><?php echo $lang['store_goods_index_store_price_help'];?>，且不能高于市场价。<br>
            此价格为商品样品价格。</p>
        </dd>
      </dl>
      <dl class="have-sample">
          <dt><i class="required">*</i>限购数量<?php echo $lang['nc_colon'];?></dt>
          <dd>
              <input id="num_limit" name="num_limit" value="<?php echo $output['sample_goods_info']['num_limit']; ?>" type="text" class="text w60" /><span></span>
              <p class="hint">限购数量必须小于商品的最小起订量<br>
              当前商品的最小起订量为<?php echo $output['goods']['goods_common_moq'];?>件</p>
          </dd>
      </dl>
      <dl class="have-sample" nctype="virtual_null" <?php if ($output['goods']['is_virtual'] == 1) {?>style="display:none;"<?php }?>>
            <dt><?php echo $lang['store_goods_index_goods_transfee_charge'].$lang['nc_colon']; ?></dt>
            <dd>
                <ul class="ncsc-form-radio-list">
                    <li>
                        <input id="freight_0" nctype="freight" name="freight" class="radio" type="radio" <?php if (intval($output['sample_goods_info']['transport_id']) == 0) {?>checked="checked"<?php }?> value="0">
                        <label for="freight_0">固定运费</label>
                        <div nctype="div_freight" <?php if (intval($output['sample_goods_info']['transport_id']) != 0) {?>style="display: none;"<?php }?>>
                            <input id="g_freight" class="w50 text" nc_type='transport' type="text" value="<?php printf('%.2f', floatval($output['sample_goods_info']['goods_freight']));?>" name="g_freight"><em class="add-on"><i class="icon-renminbi"></i></em> </div>
                    </li>
                    <li>
                        <input id="freight_1" nctype="freight" name="freight" class="radio" type="radio" <?php if (intval($output['sample_goods_info']['transport_id']) != 0) {?>checked="checked"<?php }?> value="1">
                        <label for="freight_1"><?php echo $lang['store_goods_index_use_tpl'];?></label>
                        <div nctype="div_freight" <?php if (intval($output['sample_goods_info']['transport_id']) == 0) {?>style="display: none;"<?php }?>>
                            <input id="transport_id" type="hidden" value="<?php echo $output['sample_goods_info']['transport_id'];?>" name="transport_id">
                            <input id="transport_title" type="hidden" value="<?php echo $output['sample_goods_info']['transport_title'];?>" name="transport_title">
                            <span id="postageName" class="transport-name" <?php if ($output['sample_goods_info']['transport_title'] != '') {?>style="display: inline-block;"<?php }?>><?php echo $output['sample_goods_info']['transport_title'];?></span><a href="JavaScript:void(0);" onclick="window.open('index.php?act=store_transport&type=select')" class="ncsc-btn" id="postageButton"><i class="icon-truck"></i><?php echo $lang['store_goods_index_select_tpl'];?></a> </div>
                    </li>
                </ul>
                <p class="hint">运费设置为 0 元，前台商品将显示为免运费。</p>
            </dd>
        </dl>
      <dl class="have-sample">
        <dt>拿样规则<?php echo $lang['nc_colon'];?></dt>
        <dd id="ncProductDetails">
          <div class="tabs">
            <ul class="ui-tabs-nav" jquery1239647486215="2">
              <li class="ui-tabs-selected"><a href="#panel-1" jquery1239647486215="8"><i class="icon-desktop"></i> 电脑端</a></li>
              <li class="selected"><a href="#panel-2" jquery1239647486215="9"><i class="icon-mobile-phone"></i>手机端</a></li>
            </ul>
            <div id="panel-1" class="ui-tabs-panel" jquery1239647486215="4">
                <?php if(!$output['sample_goods_info']['goods_rules'])$output['sample_goods_info']['goods_rules']='<p>进货先拿样，批发有保障！</p><p>拼单网为您考虑周到，支持本商品拿样！</p><p>样品随机发货，不挑色、挑码，且每个买家限购数量！</p>';?>
              <?php showEditor('g_body',$output['sample_goods_info']['goods_rules'],'100%','480px','visibility:hidden;',"false",$output['editor_multimedia']);?>
              <div class="hr8">
                <div class="ncsc-upload-btn"> <a href="javascript:void(0);"><span>
                  <input type="file" hidefocus="true" size="1" class="input-file" name="add_album" id="add_album" multiple="multiple">
                  </span>
                  <p><i class="icon-upload-alt" data_type="0" nctype="add_album_i"></i>图片上传</p>
                  </a> </div>
                <a class="ncsc-btn mt5" nctype="show_desc" href="index.php?act=store_album&op=pic_list&item=des"><i class="icon-picture"></i><?php echo $lang['store_goods_album_insert_users_photo'];?></a> <a href="javascript:void(0);" nctype="del_desc" class="ncsc-btn mt5" style="display: none;"><i class=" icon-circle-arrow-up"></i>关闭相册</a> </div>
              <p id="des_demo"></p>
            </div>
            <div id="panel-2" class="ui-tabs-panel ui-tabs-hide" jquery1239647486215="5">
              <div class="ncsc-mobile-editor">
                <div class="pannel">
                  <div class="size-tip"><span nctype="img_count_tip">图片总数得超过<em>20</em>张</span><i>|</i><span nctype="txt_count_tip">文字不得超过<em>5000</em>字</span></div>
                  <div class="control-panel" nctype="mobile_pannel">
                    <?php if (!empty($output['sample_goods_info']['mb_body'])) {?>
                    <?php foreach ($output['sample_goods_info']['mb_body'] as $val) {?>
                    <?php if ($val['type'] == 'text') {?>
                    <div class="module m-text">
                      <div class="tools"><a nctype="mp_up" href="javascript:void(0);">上移</a><a nctype="mp_down" href="javascript:void(0);">下移</a><a nctype="mp_edit" href="javascript:void(0);">编辑</a><a nctype="mp_del" href="javascript:void(0);">删除</a></div>
                      <div class="content">
                        <div class="text-div"><?php echo $val['value'];?></div>
                      </div>
                      <div class="cover"></div>
                    </div>
                    <?php }?>
                    <?php if ($val['type'] == 'image') {?>
                    <div class="module m-image">
                      <div class="tools"><a nctype="mp_up" href="javascript:void(0);">上移</a><a nctype="mp_down" href="javascript:void(0);">下移</a><a nctype="mp_rpl" href="javascript:void(0);">替换</a><a nctype="mp_del" href="javascript:void(0);">删除</a></div>
                      <div class="content">
                        <div class="image-div"><img src="<?php echo $val['value'];?>"></div>
                      </div>
                      <div class="cover"></div>
                    </div>
                    <?php }?>
                    <?php }?>
                    <?php }?>
                  </div>
                  <div class="add-btn">
                    <ul class="btn-wrap">
                      <li><a href="javascript:void(0);" nctype="mb_add_img"><i class="icon-picture"></i>
                        <p>图片</p>
                        </a></li>
                      <li><a href="javascript:void(0);" nctype="mb_add_txt"><i class="icon-font"></i>
                        <p>文字</p>
                        </a></li>
                    </ul>
                  </div>
                </div>
                <div class="explain">
                  <dl>
                    <dt>1、基本要求：</dt>
                    <dd>（1）手机详情总体大小：图片+文字，图片不超过20张，文字不超过5000字；</dd>
                    <dd>建议：所有图片都是本宝贝相关的图片。</dd>
                  </dl><dl>
                    <dt>2、图片大小要求：</dt>
                    <dd>（1）建议使用宽度480 ~ 620像素、高度小于等于960像素的图片；</dd>
                    <dd>（2）格式为：JPG\JEPG\GIF\PNG；</dd>
                    <dd>举例：可以上传一张宽度为480，高度为960像素，格式为JPG的图片。</dd>
                  </dl><dl>
                    <dt>3、文字要求：</dt>
                    <dd>（1）每次插入文字不能超过500个字，标点、特殊字符按照一个字计算；</dd>
                    <dd>建议：不要添加太多的文字，这样看起来更清晰。</dd>
                  </dl>
                </div>
              </div>
              <div class="ncsc-mobile-edit-area" nctype="mobile_editor_area">
                <div nctype="mea_img" class="ncsc-mea-img" style="display: none;"></div>
                <div class="ncsc-mea-text" nctype="mea_txt" style="display: none;">
                  <p id="meat_content_count" class="text-tip"></p>
                  <textarea class="textarea valid" nctype="meat_content"></textarea>
                  <div class="button"><a class="ncsc-btn ncsc-btn-blue" nctype="meat_submit" href="javascript:void(0);">确认</a><a class="ncsc-btn ml10" nctype="meat_cancel" href="javascript:void(0);">取消</a></div>
                  <a class="text-close" nctype="meat_cancel" href="javascript:void(0);">X</a>
                </div>
              </div>
              <input name="m_body" autocomplete="off" type="hidden" value='<?php echo $output['sample_goods_info']['goods_mobile_rules'];?>'>
            </div>
          </div>
        </dd>
      </dl>
    </div>
    <div class="bottom tc hr32">
      <label class="submit-border">
        <input type="submit" class="submit" value="<?php if ($output['edit_goods_sign']) {echo '保存';} else {?><?php echo $lang['store_goods_add_next'];?>，上传商品图片<?php }?>" />
      </label>
    </div>
  </form>
</div>

<script type="text/javascript">
var SITEURL = "<?php echo SHOP_SITE_URL; ?>";
var DEFAULT_GOODS_IMAGE = "<?php echo thumb(array(), 60);?>";
var SHOP_RESOURCE_SITE_URL = "<?php echo SHOP_RESOURCE_SITE_URL;?>";

$(function(){
	//电脑端手机端tab切换
	$(".tabs").tabs();

    $("#no_sample").click(function(){
        $(".have-sample").hide();
        if($("#sample_goods_price").val()==0){
            $("#sample_goods_price").val(0.01);
        }
        if($("#num_limit").val()==0){
            $("#num_limit").val(1);
        }
    });
    <?php if(empty($output['sample_goods_info'])||$output['sample_goods_info']['enable']!=1){?>
        $("#no_sample").click();
    <?php }?>

    $("#have_sample").click(function(){
        $(".have-sample").show();
    });

    $('#samples_goods_form').validate({
        errorPlacement: function(error, element){
            $(element).nextAll('span').append(error);
        },
        <?php if ($output['edit_goods_sign']) {?>
        submitHandler:function(form){
            ajaxpost('samples_goods_form', '', '', 'onerror');
        },
        <?php }?>
        rules : {
            sample_goods_price : {
                required    : true,
                number      : true,
                min         : 0.01,
                max         : 9999999
            },
            num_limit    :  {
                required    : true,
                digits      : true,
                min         : 1,
                max         : 999999999
            }

        },
        messages : {
            sample_goods_price : {
                required    : '<i class="icon-exclamation-sign"></i>请填写样品价',
                number      : '<i class="icon-exclamation-sign"></i>请填写正确的价格',
                min         : '<i class="icon-exclamation-sign"></i>请填写0.01~9999999之间的数字',
                max         : '<i class="icon-exclamation-sign"></i>请填写0.01~9999999之间的数字'
            },
            num_limit    :  {
                required    : '<i class="icon-exclamation-sign"></i>限购数量为空',
                digits      : '<i class="icon-exclamation-sign"></i>请正确输入',
                min         : '<i class="icon-exclamation-sign"></i>最小值为1',
                max         : '<i class="icon-exclamation-sign"></i>数值太大'
            }
        }
    });


});


</script> 
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/scrolld.js"></script>
<script type="text/javascript">$("[id*='Btn']").stop(true).on('click', function (e) {e.preventDefault();$(this).scrolld();})</script>
