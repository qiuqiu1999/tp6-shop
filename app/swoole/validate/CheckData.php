<?php


namespace app\swoole\validate;


use think\Validate;

class CheckData extends Validate
{
    protected $rule = [
        'tel' => 'require',
        'code' => 'require',
//        'captcha' => 'require|checkCaptcha:abc',
    ];
    protected $message = [
        'tel' => '手机号不能为空',
        'code' => '请输入验证码',
    ];

//    protected function checkCaptcha($value, $rule)
//    {
//        if(!captcha_check($value)) {
//            return "您输入的验证码不正确!";
//        }
//        return true;
//    }
}