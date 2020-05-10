<?php


namespace app\common\lib;

/**
 * 字符串类库
 * Class Str
 * @package app\common\lib
 */
class Str
{
    /**
     * 生成token
     * @param $string
     * @return string
     */
    public static function getLoginToken($string)
    {
        $str = md5(uniqid(microtime(true)), true); //生成一个不会重复的字符串
        $token = sha1($str.$string);
        return $token;
    }
}