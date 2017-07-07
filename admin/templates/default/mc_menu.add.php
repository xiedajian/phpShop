<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
    <div class="fixed-bar">
        <div class="item-title">
            <h3>首页导航栏</h3>
            <?php echo $output['top_link'];?> </div>
    </div>
    <div class="fixed-empty"></div>
    <form id="menu_form" enctype="multipart/form-data" method="post">
        <input type="hidden" name="form_submit" value="ok" />
        <input type="hidden" name="id" value="<?php echo $output['val']['id'];?>" />
        <table class="table tb-type2">
            <tbody>
                <tr class="noborder">
                    <td colspan="2" class="required"><label class="validation" for="title">标题:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" value="" name="title" class="txt">
                    </td>
                    <td class="vatop tips"></td>
                </tr>

                <tr>
                    <td colspan="2"><label for="link">链接地址:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" value="" name="link" id="link" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2"><label for="sort">排序:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" value="" name="sort" id="sort" class="txt"></td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2"><label for="">状态:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="radio" value="1" name="state" id="state-yes"><label for="state-yes">正常</label>
                        <input type="radio" value="0" name="state" id="state-no"><label for="state-no">禁用</label>
                    </td>
                    <td class="vatop tips"></td>
                </tr>
                <tr>
                    <td colspan="2"><label for="sort">新窗口打开:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="radio" value="1" name="blank" id="blank-yes"><label for="blank-yes">是</label>
                        <input type="radio" value="0" name="blank" id="blank-no"><label for="blank-no">否</label>
                    <td class="vatop tips"></td>
                </tr>
 		<tr class="noborder">
                    <td colspan="2" class="required"><label class="" for="remark">首页轮播图上传：（上传1张，最多8张）</label></td>
          </tr> 
          
           <tr class="noborder">
         			 <td colspan="3" id="divComUploadContainer"><input type="file" multiple="multiple" id="fileupload" name="fileupload" /></td>
      		</tr> 
      		
      		 <tr>
         		 	<td colspan="2" class="required">已选择图片:</td>
       		</tr>
       		
       		 <tr>
		          <td colspan="2"><ul id="thumbnails" class="thumblists">
		          
		         <!--  <li class="picture" id="" style='float:none; width:300px; height:150px; '>
		          <input type="hidden" name="thumb[]" value="" />
		          <div class="size-64x64">
		          <span class="thumb"><i></i><img src="http://www.ipvp.com/data/upload/shop/top_menu/05061832653617642.jpg"  width="64px" height="64px"/>
		          </span></div>
		          <div style='border: 0;'>
		          链接地址：<input type="text" value="" name="thumblink[]" id="link" class="txt" style='width: 200px;'>
		          
		          </div>
		          <p><span><a href="javascript:del_file_upload('+id+');"><?php echo $lang['nc_del'];?></a></span></p></li> -->
		         
		            </ul><div class="tdare">     
		          </div></td>
		     </tr>

            </tbody>
            <tfoot>
            <tr>
                <td colspan="2"><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
            </tr>
            </tfoot>
        </table>
    </form>
</div>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script>
var pic=0;
    $(function(){
        $('#menu_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
                title : {
                    required : true
                },
                link : {
                    required : true
                },
                sort : {
                    required : true,
                    number:true
                },
                state : {
                    required : true
                },
                blank : {
                    required : true
                }
            },
            messages : {
                title : {
                    required : '请输入标题'
                },
                link : {
                    required : '请输入链接地址'
                },
                sort  : {
                    required   : '请输入排序',
                    number   : '请输入数字0-255'
                },
                state  : {
                    required   : '请选择状态'
                },
                blank  : {
                    required   : '请选择是否新窗口打开'
                }
            }
        });

        $("#submitBtn").click(function(){
            if($("#menu_form").valid()){
                $("#menu_form").submit();
            }
        });

        // 图片上传
        
        $('#fileupload').each(function(){
        	
            $(this).fileupload({
                dataType: 'json',
                url: 'index.php?act=mc_menu&op=pic_upload',
                done: function (e,data) {
                    if(data != 'error'){
                    
                    	add_uploadedfile(data.result);
                    }
                }
            });
        });
    });


    function add_uploadedfile(file_data)
    {
        if(pic==8){
			alert("上传图片最多为8张！");
			return;
            }
        
        var id=parseInt(file_data.id); //强制转成数字
        var newImg = '<li class="picture" id="'+id+'" style="float:none; width:300px; height:150px; "><input type="hidden" name="thumb[]" value="' + file_data.pic + '" /><div class="size-64x64"><span class="thumb"><i></i><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_TOPMENU.'/';?>' + file_data.pic + '" alt="' + file_data.pic + '" width="64px" height="64px"/></span></div><div style="border: 0;"> 链接地址：<input type="text" value="" name="thumb_link[]" id="link" class="txt" style="width: 200px;"></div><p><span><a href="javascript:del_file_upload('+id+');"><?php echo $lang['nc_del'];?></a></span></p></li>';
        pic++;
        $('#thumbnails').prepend(newImg);
    }
   
    function del_file_upload(file_id)
    {
     
        if(!window.confirm('确定删除？')){
            return;
        }
        pic--;
        $('#' + file_id).remove();
    }

</script> 
