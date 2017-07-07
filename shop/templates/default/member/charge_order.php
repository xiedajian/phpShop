<?php defined('InShopNC') or exit('Access Invalid!');?>
<style>
    a:hover{
        color: #333;
    }

</style>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />

<div class="wrap">
    <div class="tabmenu">
        <?php include template('layout/submenu');?>
    </div>
    <form method="get" action="index.php" target="_self">
        <table class="ncm-search-table">
            <input type="hidden" name="act" value="member_order" />
            <input type="hidden" name="op" value="charge_order" />
            <tr>
                <td>&nbsp;</td>
                <th><?php echo $lang['member_order_state'];?></th>
                <td class="w100"><select name="state_type">
                        <option value="" <?php echo $_GET['state_type']==''?'selected':''; ?>><?php echo $lang['member_order_all'];?></option>
                        <option value="10" <?php echo $_GET['state_type']=='10'?'selected':''; ?>>等待支付</option>
                        <option value="40" <?php echo $_GET['state_type']=='40'?'selected':''; ?>>购买成功</option>
                        <option value="0" <?php echo $_GET['state_type']=='0'?'selected':''; ?>>订单关闭</option>
                    </select></td>
                <th><?php echo $lang['member_order_time'];?></th>
                <td class="w240"><input type="text" class="text w70" name="query_start_date" id="query_start_date" value="<?php echo $_GET['query_start_date']; ?>"/><label class="add-on"><i class="icon-calendar"></i></label>&nbsp;&#8211;&nbsp;<input type="text" class="text w70" name="query_end_date" id="query_end_date" value="<?php echo $_GET['query_end_date']; ?>"/><label class="add-on"><i class="icon-calendar"></i></label></td>
                <th><?php echo $lang['member_order_sn'];?></th>
                <td class="w160"><input type="text" class="text w150" name="order_sn" value="<?php echo $_GET['order_sn']; ?>"></td>
                <td class="w70 tc"><label class="submit-border">
                        <input type="submit" class="submit" value="<?php echo $lang['nc_search'];?>"/>
                    </label></td>
            </tr>
        </table>
    </form>

    <table class="ncm-default-table charge_table">
        <thead>
        <tr>
            <th >订单号</th>
            <th >所属产品</th>
            <th>付费许可类型</th>
            <th>许可购买日期</th>
            <th>订单支付日期</th>
            <th>许可到期日期</th>
            <th>购买数量</th>
            <th>订单金额</th>
            <th>订单状态</th>
            <th>订单操作</th>
        </tr>
        </thead>
        <tbody>
        <?php if($output["order_list"]) { ?>
            <?php foreach($output["order_list"] as $list) { ?>
                <tr>
                    <td><?php echo $list["order_no_v2"]; ?></td>
                    <td>客满分</td>
                    <td><?php if($list['charge_data']['app_code']=="KMF"){
                            if($list["charge_data"]['licences_months']==6){
                                echo "半年期付费许可";
                            }else{
                                echo "一年期付费许可";
                            }
                        }elseif($list["charge_data"]["app_code"]=="ZG"){
                            echo "增购执行人员";
                        }elseif($list["charge_data"]["app_code"]=="DPZS"){
                            echo "店铺指数";
                        }elseif($list["charge_data"]["app_code"]=="MRJ"){
                            echo "每人计";
                        } ?>
                        </td>
                    <td><?php echo date('Y-m-d',$list["add_time"]); ?> </td>
                    <td><?php if($list["payment_time"]){echo  date('Y-m-d',$list["payment_time"]);}else{echo "---";}?> </td>
                    <td><?php if($list["order_state"]==40 ){echo $list["charge_data"]["expire_date"];}else{echo "---";} ?> </td>
                    <td><?php if($list["charge_data"]["licences"]){echo $list["charge_data"]["licences"];}else{echo 1;};?></td>
                    <td class="order_money"><?php echo "￥".$list["order_amount"]; ?></td>
                    <td class="over_time"><?php if($list["order_state"]==10){
                            echo "等待支付";
                        }elseif($list["order_state"]==40){
                            echo "购买成功";
                        }else{
                            echo "订单关闭";
                        }
                        ?></td>
                    <td>
                        <div>
                            <p><a class="text_decorate" href="javascript:void(0)"  nc_type="dialog"  dialog_width="580" dialog_title="订单详情" dialog_id="buyer_order_cancel_order" uri="index.php?act=member_order&op=charge_order_info&order_sn=<?php echo $list["order_sn"];?>"  id="order<?php echo $order_info['order_id']; ?>_action_cancel">订单详情</a></p>
                        </div>
                <?php if($list["order_state"]==10){ ?>
                    <div style="margin-top: 5px; margin-bottom: 5px;"><a class="charge_btn" href="index.php?act=buy&op=pay&pay_sn=<?php echo $list["pay_sn"]; ?>" style="text-decoration:none;">立即支付</a></div>
                    <div>（距订单关闭：<?php echo $list["days_data"]; ?> ）</div>
                    <?php }?>
                        <?php if($list["order_state"]!=10){?>
                            <a href="javascript:void(0);" class="text_decorate" onclick="ajax_get_confirm('您确定要删除吗?', 'index.php?act=member_order&op=charge_delete_order&order_id=<?php echo $list['order_id']; ?>');">删除订单</a>
                        <?php }?>
                    </td>
                </tr>

                <?php
            }
        }
        ?>
        <!--
        <tr>
            <td>2016xxxx991</td>
            <td>客满分</td>
            <td>增加执行人员</td>
            <td>2016-09-07</td>
            <td>2016-09-07</td>
            <td>2017-03-04</td>
            <td>5</td>
            <td class="order_money">￥2970</td>
            <td>购买成功</td>
            <td><div  class="text_decorate">订单详情</div></td>
        </tr>
        <tr>
            <td>2016xxxx991</td>
            <td>客满分</td>
            <td>增加执行人员</td>
            <td>2016-09-07</td>
            <td>2016-09-07</td>
            <td>2017-03-04</td>
            <td>5</td>
            <td class="order_money">￥2970</td>
            <td>等待支付</td>
            <td>
                <div  class="text_decorate">订单详情</div>
                <div><button class="charge_btn">立即支付</button></div>
                <div>（距订单关闭：1天18时21分）</div>
            </td>
        </tr>
        <tr>
            <td>2016xxxx991</td>
            <td>客满分</td>
            <td>半年期付费许可</td>
            <td>2016-09-07</td>
            <td>2016-09-07</td>
            <td>2017-03-04</td>
            <td>5</td>
            <td class="order_money">￥2970</td>
            <td>购买成功</td>
            <td>
                <div  class="text_decorate">订单详情</div>
                <div><button class="charge_btn">增购执行人员名额</button></div>
                <div>（距订单关闭：1天18时21分）</div>
            </td>
        </tr>
        <tr>
            <td>2016xxxx991</td>
            <td>客满分</td>
            <td>半年期付费许可</td>
            <td>2016-09-07</td>
            <td>2016-09-07</td>
            <td>2017-03-04</td>
            <td>5</td>
            <td class="order_money">￥2970</td>
            <td class="over_time">订单关闭</td>
            <td>
                <div>
                    <p><a class="text_decorate" href="javascript:void(0)"  nc_type="dialog"  dialog_width="580" dialog_title="订单详情" dialog_id="buyer_order_cancel_order" uri="index.php?act=member_order&op=charge_order_info"  id="order<?php echo $order_info['order_id']; ?>_action_cancel">订单详情</a></p>
                    </div>
                <div  class="text_decorate">删除订单</div>

            </td>
        </tr>
        <tr>
            <td>2016xxxx991</td>
            <td>客满分</td>
            <td>半年期付费许可</td>
            <td>2016-09-07</td>
            <td>2016-09-07</td>
            <td>2017-03-04</td>
            <td>5</td>
            <td class="order_money">￥2970</td>
            <td class="over_time">许可过期</td>
            <td>
                <div  class="text_decorate">订单详情</div>
                <div><button class="charge_btn">立即续费</button></div>
                <div class="text_decorate">删除订单</div>
            </td>
        </tr>
-->
        </tbody>
        <?php if($output['order_list']) { ?>
            <tfoot>
            <tr>
                <td colspan="12"><div class="pagination"> <?php echo $output['show_page']; ?> </div></td>
            </tr>
            </tfoot>
        <?php } ?>
    </table>


</div>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/i18n/zh-CN.js" ></script>
<script charset="utf-8" type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/sns.js" ></script>
<script type="text/javascript">
    $(function(){
        $('#query_start_date').datepicker({dateFormat: 'yy-mm-dd'});
        $('#query_end_date').datepicker({dateFormat: 'yy-mm-dd'});
    });
</script>
