<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
       <h3>内容管理</h3>
      <ul class="tab-base">
                <li><a href="JavaScript:void(0);" class="current"><span><?php echo '编辑';?></span></a></li>
            </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
  <form id="menu_form" method="post" name="articleForm">
    <input type="hidden" name="form_submit" value="ok" />
     <input type="hidden" name="id" value="<?php echo $output['material']['id']; ?>" />
    <table class="table tb-type2 nobdb">
      <tbody>
        <tr class="noborder">
              <td colspan="2" class="required"><label class="validation" for="parent_1">选择分类:</label></td>
         </tr>
     	<tr class="noborder">
                    <td class="vatop rowform">
                        <div style="width: 900px;">
                        <select name="parent_1" id="level_1">
                            <option value="">请选择</option>
                            <?php if($output['category']){foreach($output['category'] as $val){?>
                                <option value="<?php echo $val['category']?>"  <?php if(substr($output['material']['category'],0,4)==substr($val['category'],0,4)){ ?>selected="selected"<?php } ?>><?php echo $val['name'];?><?php echo $val['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select>

                        <select name="parent_2" id="level_2">
                            <option value="">请选择</option>
                            <?php if($output['category2']){foreach($output['category2'] as $val2){?>
                                <option value="<?php echo $val2['category']?>" <?php if(substr($output['material']['category'],0,8)==substr($val2['category'],0,8)){ ?>selected="selected"<?php } ?>><?php echo $val2['name'];?><?php echo $val2['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select>
                         <select name="parent_3" id="level_3">
                            <option value="">请选择</option>
                            <?php if($output['category3']){foreach($output['category3'] as $val3){?>
                                <option value="<?php echo $val3['category']?>" <?php if($output['material']['category']==$val3['category']){ ?>selected="selected"<?php } ?>><?php echo $val3['name'];?><?php echo $val3['state']==0?'（禁用）':'';?></option>
                            <?php }}?>
                        </select>
                        </div>
                        <script>
                            $(function(){
                                $("#level_1").change(function(){
                                    var code = $("#level_1").val();
                                    if(!code){
                                        $("#level_2").empty();
                                    }else{
                                        $.getJSON('index.php?act=mc_category&op=ajax_category&code='+code,function(data){
                                            $("#level_2").empty();
                                            $("#level_2").append('<option value="0">请选择</option>');
                                            for(var i=0;i<data.length;i++){
                                                $("#level_2").append('<option value="'+data[i]['category']+'">'+data[i]['name']+(data[i]['state']==0?'(禁用)':'')+'</option>');
                                            }
                                        });
                                    }
                                    $("#level_3").empty(); 
                                    $("#level_3").append('<option value="0">请选择</option>');
                                });
                             //   $("#level_1").change();

                                $("#level_2").change(function(){
                                    var code = $("#level_2").val();
                                    if(!code){
                                        $("#level_3").empty();
                                    }else{
                                        $.getJSON('index.php?act=mc_category&op=ajax_category&code='+code,function(data){
                                            $("#level_3").empty();
                                            $("#level_3").append('<option value="0">请选择</option>');
                                            for(var i=0;i<data.length;i++){
                                                $("#level_3").append('<option value="'+data[i]['category']+'">'+data[i]['name']+(data[i]['state']==0?'(禁用)':'')+'</option>');
                                            }
                                        });
                                    }
                                });
                          //      $("#level_2").change();
                            });
                        </script>
                    </td>
                  
         </tr>
         
        <tr class="noborder">
                <td colspan="2" class="required"><lable class="validation" for="type">发布内容类型：</lable> 
                <input type='radio' value='1' <?php if($output['material']['type']==1){ ?>checked="checked" <?php } ?> name='type'>图文
                <input type='radio' value='2' <?php if($output['material']['type']==2){ ?>checked="checked" <?php } ?>  name='type'>视频
                <input type='radio' value='3' <?php if($output['material']['type']==3){ ?>checked="checked" <?php } ?>  name='type'>图片
                </td>
         </tr> 
         <tr class="noborder">
                    <td colspan="2" class=""><label class="" for="title">精品课程:</label>
                    <input type="checkbox" value='1' <?php if($output['material']['is_jp']==1){ ?> checked="checked"<?php } ?> name='is_jp'>精品课程</td>
                    
         </tr> 
         <tr class="noborder">
                    <td colspan="2" class="required"><label class="validation" for="title">标题:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <input type="text" value="<?php echo $output['material']['title']; ?>" name="title" class="txt">
                    </td>
                    <td class="vatop tips"></td>
         </tr>
         
          <tr class="noborder">
                    <td colspan="2" class="required"><label class="" for="remark">摘要:</label></td>
                </tr>
                <tr class="noborder">
                    <td class="vatop rowform">
                        <textarea  value="0" name="remark" rows="5" cols="200" style="width:500px; height:100px;"><?php echo $output['material']['remark']; ?></textarea>
                    </td>              
          </tr>
           <tr>
                    <td colspan="2"><label for="tag">已有tag选择:</label></td>
           </tr>
           
          <tr class="noborder">
                    <td class="vatop rowform"  >
                    <div style="width: 500px;" id="tag">
                       <?php if(!empty($output['tag']) && is_array($output['tag'])){ ?>
	                       <?php foreach ($output['tag'] as $list){ ?>
	                       <div style='width:100px; float:left'>
	                       <input type="checkbox" value='<?php echo $list['id']; ?>' name='tag[]' class='tag' <?php if(in_array($list['id'], $output['tagarray'])){ ?> checked="checked" <?php } ?>><?php echo $list['name']; ?>
	                       </div>
	                       <?php } ?>
                       <?php } ?>
                       </div>
                    </td>
                    <td class="vatop tips"></td>
           </tr>
           
          <tr class='noborder'>
                 <td colspan="2" class=""><label class="" for="tag">新增tag:</label>&nbsp;&nbsp;&nbsp;
                 <input type="text"  name='tag_add' id='tag_add' class="txt" style='width: 200px;'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;tips:多个tag可以用逗号隔开  
                 </td>
          </tr>
          
          <tr class="noborder">
                    <td colspan="2" class="required"><label class="" for="remark">课堂缩略图上传：（上传1张，最多8张，建议尺寸500*500像素）</label></td>
          </tr> 
          
           <tr class="noborder">
         			 <td colspan="3" id="divComUploadContainer"><input type="file" multiple="multiple" id="fileupload" name="fileupload" /></td>
      		</tr> 
      		
      		 <tr>
         		 	<td colspan="2" class="required">已选择图片:</td>
       		</tr>
       		
       		 <tr>
		          <td colspan="2"><ul id="thumbnails" class="thumblists">
		          <?php foreach ($output['material']['thumb'] as $key=>$pic){ ?>
		          <li class="picture" id="<?php echo $key; ?>">
		          <input type="hidden" name="thumb[]" value="<?php echo substr($pic,46); ?>" />
		          <div class="size-64x64"><span class="thumb">
		          <i></i><img src="<?php echo $pic;?>"  width="64px" height="64px"/></span>
		          </div><p><span><a href="javascript:del_file_upload('<?php echo $key; ?>');"><?php echo $lang['nc_del'];?></a></span></p></li>
		          <?php } ?>
		            </ul><div class="tdare">     
		          </div></td>
		     </tr>
		   <tr class="noborder">
                    <td colspan="2" class="required"><label class="validation" for="content">内容编辑(图文的内容详情编辑;or 图片类素材的多图片上传加附件上传,or视频类课程介绍加视频附件上传):</label></td>
          </tr>   
		  <tr class="noborder">
          	<td colspan="2" class="vatop rowform">
          	<textarea class="text-input " id="content" name="content" cols="79" rows="15" style="width:750px;height:300px;"><?php echo $output['material']['content']; ?></textarea>
			<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/ueditor/ueditor.config.js"></script>
			<script type="text/javascript" src= "<?php echo RESOURCE_SITE_URL;?>/js/ueditor/ueditor.all.js"></script>
          	<script type="text/javascript">
					var editor = new UE.ui.Editor({ toolbars:[
                           ['fullscreen', 'source', '|', 'undo', 'redo', '|',
                            'bold', 'italic', 'underline', 'fontborder', 'strikethrough', 'superscript', 'subscript', 'removeformat', 'formatmatch', 'autotypeset', 'blockquote', 'pasteplain', '|', 'forecolor', 'backcolor', 'insertorderedlist', 'insertunorderedlist', 'selectall', 'cleardoc', '|',
                            'rowspacingtop', 'rowspacingbottom', 'lineheight', '|',
                            'customstyle', 'paragraph', 'fontfamily', 'fontsize', '|',
                            'directionalityltr', 'directionalityrtl', 'indent', '|',
                            'justifyleft', 'justifycenter', 'justifyright', 'justifyjustify', '|', 'touppercase', 'tolowercase', '|',
                            'link', 'unlink', 'anchor', '|', 'imagenone', 'imageleft', 'imageright', 'imagecenter', '|',
                            'insertimage', 'emotion', 'scrawl', 'insertvideo', 'music', 'attachment', 'map', 'gmap', 'insertframe','insertcode', 'webapp', 'pagebreak', 'template', 'background', '|',
                            'horizontal', 'date', 'time']
                    ],initialFrameWidth:750,initialFrameHeight:250,initialContent:''});
					editor.render("content");
				</script>
          	
          	</td>
        </tr> 
         <tr>
         <td>    
         </td>
         </tr>
         <tr class="noborder">
              <td colspan="2" class="required">
              <input type="checkbox" value='1' name='is_down' id="is_down" <?php if($output['material']['is_down']==1){ ?>checked="checked" <?php } ?>>包含下载</td>
         </tr> 
         
         <tr class="noborder">
         			 <td colspan="3" id="down_td"><input type="file" multiple="multiple" id="download" name="download_add" />
         			<span id='add_download'>
         			<?php if(!empty($output['material']['download'])){ ?>
         			<input type="text" name="download"  value="<?php echo substr($output['material']['download'],46); ?>" readonly="readonly" style="width:150px"/>
         			<?php } ?>
         			</span>
         			 </td>
      	</tr> 
         
         <tr class="noborder">
                    <td colspan="2" class="required"><label class="" for="browse">浏览权限:</label>
                    <input type="checkbox" value='1' name='browse[]' <?php if(($output['material']['browse']%2)==1){ ?> checked="checked"<?php } ?>>经销商(注册认证会员)
                    <input type="checkbox" value='2' name='browse[]' <?php if(($output['material']['browse']>2 || $output['material']['browse']==2) && $output['material']['browse']!=4){ ?> checked="checked"<?php } ?>>分销商
                    <input type="checkbox" value='4' name='browse[]' <?php if($output['material']['browse']>4 ||$output['material']['browse']==4){ ?> checked="checked"<?php } ?>>公众/游客</td>
                    
         </tr> 
          
          <tr class="noborder">
                <td colspan="2" class="required"><lable class="" for="share">分享类型（微信朋友圈）：</lable> 
                <input type='radio' value='1' <?php if($output['material']['share']==1){ ?> checked="checked"<?php } ?>  name='share'>自动提取文章标题和图片;不包含官网链接
                <input type='radio' value='2'  <?php if($output['material']['share']==2){ ?> checked="checked"<?php } ?> name='share'>普通文章分享(文章带官网链接) 
                </td>
         </tr> 
          
         <tr class="noborder">
         <td ><lable class="validation" for="type">可以根据情况设置是否需要用户购买，以及购买后，可执行的动作；</lable> </td>
         </tr>
  <br/>
   <br/>
 		 <tr class="noborder">
              <td colspan="2" class="required">
              <input type="checkbox" value='1' name='pay' id="pay" <?php if($output['material']['price']>0){ ?>checked="checked"<?php } ?>>需要付费</td>
         </tr> 
         
         <tr class="pay" style="display:none;">
         <td ><lable class="validation" for="type">价格设置：</lable> </td>
         </tr> 
         
         <tr class="pay"  style="display:none;">
         	<td>促销价（元）：<input type="text" value="<?php echo $output['material']['promotion_price']; ?>" name="promotion_price" class="txt">
         	促销结束时间：<input type="text" value="<?php if($output['material']['payment_time_start']!=0){echo data('Y-m-d',$output['material']['payment_time_start']);}  ?>" name="promotion_time" class="txt date" id='payment_time_start'>
         	促销词：<input type="text" value="<?php echo $output['material']['promotion_data']; ?>" name="promotion_data" class="txt">
         	</td>
         
         </tr>
         <tr  class="pay"  style="display:none;">
         <td><lable class="" for="price">原价（元）：</lable></td> 
      
         </tr>
          <tr  class="pay"  style="display:none;">
         <td><input type="text" name="price" class="txt" value="<?php echo $output['material']['price']; ?>"></td>
         </tr>
         <tr  class="pay"  style="display:none;">
         <td>购买后可以执行的操作：
          <select name="operation" id="level_1">
             <option value="1" <?php if($output['material']['operation']==1){ ?>selected="selected"<?php } ?>>在线观看</option>
             <option value="2" <?php if($output['material']['operation']==2){ ?>selected="selected"<?php } ?>>下载</option>
             <option value="3" <?php if($output['material']['operation']==3){ ?>selected="selected"<?php } ?>>直接应用到微店</option>
         </select>
         
         </td>
         </tr>
         <tr class="noborder">
              <td colspan="2" class="pay" style="display:none;">
              <input type="checkbox" value='1' name='in_package' id="pay" <?php if($output['material']['in_package']==1){ ?> checked="checked"<?php } ?>>是否在套餐内</td>
         </tr>  
      </tbody>
      <tfoot>
        <tr class="tfoot">
          <td colspan="15" ><a href="JavaScript:void(0);" class="btn" id="submitBtn"><span><?php echo $lang['nc_submit'];?></span></a></td>
        </tr>
      </tfoot>
    </table>
  </form>
</div>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/jquery.ui.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/common_select.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.mousewheel.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.iframe-transport.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script> 
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/dialog/dialog.js" id="dialog_js" charset="utf-8"></script>
<link rel="stylesheet" type="text/css" href="<?php echo RESOURCE_SITE_URL;?>/js/jquery-ui/themes/ui-lightness/jquery.ui.css"  />
<script type="text/javascript">
   var pic='<?php echo sizeof($output['material']['thumb']); ?>';
  
   var show=0;
   if('<?php echo $output['material']['price']; ?>'>0){
		//alert('aa');
		show++;
		$('.pay').show();
	   }
   
    $(function(){
    	$('#payment_time_start').datepicker({dateFormat: 'yy-mm-dd'}); //时间
    	//需要付费
    	$('#pay').on('change',function(){
        	if(show==0){
        		$('.pay').show();
        		show++;
            	}else{
            	$('.pay').hide();
            	show--;	
                	}
			
        	});
        	
    	
		$('#tag_add').change(function(){
			var tag=$(this).val();
			if(tag){
				 $.getJSON('index.php?act=mc_material&op=tag_add&tag='+tag,function(data){
					 var str='';
						if(data){
								for(i=0;i<data.length;i++){
									str+=' <div style="width:100px; float:left"><input type="checkbox" value="'+ data[i]['id'] +'" name="tag[]" class="tag">'+data[i]['name']+'</div>';
									}
							}
						 document.getElementById('tag').insertAdjacentHTML("beforeEnd", str);
						 $('#tag_add').val('');
						
                 });
				}
			});

        
        $('#menu_form').validate({
            errorPlacement: function(error, element){
                error.appendTo(element.parent().parent().prev().find('td:first'));
            },
            rules : {
            	title : {
                    required : true
                },
            	price :{
                
					number : true
					
                },
                promotion_price :{
                	number : true
                    },
               content :{
					required :true
                   },
               parent_1:{
                   required :true
                       }
                
            },
            messages : {
            	title : {
                    required : '请输入标题'
                },
                price:{
                 
					number : '请输入正确的价格'
                	},
                promotion_price :{
                	number : '请输入正确的促销价'
                    },
                content :{
					required:'请输入内容'
                    },
                parent_1 :{
					required:'请选择分类'
                    }
            }
        });

        $("#submitBtn").click(function(){
          
        	 var text="";  
             $(".tag").each(function() {  
                 if ($(this).attr("checked")) {  
                     text += ","+$(this).val();  
                 }  
             });  
             if(text==""){
				alert('请选择tag标签');
				return;
                 }
            if(pic==0){
				alert("请选择缩略图！");
				return;
                }
            if(show==1 && $('input[name=price]').val()==''){
                alert('请输入原价');
                return;
                }
           
            if($("#menu_form").valid()){
                $("#menu_form").submit();
            }
        });

     // 图片上传
     
        $('#fileupload').each(function(){
        	
            $(this).fileupload({
                dataType: 'json',
                url: 'index.php?act=mc_material&op=pic_upload',
                done: function (e,data) {
                    if(data != 'error'){
                    
                    	add_uploadedfile(data.result);
                    }
                }
            });
        });

 //下载文件上传
        
        $('#download').each(function(){
        	
            $(this).fileupload({
                dataType: 'json',
                url: 'index.php?act=mc_material&op=download_upload',
                done: function (e,data) {
                    if(data != 'error'){
                    	add_download(data.result);
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
        pic++;
        var id=parseInt(file_data.id); //强制转成数字
        var newImg = '<li class="picture" id="'+id+'"><input type="hidden" name="thumb[]" value="' + file_data.pic + '" /><div class="size-64x64"><span class="thumb"><i></i><img src="<?php echo UPLOAD_SITE_URL.'/'.ATTACH_MATERIAL.'/';?>' + file_data.pic + '" alt="' + file_data.pic + '" width="64px" height="64px"/></span></div><p><span><a href="javascript:del_file_upload('+id+');"><?php echo $lang['nc_del'];?></a></span></p></li>';
        $('#thumbnails').prepend(newImg);
    }

    function add_download(data){
        $('#add_download').empty();
    	 var newImg = '<input type="text" name="download"  value="'+data.download+'" readonly="readonly" style="width:150px"/>';
         $('#add_download').prepend(newImg);

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