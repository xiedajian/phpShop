<?php defined('InShopNC') or exit('Access Invalid!');?>

<?php if($output['info']&&$output['info']['status']==200){ ?>
    <div class="delivery-list">
        <table cellspacing="0">
            <tbody>
            <?php for($i=0;$i<count($output['info']['data']);$i++){?>
                <tr>
                    <td width="10"></td>
                    <td class="<?php if($i==0){ ?>status-first<?php }else if($i==count($output['info']['data'])-1){ ?>status-last<?php }else{ ?>status<?php } ?>">&nbsp;</td>
                    <td>
                        <div class="<?php if($i==0){?>first<?php }else{ ?>detail<?php } ?>">
                            <div><?php echo $output['info']['data'][$i]['context'];?></div>
                            <p><?php echo $output['info']['data'][$i]['time'];?></p>
                            <div class="angle"></div>
                        </div>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php }else{?>
<div style="font-size: 12px;text-align: left;padding: 25px 3%;color: red">
    查询失败
</div>
<?php }?>
