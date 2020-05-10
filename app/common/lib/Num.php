<?php

declare(strict_types=1);

namespace app\common\lib;

/**
 * 记录和数字相关的类库方法啊
 * Class Num
 * @package app\common\lib
 */
class Num
{
    /**
     * @param int $length
     * @return int
     */
    public static function getCode($length = 4) :int
    {
        switch ($length) {
            case 4:
                $code = mt_rand(1000, 9999);
                break;
            case 6:
                $code = mt_rand(100000, 999999);
                break;
            default:
                $code = mt_rand(1000, 9999);
        }
        return $code;
    }

}