<?php


namespace app\api\controller;


use app\BaseController;
use app\common\business\UserBis;
use app\common\lib\Show;
use think\facade\View;

class Login extends BaseController
{
    public function userLogin()
    {
        if (!$this->request->isPost()) {
            return show(config("status.error"), '非法请求');
        }

        $phoneNumber = input("param.phone_number", "", "trim");
        $code = input("param.code", "", "intval");
        $type = input("param.type", "", "intval");

        $data = [
            'phone_number' => $phoneNumber,
            'code' => $code,
            'type' => $type
        ];
        $validate = new \app\api\validate\CheckData();
        if (!$validate->scene('login')->check($data)) {
            return Show::error($validate->getError());
        }

        try {
            $userBusiness = new UserBis();
            $result = $userBusiness->login($data);
        }catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        if($result) {
            return Show::success($result);
        }
        return Show::error("登录失败");
    }
}