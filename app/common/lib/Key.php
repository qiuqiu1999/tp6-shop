<?php


namespace app\common\lib;

/**
 * RedisKey类库
 * Class Key
 * @package app\common\lib
 */
class Key
{
    /**
     * 用户购物车Key
     * @param $userId
     * @return string
     */
    public static function userCart($userId)
    {
        return config("redis.mall_cart_pre") . $userId;
    }

    public static function token($token)
    {
        return config("redis.mall_token_pre"). $token;
    }

    public static function tokenExpire()
    {
        return config("redis.mall_token_expire");
    }

    public static function phoneCode($phoneNumber)
    {
        return config("redis.mall_phone_code_pre") . $phoneNumber;
    }

    public static function phoneCodeExpire()
    {
        return config("redis.mall_phone_code_expire");
    }

    public static function goodsPv($goodsId)
    {
        return config("redis.mall_goods_pv_pre") . $goodsId;

    }

    public static function orderStatus()
    {
        return config("redis.mall_order_status_key");
    }

    public static function orderExpire()
    {
        return config("redis.mall_order_expire");
    }
}