<?php defined('InShopNC') or exit('Access Invalid!');?>

<div class="page">
  <div class="fixed-bar">
    <div class="item-title">
      <h3>素材上传</h3>
      <ul class="tab-base">
        <li><a href="index.php?act=material&op=index"><span>管理</span></a></li>
        <li><a href="index.php?act=material&op=tag" ><span>标签管理</span></a></li>
        <li><a href="index.php?act=material&op=edit" class="current"><span>素材上传</span></a></li>
      </ul>
    </div>
  </div>
  <div class="fixed-empty"></div>
    <table class="table tb-type2" id="prompt">
        <tbody>
            <tr class="space odd">
                <th colspan="12">
                    <div class="title">
                    <h5><?php echo $lang['nc_prompts'];?></h5>
                    <span class="arrow"></span>
                    </div>
                </th>
            </tr>
            <tr>
                <td><ul>
                        <li>1. 点击上传文件，上传素材包文件（支持 .png、.jpg、.gif、.psd、.rar文件）</li>
                        <li>2. 上传的第一张图片文件将作为前台展示缩略图，没有上传图片格式的话将展示默认缩略图</li>
                    </ul></td>
            </tr>
        </tbody>
    </table>
    <style>
        .material-upload{
            width: 100%;
            margin-top: 30px;
        }
        .material-upload-left{
            padding: 0;
        }
        .material-upload-right{
            padding: 0px 0px 0px 50px;
        }
        .material-upload-btn{
            display: inline-block;
            padding: 8px 0px;
            background-color: #4F8EC8;
            color: #ffffff;
            border-radius: 5px;
            text-align: center;
            margin-right: 10px;
            cursor: pointer;
            width:138px;
        }
        table.input-form{
            margin-top: 30px;width: 100%;
        }
        table.input-form td{padding: 10px 10px}
        .tag{padding: 3px 10px;border: 1px solid #BBBBBB;display: inline-block;margin-right: 20px;margin-bottom: 5px;cursor: pointer}
        .no-tag{padding: 3px 10px;display: inline-block;margin-right: 20px;margin-bottom: 5px;}
        .tag-select{border: 2px solid red !important;}
        .upload-tip{color: #aaaaaa}

    </style>
    <form id="material_form" method="post" action="">
        <input type="hidden" name="id" value="<?php echo $output['material_info']['id'];?>">
        <table class="material-upload">
        <tr>
            <td width="40%" style="vertical-align: top">
                <div class="material-upload-left">
                    <input type="hidden" name="form_submit" value="ok">
                    <table class="input-form">
                        <tr>
                            <td width="100" align="right"></td>
                            <td>
                                <div>
                                    <input type="file" id="material" name="material" show_img="0" style="display:none"/>
                                    <span class="material-upload-btn">上传文件</span>
                                    <span class="upload-tip" <?php if($output['material_info']['show_filename'])echo 'style="display:none"';?>>
                                        请点击上传素材文件
                                    </span>
                                    <input type="text" id="material_img" name="material_img" style="width:200px;<?php if(!$output['material_info']['show_filename'])echo 'display:none';?>" readonly="readonly" value="<?php echo $output['material_info']['filename'];?>">
                                    <div class="img-view" style="display: inline-block"></div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right"></td>
                            <td>
                                <div>
                                    <input type="file" id="show_file" name="show_file" show_img="1" style="display:none"/>
                                    <span class="material-upload-btn">上传展示文件</span>
                                    <span class="upload-tip" <?php if($output['material_info']['show_filename'])echo 'style="display:none"';?>>
                                        请点击上传展示文件
                                    </span>
                                    <input type="text" id="show_file_img" name="show_file_img" style="width:200px;<?php if(!$output['material_info']['show_filename'])echo 'display:none';?>" readonly="readonly" value="<?php echo $output['material_info']['show_filename'];?>">
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right"><label for="name">标题</label></td>
                            <td><input id="name" name="name" style="height: 26px;line-height: 26px;width: 80%;padding-left: 5px;" value="<?php echo $output['material_info']['name'];?>"></td>
                        </tr>
                        <tr>
                            <td width="100" align="right"> <label for="brief ">简介</label></td>
                            <td><textarea id="brief" name="brief" style="width: 79%;resize: vertical"><?php echo $output['material_info']['brief'];?></textarea></td>
                        </tr>
                        <tr>
                            <td width="100" align="right"><label>标签</label></td>
                            <td>
                                <div style="margin-top:10px;">
                                    <?php if($output['tag']){foreach($output['tag'] as $tag){?>
                                        <span class="tag <?php if($tag['tag_id']==$output['material_info']['tag_id']) echo ' tag-select';?>" tag_id="<?php echo $tag['tag_id'];?>"><?php echo $tag['tag_name'];?></span>
                                    <?php }}else{?>
                                        <span class="no-tag">没有标签</span>
                                    <?php }?>
                                </div>
                                <input type="hidden" name="tag_id" id="tag" value="<?php echo $output['material_info']['tag_id'];?>">
                            </td>
                        </tr>
                        <tr>
                            <td width="100" align="right"></td>
                            <td>
                                <div style="color: #ffffff;border-radius: 5px;background-color: #BBBBBB;text-align: center;padding: 8px 0px;width: 120px;cursor: pointer;float: left;margin-right: 10px" onclick="history.back()">返回</div>
                                <div style="color: #ffffff;border-radius: 5px;background-color: #4F8EC8;text-align: center;padding: 8px 0px;width: 120px;cursor: pointer;float: left" onclick="$('#material_form').submit()">提交</div>
                            </td>
                        </tr>
                    </table>

                </div>
            </td>
            <td style="vertical-align: top">
                <div class="material-upload-right">
                    <div style="padding: 10px 0px;font-size: 16px;">前台展示预览</div>
                    <div class="show_image_box" style="max-width: 500px;max-height: 500px;overflow: hidden">
                        <?php if($output['material_info']['show_file_url']){?>
                            <img class='upload_view_img' src="<?php echo $output['material_info']['show_file_url'];?>">
                        <?php }?>
                    </div>
                </div>
            </td>
        </tr>
    </table>
    </form>

<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.edit.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.ui.widget.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/fileupload/jquery.fileupload.js" charset="utf-8"></script>
<script>
    function getfilesize(target){
        //检测上传文件的大小
        var isIE = /msie/i.test(navigator.userAgent) && !window.opera;
        var fileSize = 0;
        if (isIE && !target.files){
            var filePath = target.value;
            var fileSystem = new ActiveXObject("Scripting.FileSystemObject");
            var file = fileSystem.GetFile (filePath);
            fileSize = file.Size;
        } else {
            fileSize = target.files[0].size;
        }
        var size = fileSize;//字节
        return size;
    }
    function addImg(e,data){
        var dlObj=$(e.target).parent();
        if(getfilesize(data)>=100*1024*1024){
            dlObj.find(".upload-tip").html("上传图片不能超过100M");
            return;
        }else{
            dlObj.find(".upload-tip").text("");
        }
        if($(e.target).attr('show_img')=='1'){
            $('.show_image_box').html("<img src='<?php echo ADMIN_TEMPLATES_URL;?>/images/loading.gif' />");
        }else{
            dlObj.find('.img-view').html("<img src='<?php echo ADMIN_TEMPLATES_URL;?>/images/loading.gif' />");
        }
        data.submit();
    }
    function doneImg(e,data){
        var dlObj=$(e.target).parent();
        var param = data.result;
        if (typeof(param.error) != 'undefined') {
            alert(param.error);
        } else {
            var input=$('#'+e.target.name+"_img");
            input.val(param.filename);
            input.show();
            input.focus();
            input.blur();
            param.img_path += "?rand="+Math.random();
        }
        if($(e.target).attr('show_img')=='1'){
            $('.show_image_box').html("<img class='upload_view_img' src='"+param.img_path+"' />");
        }else{
            dlObj.find('.img-view').html("");
        }
    }
    //上传素材
    $('#material').fileupload({
        dataType: 'json',
        url: 'index.php?act=material&op=upload',
        formData: {name:'material'},
        add: addImg,
        done: doneImg
    });
    //上传展示图片
    $('#show_file').fileupload({
        dataType: 'json',
        url: 'index.php?act=material&op=upload',
        formData: {name:'show_file'},
        add: addImg,
        done: doneImg
    });

    $(function(){
        $(".material-upload-btn").bind('click',function(){
            $(this).parent().find(':file').trigger('click');
        });
        $(".tag").click(function(){
            $(".tag").removeClass('tag-select');
            $(this).addClass('tag-select');
            $('#tag').val($(this).attr('tag_id'));
            $('#tag').closest('td').find('.error').hide();
        });
        $('#material_form').validate({
            errorPlacement: function(error, element){
                element.after(error);
            },
            rules : {
                material_img :{
                    required : true
                },
                name : {
                    required : true
                },
                tag  : {
                    required : true
                },
                show_file_img:{
                    required : true
                },
                tag_id:{
                    required : true
                }
            },
            messages : {
                material_img : {
                    required : '请上传素材'
                },
                name : {
                    required : '请填写标题'
                },
                tag  : {
                    required : '请选择标签'
                },
                show_file_img : {
                    required : '请上传展示图片'
                },
                tag_id:{
                    required : '请选择标签'
                }
            }
        });
    });

</script>