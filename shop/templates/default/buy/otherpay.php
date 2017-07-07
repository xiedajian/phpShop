
<style type="text/css">
.btn-change-pay-way,.btn-change-pay-way:hover{background:#949494;padding:8px 50px;border-radius:5px;color:#FFF;font-size:14px;text-decoration: inherit;}
.btn-change-go-center,.btn-change-go-center:hover{background:#42bdeb;padding:8px 50px;border-radius:5px;color:#FFF;font-size:14px;text-decoration: inherit;}
.footer-btns {padding:50px 0;text-align:center;}
</style>

<div class="ncc-main">
<div class="ncc-title">
<h3>找人代付</h3>
    <h5>订单详情内容可通过查看<a href="index.php?act=member_order" target="_blank">我的订单</a>进行核对处理。</h5>
  </div>
  <div style="padding:30px 0;">
                   复制该链接发送给您的好友支付：<span id="content"><?php echo WAP_SITE_URL.DS.'tmpl'.DS.'otherpay.html?sn='.$output["otherpay_sn"];?></span>
      <button style="margin-left: 10px;" id="copy">复制</button>
  </div>
  <div class="footer-btns">
     <a class="btn-change-pay-way" href="index.php?act=buy&op=pay&pay_sn=<?php echo $output["order_info"]["pay_sn"];?>">返回,更换支付方式</a>
     &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
     <a class="btn-change-go-center" href="index.php?act=member&op=home">知道了,返回会员中心</a>
  </div>
 </div>

<script src="<?php echo RESOURCE_SITE_URL;?>/js/ZeroClipboard/ZeroClipboard.min.js" ></script>
<script>
    // 【复制】
    var clip = new ZeroClipboard($("#copy"), {
        moviePath: "<?php echo RESOURCE_SITE_URL;?>/js/ZeroClipboard/ZeroClipboard.swf"
    });
    clip.on('load', function (client) {
        client.on('datarequested', function (client) {
            client.setText($('#content').html());
        });
        client.on('complete', function (client, args) {
            //alert('复制成功!');
            showDialog('复制成功', 'succ','','','','','','','','',2);
        });
    });
    // clip.on('wrongflash noflash', function () {
    //      $('#copy').hide();
    //      showDialog('Flash加载失败', 'error','','','','','','','','',2);
    //  });
    //
</script>