<?php


namespace app\common\lib\task;

use app\common\lib\ClassArr;
use app\common\lib\Key;
use Swoole\Server;
use app\common\lib\Predis;
use Swoole\WebSocket\Server as WebsocketServer;
use think\facade\Cache;
use think\facade\Db;
use think\facade\Log;
use app\common\lib\sms\AliSms;
use app\common\lib\Redis;

/**
 * 异步任务中心
 * Class Task
 * @package app\common\lib\task
 */
class Task
{
    /** @var WebsocketServer */
    public $server = null;

    /**
     * 异步发送验证码
     * @param $data
     * @return bool
     */
    public function sendSms($data, $server)
    {
        $phoneNumber = $data['phone_number'];
        $code = $data['code'];
        $type = ucfirst($data['type']);
        try {
            $classArr = ClassArr::smsClassStat();
            $obj = ClassArr::initClass($type, $classArr, [], false);
//            var_dump($classArr);return;
            if ($obj === false) {
                return false;
            }
            $response = $obj::sendSms($phoneNumber, $code);
        } catch (\Exception $e) {
//            // TODO: 记录日志
            Log::write("短信发送异常: " . $e->getMessage(), 'error');
            return false;
        }

        if ($response['Code'] === "OK") {
            $data['status'] = 'N';
        }

        $result = [
            "type" => "SMS",
            "address" => $phoneNumber,
            "code" => $code,
            "create_time" => time(),
        ];
//        $redis = new \Swoole\Coroutine\Redis();
//        $redis->connect("127.0.0.1", 6379);
//        $redis->auth('lswlcccom');
//        Predis::getInstance()->set(Redis::smsKey($tel, $code, 120);
//        Predis::getInstance()->set(Redis::smsKey($tel, $code, 120);
        Cache::set(Key::phoneCode($phoneNumber), $code, Key::phoneCodeExpire());
//        cache(config("redis.sms_pre"), $code, config("redis.code_expire"));
//        $result['status'] = 'S';
//        Log::write("验证码发送成功" . json_encode($result));
//        Db::table("code_log")->save($result);
        return true;
    }

    public function livePush($data, $server)
    {
        $this->server = $server;
        $clients = Predis::getInstance()->sMembers("live_game_key");
        foreach ($clients as $client) {
            $this->server->push($client, json_encode($data));
        }
        return true;
    }
}