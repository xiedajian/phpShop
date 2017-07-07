<?php
/**
 * Created by PhpStorm.
 * User: pc
 * Date: 15-8-14
 * Time: 下午4:16
 */

defined('InShopNC') or exit('Access Invalid!');

class weixin {
    /*测试
    private $appid = 'wx6107358428eac27d';
    private $appsecret = '9141d6372fb595f24916f9cf6ab5663c';
    */
    private $appid = 'wxdb4dd0e7b85c22e8';
    private $appsecret = '80f4e5e2fe65a3882dbca3665c9617c1';


    /*获取微信用户身份
     * access_token 网页授权接口调用凭证,注意：此access_token与基础支持的access_token不同
     * expires_in access_token接口调用凭证超时时间，单位（秒）
     * refresh_token 用户刷新access_token
     * openid 用户唯一标识
     * scope 用户授权的作用域，使用逗号（,）分隔
     */
    public function get_member_auth($code){
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?appid=$this->appid&secret=$this->appsecret&code=$code&grant_type=authorization_code";
        $data =$this->_curl_get($url);
        if(isset($data['errcode'])){
            throw new Exception($data['errmsg'],$data['errcode']);
        }
        return $data;
    }
    /*获取微信用户信息
     * openid 用户的唯一标识
     * nickname 用户昵称
     * sex 用户的性别，值为1时是男性，值为2时是女性，值为0时是未知
     * province 用户个人资料填写的省份
     * country 国家，如中国为CN
     * headimgurl 用户头像，最后一个数值代表正方形头像大小（有0、46、64、96、132数值可选，0代表640*640正方形头像），用户没有头像时该项为空。若用户更换头像，原有头像URL将失效
     * privilege 用户特权信息，json 数组，如微信沃卡用户为（chinaunicom）
     * unionid 只有在用户将公众号绑定到微信开放平台帐号后，才会出现该字段
     */
    public function get_member_info($open_id,$token){
        $url = "https://api.weixin.qq.com/sns/userinfo?access_token=$token&openid=$open_id&lang=zh_CN";
        $data =  $this->_curl_get($url);
        if(isset($data['errcode'])){
            throw new Exception($data['errmsg'],$data['errcode']);
        }
        return $data;
    }





    private function _curl_post($url,$params=array()){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_HEADER, 0); // 不要http header 加快效率
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_POST, 1);    // post 提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output,true);
    }

    private function _curl_get($url){
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);    // 要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_HEADER, 0); // 不要http header 加快效率
        //curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_TIMEOUT, 15);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);    // https请求 不验证证书和hosts
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        $output = curl_exec($ch);
        curl_close($ch);
        return json_decode($output,true);
    }

} 