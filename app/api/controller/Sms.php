<?php


namespace app\api\controller;


use app\BaseController;
use app\common\business\Sms as SmsBus;
use app\common\lib\Show;

class Sms extends BaseController
{
    public function code() :object
    {
        $phoneNumber = input('param.phone_number', '', 'trim');
        $data = [
            'phone_number' => $phoneNumber
        ];
        try {
            validate(\app\api\validate\CheckData::class)->scene('send_code')->check($data);
        }catch(\think\exception\ValidateException $e) {
            return Show::error($e->getError());
        }
        $type = 'ali';
        if(SmsBus::sendCode($phoneNumber, 6, $type)) {
            return Show::success();
        }
        return Show::error("发送失败");
    }
}