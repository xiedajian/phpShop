<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>标签管理</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=material&op=index"><span>管理</span></a></li>
        <li><a href="index.php?act=material&op=tag" class="current"><span>标签管理</span></a></li>
        <li><a href="index.php?act=material&op=edit" ><span>素材上传</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>


  <form method="get" name="formSearch">
    <input type="hidden" value="material" name="act">
    <input type="hidden" value="tag" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th>
              标签
          </th>
          <td>
              <input type="text" value="<?php echo $_GET['tag_name'];?>" name="tag_name" class="txt" id="tag_name">
          </td>
          <td>
              <a href="javascript:document.formSearch.submit();" class="btn-search tooltip" title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
              <?php if($output['do_search'] != ''){?>
                <a href="index.php?act=material&op=tag" class="btns tooltip" title="<?php echo $lang['nc_cancel_search'];?>"><span>撤销检索</span></a>
              <?php }?>
          </td>
        </tr>
      </tbody>
    </table>
  </form>


  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title">
            <h5><?php echo $lang['nc_prompts'];?></h5>
            <span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td><ul>
            <li></li>
            <li></li>
          </ul></td>
      </tr>
    </tbody>
  </table>

    <div>
        <button onclick="add()">添加标签</button>
    </div>

  <form method='post' id="form_tagl">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th></th>
          <th>标签名称</th>
          <th>添加时间</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['tag_list']) && is_array($output['tag_list'])){ ?>
        <?php foreach($output['tag_list'] as $k => $v){ ?>
        <tr class="hover edit">
            <td class="w24">
                <input type="checkbox" name="del_id[]" value="<?php echo $v['tag_id'];?>" class="checkitem">
            </td>
          <td>
              <span><?php echo $v['tag_name'];?></span>
              <input type="text" value="<?php echo $v['tag_name'];?>" style="display: none">
          </td>
          <td><?php if($v['add_time']>0){echo date('Y-m-d H:i:s',$v['add_time']);}?></td>

          <td class="w96 align-center">
              <a href="javascript:void" class="tag-edit" tag_id="<?php echo $v["tag_id"];?>">编辑</a>
              |<a href="javascript:void()" onclick="if(confirm('确认删除？')){location.href='index.php?act=material&op=deltag&id=<?php echo $v["tag_id"];?>';}">删除</a>
          </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="10"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['tag_list']) && is_array($output['tag_list'])){ ?>
        <tr class="tfoot" id="dataFuncs">
          <td colspan="4" id="batchAction">
              <input type="checkbox" class="checkall" id="checkallBottom">
                <label for="checkallBottom"><?php echo $lang['nc_select_all']; ?></label>
                &nbsp;&nbsp;
                <a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){$('#form_tagl').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
                <div class="pagination"> <?php echo $output['page'];?> </div>
          </td>
        </tr>
      </tfoot>
      <?php } ?>
    </table>
  </form>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script>
    $('#search_type').change(function(){
        if($(this).val()==3){
            $("#search_txt").hide();
            $("#tags").show();
        }else{
            $("#search_txt").show();
            $("#tags").hide();
        }
    });
    $('.edit-now').click(function(){
        var input_box = $(this).closest('tr').find('input');
        if(input_box.style('display')=='none'){
            input_box.parent().find('span').hide();
            input_box.show();
        }
    });
    $('.tag-edit').click(function(){
        var id=$(this).attr('tag_id');
        if(id){
            var _uri = "<?php echo ADMIN_SITE_URL;?>/index.php?act=material&op=editTag&id="+id;
            CUR_DIALOG = ajax_form('tag', '修改标签', _uri, 350);
        }
    });
    function add(){
        var _uri = "<?php echo ADMIN_SITE_URL;?>/index.php?act=material&op=editTag";
        CUR_DIALOG = ajax_form('tag', '添加标签', _uri, 350);
    }

</script>