<?php


namespace app\common\business;


use app\common\lib\JwtAuth;
use think\facade\Db;

class Login
{
    public  function login()
    {
        $data = request()->param();
        $username = $data['username'];
        $password = md5($data['password']);

        $map = ['username' => $username, 'password' => $password];
        $user = Db::table('users')->where($map)->find();

        if (is_null($user)) {
            $data = ['code' => -1, 'msg' => '用户或密码错误'];
        } else {
            $instance = JwtAuth::getInstance();
            $token = $instance->setUid($user['id'])->encode()->getToken();
            $data = ['code' => 1, 'msg' => '登录成功', '_token' => $token];
        }
        return json($data);
    }
}