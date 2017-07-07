<?php defined('InShopNC') or exit('Access Invalid!');?>

<!doctype html>
<html lang="zh">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET;?>">
        <title><?php echo $output['html_title'];?></title>
        <meta name="keywords" content="<?php echo $output['seo_keywords']; ?>" />
        <meta name="description" content="<?php echo $output['seo_description']; ?>" />
        <link rel="shortcut icon" href="<?php echo BASE_SITE_URL;?>/favicon.ico" />
        <link href="<?php echo COURSE_RESOURCE_SITE_URL;?>/css/base.css" rel="stylesheet" type="text/css">
        <script src="<?php echo COURSE_RESOURCE_SITE_URL;?>/js/jquery.js"></script>
        <script src="<?php echo COURSE_RESOURCE_SITE_URL;?>/js/jquery.masonry.js"></script>
    </head>
    <body>
        <?php require_once template('top');?>
        <?php require_once($tpl_file);?>
        <?php require_once template('footer');?>
    </body>
</html>
