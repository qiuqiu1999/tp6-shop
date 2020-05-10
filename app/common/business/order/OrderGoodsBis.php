<?php


namespace app\common\business\order;


use app\common\business\BaseBis;
use app\common\model\mysql\OrderGoods as OrderGoodsModel;


class OrderGoodsBis extends BaseBis
{
    public $model = NULL;

    public function __construct()
    {
        $this->model = new OrderGoodsModel();
    }

    public function getByOrderId($orderId)
    {
        try {
            $condition = ['order_id' => $orderId];
            $result = $this->model->getByCondition($condition);
        } catch(\Exception $e) {
            return [];
        }
        if (!$result) {
            return [];
        }
        return $result->toArray();
    }
}