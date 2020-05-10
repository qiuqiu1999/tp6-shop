<?php


namespace app\wechat\controller;

use app\common\lib\Curl;


class WeChatService
{
    public $accessToken = null;
    public $AppID = null;
    public $AppSecret = null;

    public function __construct()
    {
//        $this->AppID = 'wxc139f09a802e3427';
//        $this->AppSecret = '7926d7756b590f4cdb8cdcfbb0656a31';
        // 测试
        $this->AppID = 'wx6f63af73e801b1e8';
        $this->AppSecret = '40d7ad8244faaf8b0fd5fcda721fe21d';
        $this->accessToken = $this->getAccessToken();
    }

    public function index()
    {
        dump($this->accessToken);
    }

    /**
     * 获取AccessToken
     */
    public function getAccessToken()
    {
        $accessToken = cache("system_wechat_pre_access_token");
        if ($accessToken) {
            return $accessToken;
        }

        $url = "https://api.weixin.qq.com/cgi-bin/token";
        $data = [
            'grant_type' => 'client_credential',
            'appid' => $this->AppID,
            'secret' => $this->AppSecret
        ];
        $result = Curl::curl_request($url, $data, 'post');
        $result = json_decode($result, true);
        if (!isset($result['errcode']) || $result['errcode'] != 0) {
            $accessToken = cache("system_wechat_pre_access_token", $result['access_token'], $result['expires_in']);
        } else {
            // TODO 记录失败日志
        }
        return $accessToken;
    }

    public function getCallBackIp()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/getcallbackip";
        $data = [
            'access_token' => $this->accessToken
        ];
        $result = Curl::curl_request($url, $data, 'post');
        $result = json_decode($result, true);
        if (!isset($result['errcode']) || $result['errcode'] != 0) {
            dd($result);
        }
    }


}