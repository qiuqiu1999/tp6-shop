<?php


namespace app\common\lib;


/**
 * 工厂
 * Class ClassArr
 * @package app\common\lib
 */
class ClassArr
{
    public static function smsClassStat()
    {
        return [
            'Ali' => "app\common\lib\sms\AliSms",
            'Baidu' => "app\common\lib\sms\BaiduSms",
            'Jd' => "app\common\lib\sms\Jdsms",
        ];
    }

    /**
     * 短信工程
     * @param $type
     * @param $class
     * @param array $params
     * @param bool $needInstance
     * @return bool|mixed|object
     * @throws \ReflectionException
     */
    public static function initClass($type, $class, $params = [], $needInstance = false)
    {
        if(!array_key_exists($type, $class)) {
          return false;
        }
        $className = $class[$type];

        //->newInstanceArgs($args) => new A();
//        return $needInstance == true ? new $className($params) : $className;
        return $needInstance == true ? (new \ReflectionClass($className))->newInstanceArgs($params) : $className;
    }
}