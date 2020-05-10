<?php

declare(strict_types = 1);
namespace app\common\business;


use app\common\lib\ClassArr;
use app\common\lib\Key;
use app\common\lib\Num;
use think\facade\Db;
use app\common\lib\sms\AliSms;
use think\facade\Log;

/**
 * 短信业务Business
 * Class Sms
 * @package app\common\business\Mall
 */
class Sms
{
    public static function sendCode(string $phoneNumber, int $length, string $type = 'ali') :bool
    {
        $code = Num::getCode($length);
        $type = ucfirst($type);
        $classStats = ClassArr::smsClassStat(); // 获取所有短信发送类(数组形式返回)

        $classObj = ClassArr::initClass($type, $classStats, [], false);// 进行实例化或取得单例句柄

        if($classObj === false) {
            return false;
        }
        $sms = $classObj::sendSms($phoneNumber, $code);

        if($sms) {
            cache(Key::phoneCode($phoneNumber), $code, Key::phoneCodeExpire());
        }

        $result = [
            "type" => "SMS",
            "address" => $phoneNumber,
            "code" => $code,
            "create_time" => time(),
        ];
        $result['status'] = 'S';
        Db::table("code_log")->save($result);
        return true;
    }
}