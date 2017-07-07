<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/15 0015
 * Time: 上午 11:36
 */
include_once 'config.php';
include_once 'MyRedis.php';
$redis = new MyRedis(REDIS_CONN_HOST,REDIS_CONN_PORT);
$redis->set("lcy","Moving on",30);
echo $redis->ttl('lcy');
//$redis->connect(REDIS_CONN_HOST,REDIS_CONN_PORT);
//$redis->set("name","This is firewater");
echo $redis->get("lcy");
//echo $redis->del('lcy');
?>