<?php defined('InShopNC') or exit('Access Invalid!');?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>支付成功</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
<style>
    .success { width: 100%; text-align: center; padding: 200px 0 10px 0; color: green; }
    .fail { width: 100%; text-align: center; padding: 200px 0 10px 0; color: red; }
    .return { width: 100%; text-align: center; }
</style>
</head>

<body>
    <script>window.demo.checkPaymentAndroid("<?php echo $output['result'];?>");</script>
    <div class="<?php echo $output['result'];?>" >
        <?php echo $output['message'];?>
    </div>
    <div class="return" >
        <a href="<?php echo WAP_SITE_URL;?>/tmpl/member/order_center.html?op=order_wait_send"><img src="<?php echo WAP_SITE_URL;?>/images/pay_ok.png"></a>
    </div>
</body>
</html>

