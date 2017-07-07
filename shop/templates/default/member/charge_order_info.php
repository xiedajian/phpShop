<!--订单详情-->
        <div class=" NoBuy_main">
            <div class="content">
                <table class="charge_table_pop">
                    <tr>
                        <td>订单号：</td>
                        <td><?php echo $output["order_info"]["order_no_v2"]; ?> </td>
                    </tr>
                    <tr>
                        <td>所属产品：</td>
                        <td>客满分</td>
                    </tr>
                    <tr>
                        <td>付费许可类型：</td>
                        <td><?php if($output["order_info"]['charge_data']['app_code']=="KMF"){
                                if($output["order_info"]["charge_data"]['licences_months']==6){
                                    echo "半年期付费许可";
                                }else{
                                    echo "一年期付费许可";
                                }
                            }elseif($output["order_info"]["charge_data"]["app_code"]=="ZG"){
                                echo "增购执行人员";
                            }elseif($output["order_info"]["charge_data"]["app_code"]=="DPZS"){
                                echo "店铺指数";
                            }elseif($output["order_info"]["charge_data"]["app_code"]=="MRJ"){
                                echo "每人计";
                            } ?></td>
                    </tr>
                    <tr>
                        <td>许可购买日期：</td>
                        <td><?php echo date('Y-m-d',$output["order_info"]["add_time"]); ?></td>
                    </tr>
                    <tr>
                        <td>订单支付日期：</td>
                        <td><?php if($output["order_info"]["payment_time"]){echo date('Y-m-d',$output["order_info"]["payment_time"]);}else{echo "---";} ?></td>
                    </tr>
                    <tr>
                        <td>许可到期日期：</td>
                        <td><?php if($output["order_info"]["order_state"]==40){echo $output["order_info"]["charge_data"]["expire_date"];}else{echo "---";} ?></td>
                    </tr>
                    <tr>
                        <td>购买数量：</td>
                        <td><?php echo $output["order_info"]["charge_data"]["licences"]; ?> </td>
                    </tr>
                    <tr>
                        <td>订单状态：</td>
                        <?php if($output["order_info"]["order_state"]==10){ ?>
                            <td class="text_red ">等待支付（距订单关闭：<?php echo $output["order_info"]["days_data"]; ?>）</td>
                        <?php }elseif($output["order_info"]["order_state"]==40){ ?>
                            <td class="">购买成功</td>
                        <?php }elseif($output["order_info"]["order_state"]==0){ ?>
                            <td class="text_red">订单关闭</td>
                        <?php } ?>
                        <!--购买成功-->
                        <td class="hide">购买成功</td>
                        <!--购买成功-->
                        <td class="text_red hide">等待支付（距订单关闭：<?php echo $output["order_info"]["days_data"]; ?> ）</td>
                        <!--订单关闭-->
                        <td class="text_red hide">订单关闭</td>
                        <!--许可过期-->
                        <td class="text_red hide">许可过期</td>
                    </tr>
                    <tr>
                        <td>订单金额：</td>
                        <td>
                            <p><span class="Buy_text">￥<?php echo $output["order_info"]["order_amount"]; ?> </span> </p>
                            <p><?php echo "(￥".round($output["order_info"]["price"],2)."x".$output["order_info"]["charge_data"]["licences_months"]."个月x".$output["order_info"]["charge_data"]["licences"]."名执行人员=￥".$output["order_info"]["order_amount"].")";?> </p>
                        </td>
                    </tr>
                </table>
            </div>
            <!--购买成功-->
<?php if($output["order_info"]["order_state"]==10){ ?>
    <section class="">
        <div class="foot">
            <a class="charge_btn_pop" style="text-decoration:none;" href="index.php?act=buy&op=pay&pay_sn=<?php echo $output["order_info"]["pay_sn"]; ?>">立即支付</a>
            <!-- <button class="charge_btn_exit">退出</button>-->
            <a class="charge_btn_exit " style="text-decoration:none;" href="javascript:DialogManager.close('buyer_order_cancel_order');">取消</a>
        </div>
    </section>
            <?php }else{ ?>
    <section class="">
        <div class="foot">
            <a class="charge_btn_exit " style="text-decoration:none;" href="javascript:DialogManager.close('buyer_order_cancel_order');">退出</a>
        </div>
    </section>
            <?php } ?>
            <section class="hide">
                <div class="foot">
                    <button class="charge_btn_pop">增购执行人员名额</button>
                    <button class="charge_btn_gray">立即续费</button>
                    <button class="charge_btn_exit">退出</button>
                </div>
            </section>
            <!--等待支付-->
            <section class="hide">
                <div class="foot">
                    <a class="charge_btn_pop" style="text-decoration:none;" href="index.php?act=buy&op=pay&pay_sn=<?php echo $output["order_info"]["pay_sn"]; ?>">立即支付</a>
                   <!-- <button class="charge_btn_exit">退出</button>-->
                    <a class="charge_btn_exit " style="text-decoration:none;" href="javascript:DialogManager.close('buyer_order_cancel_order');">取消</a>
                </div>
            </section>
            <!--订单关闭-->
            <section class="hide">
                <div class="foot">
                    <button class="charge_btn_exit">退出</button>
                </div>
            </section>

        </div>





