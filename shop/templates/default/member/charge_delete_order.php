<!--订单详情-->
<div class="eject_con">
    <div id="warning"></div>

    <form action="index.php?act=member_order&op=charge_delete_order" method="post" id="confirm_order_form" onsubmit="ajaxpost('confirm_order_form','','','onerror')" >
        <input type="hidden" name="order_sn" value="<?php echo $output["order_sn"];?>" />
        <h3 class="orange">确定删除订单<?php echo $output["order_no"];?>?</h3>

        <div class="bottom">
            <label class="submit-border">
                <input type="submit" class="submit" id="confirm_yes" value="<?php echo $lang['nc_ok'];?>" />
            </label>
            <a class="ncm-btn ml5" href="javascript:DialogManager.close('buyer_order_cancel_order');">取消</a> </div>
    </form>


</div>





