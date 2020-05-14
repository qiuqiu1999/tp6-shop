<?php


namespace app\common\business;


use app\common\lib\Arr;
use app\common\lib\Key;
use think\facade\Cache;


class CartBis extends BaseBis
{
    public function insertRedis($userId, $id, $num)
    {
        $goodSku = (new GoodsSkuBis)->getNormalSkuAndGoods($id);
        $data = [
            'title' => $goodSku['goods']['title'],
            "image" => $goodSku['goods']['recommend_image'],
            "goods_id" => $goodSku['goods']['id'],
            'num' => $num,
            'create_time' => time()
        ];
        try {
            $get = Cache::hGet(Key::userCart($userId), $id);
            if ($get) {
                $get = json_decode($get, true);
                if ($goodSku['good']['stock'] < $get['num']) {
                    throw new \think\Exception($goodSku['goods']['title'] . "的商品库存不足");
                } else {
                    $data['num'] += $get['num'];
                }
            }
            $result = Cache::hSet(Key::userCart($userId), $id, json_encode($data));
        } catch (\Exception $e) {
            return FALSE;
        }
        return $result;
    }

    public function getUserCartCount($userId)
    {
        try {
            $result = Cache::hLen(Key::userCart($userId));
        } catch (\Exception $e) {
            return 0;
        }
        return intval($result);
    }

    public function getUserCart($userId, $ids)
    {
        try {
            if (!empty($id)) {
                $ids = explode(',', $ids);
                $getRes = Cache::hMget(Key::userCart($userId), $ids);
                if (in_array(false, array_values($getRes))) {
                    return [];
                }
            } else {
                $getRes = Cache::hGetAll(Key::userCart($userId));
//                var_dump($getRes);exit;
            }
        } catch (\Exception $e) {
            return [];
        }
        if ($getRes === FALSE) {
            return [];
        }
        $skuIds = array_keys($getRes);

        $skus = (new GoodsSkuBis)->getNormalInIds($skuIds);
        $stocks = array_column($skus, "stock", 'id');
        $skusPrice = array_column($skus, "price", 'id');
        $skuIdSpecsValuesIds = array_column($skus, "specs_value_ids", 'id');
        $specsValues = (new SpecsValueBis())->dealSpecsValue($skuIdSpecsValuesIds);

        $result = [];
        foreach ($getRes as $k => $v) {
            $v = json_decode($v, true);
            if ($ids && isset($stocks[$k]) && $stocks < $v['num']) {
                throw new \think\Exception($v['title'] . "的商品库存不足");
            }
            $v['id'] = $k;
            $v['image'] = preg_match("/http:\/\//", $v['image']) ? $v['image']: request()->domain() . $v['image'];
            $v['price'] = $skusPrice[$k] ?? 0;
            $v['total'] = $v['price'] * $v['num'];
            $v['sku'] = $specsValues[$k] ?? '暂无规格';
            $result[] = $v;
        }
        if (!empty($result)) {
            $result = Arr::arrsSortByKey($result, 'create_time');
        }
        return $result;
    }

    /**
     * 更新购物车商品
     * @param $userId
     * @param $id
     * @param $num
     * @return bool
     * @throws \think\Exception
     */
    public function updateRedis($userId, $id, $num)
    {
        try {
            $get = Cache::hGet(Key::userCart($userId), $id);
        } catch (\Exception $e) {
            return FALSE;
        }
        if ($get) {
            $get = json_decode($get, true);
            $get['num'] = $num;
        } else {
            throw new \think\Exception("抱歉, 购物车中不存在该商品, 请重新尝试操作");
        }
        try {
            $result = Cache::hSet(Key::userCart($userId), $id, json_encode($get));
        } catch (\Exception $e) {
            return FALSE;
        }
        return $result;
    }

    /**
     * 删除购物车商品
     * @param $userId
     * @param $id
     * @return bool
     */
    public function deleteRedis($userId, $ids)
    {
        if (!is_array($ids)) {
            $ids = explode(',', $ids);
        }
        try {
            $result = Cache::hDel(Key::userCart($userId), ...$ids);
        } catch (\Exception $e) {
            return FALSE;
        }
        return $result;
    }
}