<?php


namespace app\api\controller\order;


use app\api\controller\AuthBase;
use app\common\lib\Show;
use app\common\business\order\OrderBis;

class Index extends AuthBase
{
    public function save()
    {
        $addressId = input("param.address_id", '0', 'intval');
        $ids = input("param.ids", '', 'trim');
        if (!$addressId || $ids) {
            return Show::error("参数错误");
        }
        $data = [
            'ids' => $ids,
            'address_id' => $addressId,
            'user_id' => $this->userId
        ];
        try {
            $result = (new OrderBis)->save($data);
        } catch (\Exception $e) {
            // 记录错误信息
            return Show::error($e->getMessage(), $e->getCode());
        }
        if (!$result) {
            return Show::error("提交订单失败, 请稍后重试");
        }
        return Show::success($result);
    }

    public function read()
    {
        $id = input("param.id", 0, 'intval');
        if (empty($id)) {
            return Show::error("参数错误");
        }
        $data = [
            'user_id' => $this->userId,
            'order_id' => $id
        ];
        $result = (new OrderBis())->detail($data);
        if (!$result) {
            return Show::error("获取订单失败");
        }
        return Show::success($result);
    }
}