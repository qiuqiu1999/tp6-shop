<?php


namespace app\common\interfaces\ob;

/**
 * 订单观察者
 * Interface OrderObServer
 * @package app\common\interfaces\observerable
 */
interface OrderObServer
{
    public function placeOrder();
}