<?php


namespace app\api\controller;


use app\common\lib\Key;
use app\common\lib\Show;
use think\facade\Cache;

class Logout  extends AuthBase
{
    public function logout()
    {
        try {
            Cache::del(Key::token($this->token));
        } catch (\Exception $e) {
            return Show::error("退出失败");
        }
        return Show::success();
    }
}