<?php


namespace app\swoole\controller;


use app\BaseController;
use app\common\lib\Show;
use Swoole\Server;

class Send extends BaseController
{
    public function sendSms(Server $server)
    {
        $phoneNumber = $this->request->param("phone_number", 0, 'intval');
        if (empty($tel)) {
            return show(config("status.error"), "手机号不能为空", null);
        }
        $code = (string)mt_rand(1000, 9999);

        $taskData = [
            'method' => 'sendSms',
            'data' => [
                'phone_number' => $phoneNumber,
                'code' => $code,
                'type'=> 'ali'
            ]
        ];
        $server->task($taskData);
        return Show::success([], "验证码发送成功");
    }
}