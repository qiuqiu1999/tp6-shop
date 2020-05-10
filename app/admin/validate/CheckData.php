<?php


namespace app\admin\validate;


use think\Validate;

class CheckData extends Validate
{
    protected $rule = [
        'username' => 'require',
        'password' => 'require',
        'captcha' => 'require|checkCaptcha:abc',
        'name' => 'require',
        'pid' => 'require',
    ];
    protected $message = [
        'username' => '用户名必须',
        'password' => '密码必须',
        'captcha' => '验证码必须',
        'name' => '栏目名必须',
        'pid' => 'PID必须',
    ];

    protected $scene = [
        // 后台登录
        'login' => ['username', 'password', 'captcha'],
        // 添加pid
        'add_category' => ['name', 'pid'],
    ];

    protected function checkCaptcha($value, $rule)
    {
        if(!captcha_check($value)) {
            return "您输入的验证码不正确!";
        }
        return true;
    }


}