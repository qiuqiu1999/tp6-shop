<?php


namespace app\common\business\order;


use app\common\interfaces\ob\OrderObServer;

class OrderMsg implements OrderObServer
{
    private $username = null;
    private $phone = null;
    private $email = null;
    private $openid = null;

    public function __construct($userInfo)
    {
        if (empty($userInfo)) {
            return false;
        }
        $this->username = $userInfo['username'];
        $this->phone = $userInfo['phone'];
        $this->email = $userInfo['email'];
        $this->openid = $userInfo['openid'];
    }

    public function placeOrder()
    {
        $this->sendWx();
        $this->sendEmail();
        $this->sendSms();

    }

    public function sendWx()
    {
        // 入队列
        echo "正在向" . $this->openid . "发送微信消息...<br/>";
    }

    public function sendEmail()
    {
        // 入队列
        echo "正在向" . $this->email . "发送邮件消息...<br/>";
    }

    public function sendSms()
    {
        // 入队列
        echo "正在向" . $this->phone . "发送短信消息...<br/>";
    }
}