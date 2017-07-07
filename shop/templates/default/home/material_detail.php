<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/custom.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.poshytip.min.js" charset="utf-8"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/jquery.nyroModal/styles/nyroModal.css" rel="stylesheet" type="text/css" id="cssfile2" />
<script type="text/javascript">
    $(document).ready(function(){
        $('a[nctype="nyroModal"]').nyroModal();
    });
</script>
<style>
    html{min-height: 100% !important;}
    .order11 {width: 50%;float: left;}
    .order12 {width: 50%;float: left;}
    .order12 p:first-child{font-family:Arial,Verdana,Sans-serif;font-size: 2em;padding-top: 2px;padding-bottom: 15px;font-weight: bolder;}
    .btn-1{width: 150px;height: 40px;line-height:40px;background-color:#3499E4;color: #ffffff;text-align: center;margin-bottom: 30px;margin-top: 20px;font-size: 1em;}
    .btn-2{width: 150px;height: 40px;line-height:40px;background-color:#53C84D;color: #ffffff;text-align: center;margin-bottom: 50px; }
    .order12-1{color:#EF69A7;margin-bottom: 50px;}

    .m-top{margin-top: 7px;}
    .m-content{margin-top: 30px;}
    .download-btn{
        background-color:#EF69A7;color: #ffffff;width: 150px;height: 40px;line-height:40px;text-align: center;font-size: 1.2em;
        margin-top: 20px;cursor: pointer;
    }
    .brief-content{width: 80%}
    #faq{display: none}
    #footer{display: none}
    #think_page_trace{display: none}
</style>
<div class="wrapper">
    <div class="m-top">
        <div class="nch-breadcrumb wrapper">
            <span style="font-size: 16px;">宣传素材</span>
        </div>
        <div class="nch-breadcrumb wrapper">
            <span>
                <a href="index.php?act=material&op=index">全部</a>
            </span>
            <span class="arrow">></span>
            <span>
                <a href="index.php?act=material&op=index&tag_id=<?php echo $output['info']['tag_id'];?>">
                    <?php echo $output['tag_list'][$output['info']['tag_id']];?>
                </a>
            </span>
            <span class="arrow">></span>
            <span><?php echo $output['info']['name'];?></span>
        </div>
    </div>
    <div class="m-content">
        <div class="order11">
        	<a href="<?php echo $output['info']['img_url'];?>" nctype="nyroModal" title="">
        		<img style="<?php if($output['image_type']=='w'){?>max-width: 500px;<?php }else{ ?>max-height: 500px;<?php } ?>" src="<?php echo $output['info']['img_url'];?>"/>
    		</a>
        </div>
        
        
        <div class="order12">
            <p>
                <?php echo $output['info']['name'];?>
            </p>
            <div class="brief-content">
                <?php echo $output['info']['brief'];?>
            </div>
			
            <div class="download-btn" onclick="location.href='index.php?act=material&op=download&id=<?php echo $output['info']['id'];?>'">
                <img src="<?php echo SHOP_TEMPLATES_URL;?>/images/download.png" style="float: left;margin:  8px 0px 0px 18px;">下载
            </div>
            
          
        </div>
    </div>
</div>

