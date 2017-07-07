<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <form method="post" name="form1" id="form1" action="<?php echo urlAdmin('order', 'edit_remark');?>">
    <input type="hidden" name="form_submit" value="ok" />
    <input type="hidden" value="<?php echo $output["commonids"];?>" name="commonids">
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
            <input type="hidden" name="order_id" value="<?php echo $_GET['id'];?>">
          <td class="vatop rowform">
              <textarea rows="6" class="tarea" cols="60" name="remark" id=""><?php echo $_GET['content'];?></textarea>
          </td>
        </tr>
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="2"><a href="javascript:void(0);" class="btn" nctype="btn_submit"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>
<script>
$(function(){
    $('a[nctype="btn_submit"]').click(function(){
        ajaxpost('form1', '', '', 'onerror');
    });
});
</script>