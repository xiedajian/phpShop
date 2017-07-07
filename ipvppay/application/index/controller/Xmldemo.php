<?php

namespace app\index\controller;

use think\Controller;
use think\Db;

class Xmldemo extends Controller
{
    public function index()
    {
//接收传送的数据
        $fileContent = file_get_contents("php://input");


### 把xml转换为数组
//禁止引用外部xml实体
        libxml_disable_entity_loader(true);
//先把xml转换为simplexml对象，再把simplexml对象转换成 json，再将 json 转换成数组。
        $value_array = json_decode(json_encode(simplexml_load_string($fileContent, 'SimpleXMLElement', LIBXML_NOCDATA)), true);


### 获取值，进行业务处理
        $name = $value_array['name'];
        $age = $value_array['age'];
// 通过查询，判断该用户是否存在


### 把查询结果添加到数组中
        $value_array['result'] = 1;


### 把数组转换为xml格式，返回
        $xml = "<?xml version='1.0' encoding='UTF-8'?><group>";
        foreach ($value_array as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</group>";


// echo $xml;
        return $xml;
    }


    public function _isWeixinOrAlipay()
    {
        //判断是不是微信
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false) {
            return "微信";
        }
        //判断是不是支付宝
        if (strpos($_SERVER['HTTP_USER_AGENT'], 'AlipayClient') !== false) {
            return "支付宝";
        }
        //哪个都不是
        return "其他";
    }


}
