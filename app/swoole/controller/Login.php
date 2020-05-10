<?php


namespace app\swoole\controller;


use app\BaseController;
use app\common\lib\Predis;
use app\common\lib\Redis;
use app\live\validate\CheckData;

class Login extends BaseController
{
    public function index()
    {
        dump($_POST['http_server']);
    }

    public function login()
    {
        // 判断请求方式
        if (!$this->request->isAjax()) {
            return show(config("status.error"), "请求方式错误");
        }

        $tel = $this->request->param("tel");
        $code = $this->request->param("code");
        // 校验参数
        $data = ['tel' => $tel,
            'code' => $code
        ];
        $validate = new CheckData();
        if (!$validate->check($data)) {
            return show(config("status.error"), $validate->getError());
        }

        // TODO: 记录登录日志
        $result = $code != Predis::getInstance()->get(Redis::smsKey($tel)) ?: false;
        if ($result) {
            return show(config("status.error"), "验证码错误",null);
        }

        $data = [
            'user' =>$tel,
            'srcKey' => md5(Redis::userKey($tel)),
            'time' => time(),
            'isLogin' => true,
        ];
        Predis::getInstance()->set(Redis::userKey($tel), $data);
        return show(config("status.success"), "登录成功",null);

    }
}