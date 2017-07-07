<?php defined('InShopNC') or exit('Access Invalid!');?>
<style type="text/css">
    .address-info table{width: 100%; }
    .address-info .title{padding: 10px 0px;font-size: 14px;background-color: #FAFAFA;margin-top: 10px;}
    .address-info input{margin: 3px;width: 100%;text-indent: 10px;padding: 4px 0px;}
    .address-info select{margin: 3px;}
    .address-info table tr td:first-child{width: 80px;}
    .address-info table tr td:last-child{width: 25%;}

    .delivery-info table{width: 100%; }
    .delivery-info .title{padding: 10px 0px;font-size: 14px; background-color: #FAFAFA;margin-top: 10px;}
    .delivery-info input{margin: 3px;width: 100%;text-indent: 10px;padding: 4px 0px;}
    .delivery-info select{margin: 3px;}
    .delivery-info table tr td:first-child{width: 80px;}
    .delivery-info table tr td:last-child{width: 23%;padding-left: 2%}

    .back-btn{display: inline-block;margin-right: 20px;width: 150px;text-align: center;border: 1px solid #dddddd;padding: 5px;cursor: pointer}
    .submit-btn{display: inline-block;color: #ffffff;background-color: #0099FF;width: 150px;text-align: center;padding: 5px;cursor: pointer}
    .area-edit{padding: 4px 8px;background-color:  #dddddd;border-radius: 5px;margin-left: 10px;cursor: pointer}
</style>

<div class="wrap">
    <table class="ncsc-default-table order deliver">
      <tbody>
        <?php if (is_array($output['order_info']) and !empty($output['order_info'])) { ?>
        <tr>
          <td colspan="20" class="sep-row"></td>
        </tr>
        <tr>
          <th colspan="20"><a href="index.php?act=store_order_print&order_id=<?php echo $output['order_info']['order_id'];?>" target="_blank" class="fr" title="<?php echo $lang['store_show_order_printorder'];?>"/><i class="print-order"></i></a><span class="fr mr30"></span><span class="ml10"><?php echo $lang['store_order_order_sn'].$lang['nc_colon'];?><?php echo $output['order_info']['order_sn']; ?></span><span class="ml20"><?php echo $lang['store_order_add_time'].$lang['nc_colon'];?><em class="goods-time"><?php echo date("Y-m-d H:i:s",$output['order_info']['add_time']); ?></em></span>
            </th>
        </tr>
        <tr>
            <th colspan="20" style="background-color: #FEFDDC">
                <span style="margin-left: 10px;">子订单编号：<?php echo $output['sub_order']['sub_order_sn'];?></span>
            </th>
        </tr>
        <?php foreach($output['sub_order']['goods_info'] as $goods_id => $goods_num) { $goods_info =$output['order_goods'][$goods_id];?>
        <tr>
          <td class="bdl w10"></td>
          <td class="w50">
              <div class="pic-thumb">
                  <a href="<?php echo $goods_info['goods_url'];?>" target="_blank">
                      <img src="<?php echo $goods_info['goods_url']; ?>" />
                  </a>
              </div>
          </td>
          <td class="tl" style="border-right: 1px solid #E6E6E6;"><dl class="goods-name">
              <dt><a target="_blank" href="<?php echo $goods_info['goods_url'];?>"><?php echo $goods_info['goods_name']; ?></a></dt>
              <dd><strong>￥<?php echo $goods_info['goods_price']; ?></strong>&nbsp;x&nbsp;<em><?php echo $goods_info['goods_num'];?></em>件</dd>
            </dl>
          </td>
        </tr>
        <?php }?>

        <?php } else { ?>
        <tr>
          <td colspan="20" class="norecord"><i>&nbsp;</i><span><?php echo $lang['no_record'];?></span></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
        <form id="sub_form" action="index.php?act=store_deliver&op=sub_order_edit" method="post">
            <input type="hidden" name="order_id" value="<?php echo $_GET['order_id'];?>">
            <input type="hidden" name="sub_id" value="<?php echo $_GET['sub_id'];?>">
            <input type="hidden" name="form_submit" value="ok">
            <input type="hidden" name="ref" value="<?php echo $output['ref'];?>">
      <div class="address-info">
          <div class="title">设置身份及收货信息</div>
          <table>

              <tr>
                  <td>身份信息</td>
                  <td><input style="width: 90%;"name="buyer_name" placeholder="买家姓名" value="<?php echo $output['sub_order']['buyer_info']['buyer_name'];?>"></td>
                  <td><input style="width: 90%;margin-left: 10%" name="buyer_id_card" placeholder="买家身份证" value="<?php echo $output['sub_order']['buyer_info']['buyer_id_card'];?>"></td>
                  <td><span class="tip"></span></td>
              </tr>
              <tr>
                  <td>收货信息</td>
                  <td><input style="width: 90%;" name="rec_name" placeholder="收货人" value="<?php echo $output['sub_order']['reciver_info']['reciver_name'];?>"></td>
                  <td><input style="width: 90%;margin-left: 10%" name="rec_mobile" placeholder="收货人手机号" value="<?php echo $output['sub_order']['reciver_info']['phone'];?>"></td>
                  <td><span class="tip"></span></td>
              </tr>
              <tr>
                  <td>所在地区</td>
                  <td colspan="2">
                      <input type="hidden" name="area" value="<?php echo $output['sub_order']['reciver_info']['province_id'].','.$output['sub_order']['reciver_info']['city_id'].','.$output['sub_order']['reciver_info']['qx_id'];?>">
                      <?php if($output['sub_order']['reciver_info']['area_info']){?>
                      <div style="padding: 4px 0px;margin: 3px;"><?php echo $output['sub_order']['reciver_info']['area_info'];?><span class="area-edit">编辑</span></div>
                      <?php }?>
                      <div id="region"  <?php if($output['sub_order']['reciver_info']['area_info']) echo 'style="display:none"'?>>
                          <select></select>
                      </div>

                  </td>
                  <td><span class="tip"></span></td>
              </tr>
              <tr>
                  <td>街道地址</td>
                  <td colspan="2"><input name="addr" value="<?php echo $output['sub_order']['reciver_info']['address'];?>"></td>
                  <td><span class="tip"></span></td>
              </tr>
              <tr>
                  <td>邮编</td>
                  <td><input name="postcode" value="<?php echo $output['sub_order']['reciver_info']['postcode'];?>"></td>
                  <td></td>
                  <td><span class="tip"></span></td>
              </tr>
              <tr>
                  <td>备注</td>
                  <td colspan="2"><input name="remark" value="<?php echo $output['sub_order']['remark'];?>"></td>
                  <td><span class="tip"></span></td>
              </tr>
          </table>
      </div>
      <div class="delivery-info">
          <div class="title">设置物流信息</div>
          <table>
              <tr>
                  <td>供应商</td>
                  <td colspan="2"><input name="seller_name" value="<?php echo $output['sub_order']['seller'];?>"></td>
                  <td><span class="tip"></span></td>
              </tr>
              <tr>
                  <td>外部订单号</td>
                  <td colspan="2"><input name="other_sn" value="<?php echo $output['sub_order']['other_order_sn'];?>"></td>
                  <td><span class="tip"></span></td>
              </tr>
              <tr>
                  <td>物流公司</td>
                  <td>
                      <select name="express_id">
                          <?php if($output['my_express_list']){foreach($output['my_express_list'] as $k=>$v){?>
                              <?php if($output['express_list'][$v]['e_name']=='中通快递'){?>
                                  <option value="<?php echo $v;?>" <?php if($v==$output['sub_order']['express_id'])echo 'selected="selected"';?>><?php echo $output['express_list'][$v]['e_name'];?></option>
                                  <?php unset($output['my_express_list'][$k]);break;}?>
                          <?php }}?>
                          <?php if($output['my_express_list']){foreach($output['my_express_list'] as $v){?>
                              <option value="<?php echo $v;?>" <?php if($v==$output['sub_order']['express_id'])echo 'selected="selected"';?>><?php echo $output['express_list'][$v]['e_name'];?></option>
                          <?php }}?>
                      </select>
                  </td>
                  <td><span class="tip"></span></td>
              </tr>
              <tr>
                  <td>物流单号</td>
                  <td><input name="shipping_code" value="<?php echo $output['sub_order']['shipping_code'];?>"></td>
                  <td><span class="tip"></span></td>
              </tr>
          </table>
      </div>
        </form>
      <div style="margin-top: 20px;text-align: center;">
          <?php if($_GET['ref']){?>
          <div class="back-btn" onclick="location.href='<?php echo $_GET['ref'];?>'">返回</div>
          <?php }else{?>
              <div class="back-btn" onclick="location.href='index.php?act=store_deliver&op=take_apart_list'">返回</div>
          <?php }?>
          <div class="submit-btn">提交</div>
      </div>

</div>

<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"/>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.validation.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js"></script>

<script type="text/javascript">
$(function(){
    regionInit("region");
    $('.area-edit').click(function(){
       $(this).closest('div').hide();
        $('#region').show();
    });
    $('#sub_form').validate({
        errorPlacement: function(error, element){
            error.appendTo(element.closest('tr').find('.tip'));
        },
        submitHandler:function(form){
            var area = $("#region");
            if(area.css('display')!='none'){
                var p_id = Number(area.find('select').eq(0).val());
                var c_id = Number(area.find('select').eq(1).val());
                var q_id = Number(area.find('select').eq(2).val());
                if(!isNaN(p_id)&&!isNaN(c_id)&&!isNaN(q_id)){
                    area.closest('tr').find("input[name='area']").val(p_id+','+c_id+','+q_id);area.closest('tr').find('.tip').hide();
                }else{
                //    area.closest('tr').find('.tip').html('<font color="red">请选择地区</font>').show();return false;
                }
            }
            ajaxpost('sub_form', '', '', 'onerror');
           // form.submit();
        },

        rules : {

            <?php if($output['sub_order']['express_id']>0){?>

            express_id : {
                required : true
            },
            shipping_code : {
                required : true
            }
            <?php }?>
        },
        messages : {

            <?php if($output['sub_order']['express_id']>0){?>

            express_id : {
                required :  '请选择物流'
            },
            shipping_code : {
                required :  '请输入物流单号'
            }
            <?php }?>
        }
    });

    $('.submit-btn').click(function(){
        $("#sub_form").submit();
    });
});
</script>
