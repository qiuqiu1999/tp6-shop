<?php


namespace app\common\model\mysql;


use think\Model;

class GoodsSku extends BaseModel
{
    protected $table = "mall_goods_sku";

    public function goods()
    {
        return $this->hasOne(Goods::class, 'id', 'goods_id');
    }

    public function getNormalByGoodsId($goodsId = 0, $field = true)
    {
        $where = [
            'goods_id' => $goodsId,
            'status' => config("status.mysql.table_normal")
            ];
        $result = $this->field($field)->where($where)->select();
        if(!$result) {
            return [];
        }
        return $result->toArray();
    }

    public function incStock($id, $num)
    {
        $where = [
            'id' => $id,
            'status' => config("status.mysql.table_normal"),
        ];
        return $this->where($where)->inc("stock", $num);
    }

    public function decStock($id, $num)
    {
        $where = [
            'id' => $id,
            'status' => config("status.mysql.table_normal"),
        ];
        return $this->where($where)->dec("stock", $num);
    }
}