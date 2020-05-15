<?php


namespace app\common\business;


use app\common\model\mysql\GoodsSku as GoodsSkuModel;


class GoodsSkuBis extends BaseBis
{
    public $model = null;

    public function __construct()
    {
        $this->model = new GoodsSkuModel();
    }

    /**
     * 有规格商品 sku添加
     * @param $data
     * @return array|bool
     * @throws \Exception
     */
    public function saveAll($data)
    {
        if (empty($data['skus'])) {
            return false;
        }
        foreach ($data['skus'] as $value) {
            $insertData[] = [
                'goods_id' => $data['goods_id'],
                'specs_value_ids' => $value['propvalnames']['propvalids'],
                'price' => $value['propvalnames']['skuSellPrice'],
                'cost_price' => $value['propvalnames']['skuMarketPrice'],
                'stock' => $value['propvalnames']['skuStock'],
                'status' => config("status.mysql.table_normal")
            ];

        }
        $result = $this->model->saveAll($insertData);
        return $result->toArray();
    }

    /**
     * 无规格商品 sku添加
     * @param $data
     * @return array|bool
     */
    public function saveNoneSpecsSku($data)
    {
        if (empty($data)) {
            return false;
        }
        $insertData = [
            'goods_id' => $data['goods_id'],
            'price' => $data['price'],
            'cost_price' => $data['cost_price'],
            'stock' => $data['stock'],
            'status' => config("status.mysql.table_normal")
        ];
        $result = $this->model->create($insertData);
        return $result->toArray();
    }

    public function getNormalSkuAndGoods($id)
    {
        $result = $this->model->with(["goods" => function ($query) {
            $field = "id, title, category_id, category_path_id, ";
            $field .= "promotion_title, goods_unit, keywords, sub_title, stock, price, cost_price, sku_id, is_show_stock, ";
            $field .= "goods_specs_type, recommend_image, carousel_image, description, is_index_recommend ";
            $query->field($field);
        }])->find($id);
        if (!$result) {
            return [];
        }
        $result = $result->toArray();
        if ($result['status'] != config("status.mysql.table_normal")) {
            return [];
        }
        if (empty($result['goods'])) {
            return [];
        }
        return $result;
    }

    public function getSkuByGoodsId($goodsId = 0)
    {
        $field = "id, goods_id, specs_value_ids, price, cost_price, stock";
        $result = $this->model->getNormalByGoodsId($goodsId, $field);
        if (!$result) {
            return [];
        }
        return $result;
    }

    public function getNormalInIds($ids)
    {
        if (!$ids) {
            return [];
        }
        $result = $this->model->getNormalInIds($ids);
        $result = $result->toArray();
        if (empty($result)) {
            return [];
        }
        return $result;
    }

    public function updateDecStock($data)
    {
        foreach ($data as $value) {
            $this->model->decStock($value['id'], $value['num']);
        }
        return true;
    }
}