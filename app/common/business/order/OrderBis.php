<?php


namespace app\common\business\order;


use app\common\business\GoodsSkuBis;
use app\common\interfaces\ob\OrderObServerable;
use app\common\lib\Key;
use app\common\lib\Snowflake;
use app\common\model\mysql\Order as OrderModel;
use app\common\business\CartBis;
use app\common\model\mysql\OrderGoods;
use think\facade\Cache;


class OrderBis extends OrderObServerable
{
    private $model = NULL;

    public function __construct()
    {
        $this->model = new OrderModel();
    }

    public function save($data)
    {
        $cartBis = new CartBis;
        $orderId = Snowflake::getInstance()->setWorker(mt_rand(1, 1023))->id();
        $result = $cartBis->getUserCart($data['user_id'], $data['ids']);
        if (!$result) {
            return false;
        }
        $totalPrice = array_sum(array_column($result, 'total_price'));
        // 新增Order表数据
        $orderData = [
            'user_id' => $data['user_id'],
            'order_id' => $orderId,
            'total_price' => $totalPrice,
            'address_id' => $data['address_id'],
        ];
        // 新增OrderGoods表数据
        $newResult = array_map(function ($v) use ($orderId) {
            $v['sku_id'] = $v['id'];
            unset($v['id']);
            $v['order_id'] = $orderId;
            return $v;
        }, $result);
        $this->model->startTrans();
        try {
            $id = $this->add($orderData);
            if (!$id) {
                return 0;
            }
            // 保存OrderGoods表数据
            $orderGoodsResult = (new OrderGoods())->saveAll($newResult);
            // 减去商品库存
            $skuResult = (new GoodsSkuBis())->updateDecStock($result);
            if (!$orderGoodsResult || !$skuResult) {
                $this->model->rollback();
                return false;
            }
            $cartBis->deleteRedis($data['user_id'], $data['ids']);
            $this->model->commit();
            // 更新goods库存
            try {
                Cache::zAdd(Key::orderStatus(), time() + Key::orderExpire(), $orderId);
            } catch (\Exception $e) {

            }
            // 返回订单ID
            return ["id" => (string)$orderId];
        } catch (\Exception $e) {
            $this->model->rollback();
            return false;
        }
    }

    public function detail($data)
    {
        $condition = [
            'user_id' => $data['user_id'],
            'order_id' => $data['order_id']
        ];
        try {
            $orders = $this->model->getByContition($condition);
        } catch (\Exception $e) {
            return [];
        }
        if (!$orders) {
            return [];
        }
        $orders = $orders->toArray();
        $orders = !empty($orders) ? $orders[0] : [];
        if (empty($orders)) {
            return [];
        }
        $orders['id'] = $orders['order_id'];
        $orders['consignee_info'] = "广东 广州 荔湾区 芳村大道 接龙里x巷x号";
        $orders['mall_title'] = "111";
        // 根据OrderId查询 order_goods数据
        $orderGoods = (new OrderGoodsBis())->getByOrderId($data['order_id']);
        $orders['malls'] = $orderGoods;
        return $orders;
    }

    public function order($data)
    {
        $userInfo = [
            'username' => 'cc',
            'phone' => '13415560766',
            'email' => '244907263@qq.com',
            'openid' => 'wxadas123'
        ];
//        $this->addObserver(new OrderCart($data['user_id'], $data['ids']));
        $this->addObserver(new OrderMsg($userInfo));
        $this->addObserver(new OrderLogs());
        $this->notify();
    }


    public function checkOrderStatus()
    {
        $result = Cache::zRangeScore(Key::orderStatus(), 0,time(), ['limit', 0, 10]);
        if (empty($result) || empty($result[0])) {
            echo 1;
            return false;
        }

        try {
            $delRedis = Cache::zRem(Key::orderStatus(), $result[0]);
        } catch (\Exception $e) {
            // 记录日志
            $delRedis = "";
        }
        if ($delRedis) {
            echo "订单id:{$result[0]}在规定时间内没有完成支付, 我们判断子订单为无效订单";
            /**
             * 1.取消订单(修改订单状态
             * 2.查询order_goods表归还库存(goods_sku表 goods表)
             */

        } else {
            return false;
        }
        return true;
    }
}