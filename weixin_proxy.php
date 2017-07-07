<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/9/23 0023
 * Time: 下午 4:12
 */
class Wechat
{
    private $appid = 'wxdb4dd0e7b85c22e8';
    private $appsecret = '80f4e5e2fe65a3882dbca3665c9617c1';

    /**
     * 获取token
     */
    public function get_access_token($app_id='',$app_secret='',$code='')
    {

        //获取code，通过code加上appid和appsecret换取access_token
        if(isset($_GET['code']) && isset($_GET['state'])){
            $tokenUrl = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid='.$this->appid.'&secret='.$this->appsecret.'
                        			&code='.$_GET['code'].'&grant_type=authorization_code';
            echo $_GET['code'];
            $token_data = $this->http($tokenUrl);
            if($token_data[0] == 200)
            {
                return json_decode($token_data, TRUE);
            }
        }
        return FALSE;
    }
    /**
     * 获取授权后的微信用户信息
     *
     * @param string $access_token
     * @param string $open_id
     */
    public function get_user_info($access_token = '', $open_id = '')
    {
        if($access_token && $open_id)
        {
            $info_url = "https://api.weixin.qq.com/sns/userinfo?access_token={$access_token}&openid={$open_id}&lang=zh_CN";
            $info_data = $this->http($info_url);

            if($info_data[0] == 200)
            {
                return json_decode($info_data, TRUE);
            }
        }

        return FALSE;
    }
    public function http($url)
    {
        $ch = curl_init();
        $header = "Accept-Charset:utf-8";
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        //获得内容
        $result = curl_exec($ch);

        //关闭curl
        curl_close($ch);

        return $result;
    }

}
$wechat = new Wechat();
$res = $wechat ->get_access_token();
$uinfo = $wechat->get_user_info($res['access_token'],$res['openid']);
$red_ui = $_GET['red_ui'];
if(strstr($red_ui,'?'))
{
    redirect($red_ui.'&uinf='.$uinfo);
}
else
{
    redirect($red_ui.'?uinf='.$uinfo);
}
?>