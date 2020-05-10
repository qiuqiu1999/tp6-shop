<?php


namespace app\common\business\order;


use app\common\interfaces\ob\OrderObServer;
use app\common\business\CartBis;

class OrderCart implements OrderObServer
{
    private $userId;
    private $ids;

    public function __construct($userId, $ids)
    {
        $this->userId = $userId;
        $this->ids = $ids;
    }

    public function placeOrder()
    {
        $this->deleteCart();
    }

    public function deleteCart()
    {
        $result = (new CartBis())->deleteRedis($this->userId, $this->ids);
    }

}