<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>首页管理</h3>
      <ul class="tab-base">
        <li><a href="JavaScript:void(0);" class="current"><span><?php echo '板块区';?></span></a></li>
        <li><a href="index.php?act=mc_block&op=add_block"><span><?php echo '新增板块';?></span></a></li>
        <li><a href="index.php?act=mc_block&op=focus_edit"><span><?php echo '焦点区';?></span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <table class="table tb-type2" id="prompt">
    <tbody>
      <tr class="space odd">
        <th colspan="12"><div class="title"><h5><?php echo $lang['nc_prompts'];?></h5><span class="arrow"></span></div></th>
      </tr>
      <tr>
        <td>
        <ul>
            <li>排序越小越靠前，可以控制板块显示先后。</li>
            <li>色彩风格和前台的样式一致，在基本设置中选择更换。</li>
            <li>色彩风格是css样式中已经有的，如果需要修改名称则相关程序也要同时改变才会有效果。</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
  <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>排序</th>
          <th>板块名称</th>
          <th>色彩风格</th>
          <th class="align-center">更新时间</th>
          <th class="align-center">显示</th>
          <th class="align-center">操作</th>
        </tr>
      </thead>
      <tbody>
        <?php if(!empty($output['block_list']) && is_array($output['block_list'])){ ?>
        <?php foreach($output['block_list'] as $k => $v){ ?>
        <tr class="hover">
          <td class="w24 sort">
              <input type="text" value="<?php echo $v['sort'];?>" class="editable">
          </td>
          <td><?php echo $v['name'];?></td>
          <td><?php echo $output['style_name'][$v['style']];?></td>
          <td class="w150 align-center"><?php echo date('Y-m-d H:i:s',$v['update_time']);?></td>
          <td class="w150 align-center"><?php echo $v['state']==1 ? '是' : '否';?></td>
          <td class="w150 align-center">
              <a href="index.php?act=mc_block&op=edit_block&id=<?php echo $v['id'];?>">基本设置</a> |
              <a href="index.php?act=mc_block&op=edit_block_content&id=<?php echo $v['id'];?>">板块编辑</a>
          </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="15"><?php echo $lang['nc_no_record'];?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php if(!empty($output['block_list']) && is_array($output['block_list'])){ ?>
        <tr class="tfoot">
          <td colspan="16">
            <div class="pagination"> <?php echo $output['page'];?> </div></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
</div>