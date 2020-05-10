<?php


namespace app\common\business\order;


use app\common\interfaces\ob\OrderObServer;

class OrderLogs implements OrderObServer
{
    public function placeOrder()
    {
        $this->orderLog();
    }

    public function orderLog()
    {
        echo "记录订单log...";
    }


}