<?php


namespace app\api\controller;


use app\common\business\order\OrderBis;
use app\common\lib\Snowflake;
use think\facade\Cache;

class Test extends ApiBase
{
    protected $data;

    public function index()
    {
        var_dump($this->userId);
        var_dump($this->username);

    }

    public function test()
    {
        $post = input("param.");
//        if ($post['password'] == 'admin_qjc') {

//            $result = shell_exec("sh /var/www/html/lv1/hook/build.sh");
//            var_dump($result);
//        if ($result) {
//                return Cache::set('testhooks', '[' .date('Y-m-d H:i:s') . '] [success] : success');
//            }
//            Cache::set('testhooks', '[' .date('Y-m-d H:i:s') . '] [error] : exec error');
//        } else {
//            Cache::set('testhooks', '[' .date('Y-m-d H:i:s') . '] [error] : password error');
//        }
    }

    public function getTest()
    {
        var_dump(Cache::get('testhooks'));
    }
}