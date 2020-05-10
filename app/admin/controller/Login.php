<?php


namespace app\admin\controller;


use app\admin\business\AdminBusiness;
use app\BaseController;
use think\facade\View;

class Login extends BaseController
{
//    public function initialize()
//    {
//        if($this->isLogin()){
//            return $this->redirect(url("/admin/index/index"));
//        }
//    }

    public function index()
    {
        return View::fetch();
    }

    public function check()
    {
        if (!request()->isPost()) {
            return show(config("status.error"), '求情方式错误');
        }

        $username = request()->param("username", "", "trim");
        $password = request()->param("password", "", "trim");
        $captcha = request()->param("captcha", "", "trim");

        $data = [
            'username' => $username,
            'password' => $password,
            'captcha' => $captcha
        ];
        $validate = new \app\admin\validate\CheckData();
        // 校验参数
//        if (!$validate->check($data)) {
//            return show(config("status.error"), $validate->getError());
//        }
        if(!$validate->scene('login')->check($data)) {
            return show(config("status.error"), $validate->getError());
        }
        // 校验用户
        $adminBusiness = new AdminBusiness();
        $result = $adminBusiness->login($data);

        if($result) {
            return show(config("status.success"), '登录成功');
        }
        return show(config("status.error"),"登录失败");
    }

    public function logout()
    {
        session(config("admin.session_admin"), null);
        return redirect(url('admin/login/index'));
    }

    public function phpinfo()
    {
        phpinfo();
    }
}