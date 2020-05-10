<?php


namespace app\common\interfaces\ob;

use app\common\business\BaseBis;

/**
 * 订单被观察者
 * Interface OrderObServer
 * @package app\common\interfaces\ob
 */
abstract class OrderObServerable extends BaseBis
{
    private $obServices = [];

    // 添加观察者
    public function addObserver($observer)
    {
        $this->obServices[] = $observer;
    }

    // 通知观察者
    public function notify()
    {
        if (empty($this->obServices)) {
            return false;
        }
        foreach ($this->obServices as $obService) {
            $obService->placeOrder();
        }
        return true;
    }
}