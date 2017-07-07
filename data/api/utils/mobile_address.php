<?php
require_once(dirname(__FILE__) . DS . 'lib/HttpClient.class.php');
function  get_mobile_address($mobile){
	$client = new HttpClient("apis.juhe.cn");
	$result=$client->get("/mobile/get?phone=".$mobile."&key=aacb241dbe96521a45e76094af1a573c");
	if(!result){
		return "";
	}
	$data = json_decode($client->getContent());
	if($data->resultcode!="200"){
		return "";
	}
	return $data->result->province.$data->result->city;
}
?>