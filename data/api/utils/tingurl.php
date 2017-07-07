<?php
require_once(dirname(__FILE__) . DS . 'lib/HttpClient.class.php');
function  get_tingurl($longurl){
	$client = new HttpClient("dwz.cn");
	$result=$client->post("/create.php",array("url"=>$longurl));
	if(!result){
		return $longurl;
	}
	$data = json_decode($client->getContent());
	if($data->status===0){
		return $data->tinyurl;
	}
	return $longurl;
}
?>