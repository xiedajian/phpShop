<style>

    .content{padding:7px;width:1200px;margin:0 auto;}
    .item{background:#ffffff;color:#333;border: 1px solid #EAEAEA;word-break:break-all;word-wrap:break-word;white-space:pre;white-space:pre-wrap;cursor: pointer}
    .item img:first-child{width:100%;height:auto;}
    .gridalicious{width: 100%;margin-top: 80px;}
     li{list-style: none}
    .list-top{width: 100%;margin-top: 12px; background-color: #ffffff;margin-bottom: 30px;}
    .list-top ul{height: 35px;line-height: 35px;font-size:1em;}
    .list-top li{display: inline-block;padding:0 15px;text-align: center;font-size: 14px;color: #777;margin-bottom: 7px;}
    .list-top .now_tag{background-color: #4daefd;color: #ffffff !important;border-radius: 17px;}
    .list-top li:hover{background-color: #4daefd;color: #ffffff !important;border-radius: 17px;}
    .material-empty{width: 100%;text-align: center;margin-top: 30px;}
    .m-title{
        padding: 8px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-align: center;
    }


    #faq{display: none}
    #footer{display: none}
    #think_page_trace{display: none}

</style>


<div class="wrapper" >
    <div class="content">
        <div class="nch-breadcrumb-layout">
            <div class="nch-breadcrumb wrapper">
                <span style="font-size: 16px">宣传素材</span>
            </div>
        </div>
        <div class="list-top">
            <ul>
                <a href="index.php?act=material&op=index"><li <?php if(intval($_GET['tag_id'])==0)echo 'class="now_tag"';?>>全部</li></a>
                <?php if($output['tag_list']){foreach($output['tag_list'] as $tag){?>
                    <a href="index.php?act=material&op=index&tag_id=<?php echo $tag['tag_id'];?>"><li <?php if($_GET['tag_id']==$tag['tag_id'])echo 'class="now_tag"';?>><?php echo $tag['tag_name'];?></li></a>
                <?php }}?>
            </ul>
        </div>
        <div id="device" class="gridalicious">
        </div>
        <div class="material-empty">
            暂时没有素材！
        </div>
    </div>

</div>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.js"></script>
<script src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.grid-a-licious.min.js"></script>
<script type="text/javascript">
    var loading = 0;
    var page = 1;
    function load(){
        if(loading==0){
            loading = 1;
        }
        else
            return;
        $.ajax({
            type:"GET",
            dataType: "json",
            url:'index.php?act=material&op=ajax_material&tag_id='+<?php echo $_GET['tag_id']?$_GET['tag_id']:0;?>+'&curpage='+page++,
            async:true,
            success: function(data){
                var boxes =[];
                if(data.list==null){
                    return;
                }
                for(var i =0;i<data.list.length;i++){
                    var item = $(' <div class="item" onclick="location.href=\'index.php?act=material&op=detail&id='+data.list[i].id+'\'"><img src="'+data.path+data.list[i].show_filename+'"/><p class="m-title">'+data.list[i].name+'</p></div>');
                    boxes.push(item);
                }
                $("#device").gridalicious('append', boxes);
                if($("#device").find('.item').length>0){
                    $('.material-empty').hide();
                }else{
                    $('.material-empty').show();
                }
                loading = 0;
            }
        });
    }

    $(function(){
        $(window).scroll(function(){
          if($(window).scrollTop()+$(window).height()==$(document).height()){
              load();
          }
        });
        $("#device").gridalicious({
            gutter: 20,
            width:1200,
            animate: true,
            animationOptions: {
                speed: 150,
                duration: 400,
                complete:function(data){
                }
            }
        });
        load();
        $(window).scrollTop(0);
    });

</script>
