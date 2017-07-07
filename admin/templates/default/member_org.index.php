
<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>店铺认证</h3>
      <ul class="tab-base">
        <li><a <?php if($_GET["auth_statu"]=="0"||empty($_GET["auth_statu"])){?>href="JavaScript:void(0);" class="current"<?php }else{?>href="index.php?act=member_org&op=index"<?php }?> ><span>未认证</span></a></li>
        <li><a <?php if($_GET["auth_statu"]=="1"){?>href="JavaScript:void(0);" class="current"<?php }else{?>href="index.php?act=member_org&op=index&auth_statu=1"<?php }?>  ><span>通过</span></a></li>
        <li><a <?php if($_GET["auth_statu"]=="2"){?>href="JavaScript:void(0);" class="current"<?php }else{?>href="index.php?act=member_org&op=index&auth_statu=2"<?php }?>  ><span>不通过</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form method="get" name="formSearch" id="formSearch">
    <input type="hidden" value="member_org" name="act">
    <input type="hidden" value="index" name="op">
    <input type="hidden" name="auth_statu" value="<?php echo $_GET["auth_statu"];?>">
    <table class="tb-type1 noborder search">
      <tbody>
        <tr>
          <td>公司名称:</td>
          <td><input type="text" value="<?php echo $_GET['org_name'];?>" name="org_name" class="txt"></td>
          <td>申请会员:</td>
          <td><input type="text" value="<?php echo $_GET['applicant_name'];?>" name="applicant_name" class="txt"></td>
          <td>法人:</td>
          <td><input type="text" value="<?php echo $_GET['corporate'];?>" name="corporate" class="txt"></td>
          <td>联系人:</td>
          <td><input type="text" value="<?php echo $_GET['conneter'];?>" name="conneter" class="txt"></td>
          <td><a href="javascript:void(0);" onclick="$('#formSearch').submit();" id="ncsubmit" class="btn-search " title="<?php echo $lang['nc_query'];?>">&nbsp;</a>
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
            <li>对申请的店铺认证进行审核、查看.</li>
          </ul></td>
      </tr>
    </tbody>
  </table>
    <table class="table tb-type2 nobdb">
      <thead>
        <tr class="thead">
          <th>申请会员</th>
          <th>公司名称</th>
          <th>门店数</th>
          <th>法人</th>
          <th>联系人</span></th>
          <th>申请时间</th>
          <?php if($output['member_org_list'][0]['auth_time']!=""){ ?>
          <th>审核时间</th>
          <?php } ?>
          <th>认证状态</th>
          <th><?php echo $lang['nc_handle']; ?></th>
        </tr>
      <tbody>
        <?php if(!empty($output['member_org_list']) && is_array($output['member_org_list'])){ ?>
        <?php foreach($output['member_org_list'] as $k => $v){ ?>
        <tr class="hover">
          <td><?php echo $v["applicant_name"];?></td>
          <td><?php echo $v["org_name"];?></td>
          <td><?php echo $v["store_count"];?></td>
          <td><?php echo $v["corporate"];?></td>
          <td><?php echo $v["conneter"];?></td>
          <td><?php echo date('Y-m-d H:i:s',$v['create_time']);?></td>
           <?php if($v['auth_time']!=""){ ?>
          <td><?php echo date('Y-m-d H:i:s',$v['auth_time']);?></td>
            <?php } ?>
          <td><?php echo $v["auth_statu"]==0?"未认证":($v["auth_statu"]==1?"<font color='green'>通过</font>":"<font color='red'>不通过</font>");?></td>
          <td>
             <?php if($v["auth_statu"]==0){?>
                  <a href="index.php?act=member_org&op=member_org_view&org_id=<?php echo $v["org_id"];?>">审核</a>
             <?php }else if($v["auth_statu"]==1){ ?>
                  <a href="index.php?act=member_org&op=member_org_view&org_id=<?php echo $v["org_id"];?>">查看</a>|<a href="index.php?act=member_org&op=member_org_edit&org_id=<?php echo $v["org_id"];?>">修改</a></a>
             <?php }else{?>
                  <a href="index.php?act=member_org&op=member_org_view&org_id=<?php echo $v["org_id"];?>">查看</a>
             <?php }?>
          </td>
        </tr>
        <?php } ?>
        <?php }else { ?>
        <tr class="no_data">
          <td colspan="11"><?php echo $lang['nc_no_record']?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot class="tfoot">
        <?php if(!empty($output['member_org_list']) && is_array($output['member_org_list'])){ ?>
        <tr>
          <td colspan="16">
            <div class="pagination"> <?php echo $output['page'];?> </div>
           </td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
</div>
