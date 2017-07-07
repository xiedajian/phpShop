<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>素材上传</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=material&op=index" class="current"><span>管理</span></a></li>
        <li><a href="index.php?act=material&op=tag" ><span>标签管理</span></a></li>
        <li><a href="index.php?act=material&op=edit" ><span>素材上传</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>


  <form method="get" name="formSearch">
    <input type="hidden" value="material" name="act">
    <input type="hidden" value="index" name="op">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <th>
              <select name="search_type" id="search_type">
                <option value="1" <?php if($_GET['search_type']==1)echo 'selected="selected"';?>>标题</option>
                <option value="2" <?php if($_GET['search_type']==2)echo 'selected="selected"';?>>标签</option>
              </select>
          </th>
          <td>
              <input type="text" value="<?php echo $_GET['search_txt']?>" name="search_txt" class="txt" id="search_txt" <?php if($_GET['search_type']==3)echo 'style="display:none"';?>>
              <select name="tag" <?php if($_GET['search_type']!=3)echo 'style="display: none"';?> id="tags">
                  <?php if($output['tag']){foreach($output['tag'] as $tag){?>
                      <option value="<?php echo $tag['tag_id'];?>" <?php if($_GET['tag']==$tag['tag_id'])echo 'selected="selected"';?>><?php echo $tag['tag_name'];?></option>
                  <?php }}else{?>
                      <option value="0">没有标签</option>
                  <?php }?>
              </select>
          </td>
          <td>
              <a href="javascript:document.formSearch.submit();" class="btn-search tooltip" title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
              <?php if($output['do_search'] != ''){?>
                <a href="index.php?act=material&op=index" class="btns tooltip" title="<?php echo $lang['nc_cancel_search'];?>"><span>撤销检索</span></a>
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

  <form method='post' id="form_material">
    <input type="hidden" name="form_submit" value="ok" />
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>&nbsp;</th>
          <th>标题名称</th>
          <th>标签</th>
          <th>上传时间</th>
          <th>下载次数</th>
          <th>简介</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['material_list']) && is_array($output['material_list'])){ ?>
        <?php foreach($output['material_list'] as $k => $v){ ?>
        <tr class="hover edit">
          <td class="w24"><input type="checkbox" name="del_id[]" value="<?php echo $v['id'];?>" class="checkitem"></td>
          <td>
              <span><?php echo $v['name'];?></span>
          </td>
          <td><?php echo $output['tag'][$v['tag_id']]['tag_name'];?></td>
          <td><?php if($v['addtime']>0)echo date('Y-m-d H:i:s',$v['addtime']);?></td>
          <td><?php echo $v['download'];?></td>
          <td><?php echo $v['brief'];?></td>

          <td class="w96 align-center">
              <a href="index.php?act=material&op=edit&id=<?php echo $v['id'];?>">编辑</a>
              |<a href="javascript:void()" onclick="if(confirm('确认删除？')){location.href='index.php?act=material&op=del&id=<?php echo $v["id"];?>';}">删除</a>
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
            <?php if(!empty($output['material_list']) && is_array($output['material_list'])){ ?>
            <tr class="tfoot" id="dataFuncs">
              <td><input type="checkbox" class="checkall" id="checkallBottom"></td>
              <td colspan="16" id="batchAction">
                    <label for="checkallBottom"><?php echo $lang['nc_select_all'];?></label>
                    &nbsp;&nbsp;

                    <a href="JavaScript:void(0);" class="btn" onclick="if(confirm('<?php echo $lang['nc_ensure_del'];?>')){$('#form_material').submit();}"><span><?php echo $lang['nc_del'];?></span></a>
                    <div class="pagination"> <?php echo $output['page'];?> </div>
              </td>
            </tr>
            <?php } ?>
      </tfoot>
    </table>
  </form>

</div>
<script type="text/javascript" src="<?php echo RESOURCE_PATH;?>/js/jquery.edit.js" charset="utf-8"></script>
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
</script>