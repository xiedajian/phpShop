<?php defined('InShopNC') or exit('Access Invalid!');?>

<style>
    td{padding: 3px 5px;}
</style>
<div class="page">

    <form method="post" action="index.php?act=material&op=editTag">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="tag_id" value="<?php echo $output['tag_info']['tag_id'];?>">
        <div>
            <table>
                <tr>
                    <td><label>标签名称</label></td>
                    <td><input name="tag_name" value="<?php echo $output['tag_info']['tag_name'];?>"></td>
                    <td><input type="submit" value="<?php if($output['tag_info']['tag_id'])echo '保存';else echo '添加';?>"></td>
                </tr>
            </table>


        </div>

    </form>

</div>
