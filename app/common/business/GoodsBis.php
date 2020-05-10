<?php


namespace app\common\business;


use app\common\lib\Key;
use app\common\model\mysql\Goods as GoodsModel;
use app\common\business\GoodsSkuBis;
use app\common\business\SpecsValueBis;
use think\facade\Cache;

class GoodsBis extends BaseBis
{
    public $model = null;

    public function __construct()
    {
        $this->model = new GoodsModel();
    }

    public function instData($data)
    {
        // 保存商品数据
        $this->model->startTrans();
        try {
            $goodsId = $this->add($data);
            if (!$goodsId) {
                return false;
            }
//            dd($goodsId);
//            sku入库
            if ($data['goods_specs_type'] == 1) {
                $goodsSkuData = [
                    'goods_id' => $goodsId
                ];
                return true;
            } elseif ($data['goods_specs_type'] == 2) {
                $data['goods_id'] = $goodsId;
                $res = (new GoodsSkuBis())->saveAll($data);
                if (!$res) {
                    throw new \think\Exception("sku入库失败");
                }
                // sku回填
                $stock = array_sum(array_column($res, 'stock'));
                $goodsUpdateData = [
                    'price' => $res[0]['price'],
                    'cost_price' => $res[0]['cost_price'],
                    'stock' => $stock,
                    'sku_id' => $res[0]['id']
                ];
                $updateResult = $this->model->updateById($goodsId, $goodsUpdateData);
                if (!$updateResult) {
                    throw new \think\Exception("sku回填失败");
                }
            } else {
                throw new \think\Exception("sku新增失败");
            }
            $this->model->commit();
            return true;
        } catch (\Exception $e) {
            // TODO 记录日志 $e->getMessage();

            $this->model->rollback();
            return false;
        }
    }

    public function getLists($data = [])
    {
        $result = $this->model->getLists($data, 5)->toArray();
        return $result;
    }

    public function updateById($id, $data)
    {
        $goods = $this->model->getNormalById($id);
        if (empty($goods)) {
            throw new \think\Exception("商品不存在");
        }
        $result = $this->model->updateById($id, $data);
        if (!$result) {
            return false;
        }
        return true;
    }

    public function getRotationChart()
    {
        $data = [
            "is_index_recommend" => 1,
        ];
        $field = "sku_id as id, title, big_image as image";
        $result = $this->model->getNormalGoodsByCondition($data, $field);
        return $result->toArray();
    }

    public function getNormalLists($data, $pageSize, $order)
    {
        $field = "sku_id as id, title, recommend_image as image, price";
        $result = $this->model->getNormalLists($data, $pageSize, $field,$order);
        return $result->toArray();
    }

    public function getGoodsDetailBySkuId($skuId)
    {
        $goodsSkuBis = new GoodsSkuBis();
        // 商品数据
        $goodsSku = $goodsSkuBis->getNormalSkuAndGoods($skuId);

        if (!$goodsSku) {
            return [];
        }
        $goods = $goodsSku['goods'];
        // sku数据
        $skus = $goodsSkuBis->getSkuByGoodsId($goods['id']);
        if(!$goodsSku) {
            return [];
        }
        $flagValue = '';
        foreach ($skus as $sku) {
            if ($sku['id'] == $skuId) {
                $flagValue = $sku['specs_value_ids'];
            }
        }
        $gids = array_column($skus, 'id', 'specs_value_ids');
//        dump($goodsSku);exit;
        $sku = (new SpecsValueBis())->dealGoodsSkus($gids, $flagValue);
        $result = [
            'title' => $goods['title'],
            'price' => $goodsSku['price'],
            'cost_price' => $goodsSku['cost_price'],
            'sales_count' => 0,
            'stock' => $goodsSku['stock'],
            'gids' => $gids,
            'image' => $goods['carousel_image'],
            'sku' => $sku,
            'detail' => [
                "d1" => [
                    "商品编码" => $goodsSku['id'],
                    "商家时间" => $goodsSku['create_time']
                ],
                "d2" => preg_replace('/(<img src=")(.*?)/', '$1' . request()->domain().'$2',$goods['description'])
            ],
        ];

        // 记录数据到redis 作为PV统计
        Cache::inc(Key::goodsPv($goods['id']));
        return $result;
    }

    public function categoryGoodsRecommend($categoryIds)
    {
        if (empty($categoryIds)) {
            return [];
        }
        $categoryBis = new CategoryBis();
        $categoryFirsts = $categoryBis->getNormalInIds($categoryIds);
        $first = [];
        foreach ($categoryFirsts as $categoryFirst) {
            $first[$categoryFirst['id']] = $categoryFirst;
        }
        $categorySeconds = $categoryBis->getNormalInPids($categoryIds);
        $result = [];
        foreach ($categoryIds as $key => $categoryId) {
            $list = [];
            foreach ($categorySeconds as $categorySecond) {
                if ($categorySecond['pid'] == $categoryId) {
                    $list[] = [
                        'name' => $categorySecond['name'],
                        'category_id' => $categorySecond['id']
                    ];
                }
            }
            $result[$key]['categorys'] = [
                'category_id' => $first[$categoryId]['id'],
                'name' => $first[$categoryId]['name'],
                'icon' => $first[$categoryId]['icon'],
                'list' => $list
            ];
        }
        foreach ($categoryIds as $key => $categoryId) {
            $result[$key]['goods'] = $this->getNormalGoodsFindInSetCategoryId($categoryId);
        }
        return $result;
    }

    public function getNormalGoodsFindInSetCategoryId($categoryId)
    {
        $field = "sku_id as id, title, price, recommend_image as image";
        $result = $this->model->getNormalGoodsFindInSetCategoryId($categoryId, $field);
        return $result;
    }
}