<?php


namespace app\common\model\mysql;


use think\Config;

class Goods extends BaseModel
{
    protected $table = "mall_goods";

    /**
     * 获取所有商品的列表
     * @param array $where
     * @param int $num
     * @return array|bool|\think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getLists($where = [], $pageSize = 10)
    {
        $order = [
            'id' => 'desc'
        ];
        $result = $this->where("status", "<>", config("status.mysql.table_delete"))
            ->where($where)
            ->order($order)
            ->paginate($pageSize);
        if (!$result) {
            return \App\common\lib\Arr::paginateData($pageSize);
        }
        return $result;
    }

    /**
     * 获取商品首页推荐大图
     * @param $where
     * @param bool $field
     * @param int $limit
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalGoodsByCondition($where, $field = true, $limit = 5)
    {
        $order = ["listorder" => "desc"];
        $where['status'] = config("status.mysql.table_normal");
        $result = $this->field($field)->where($where)->order($order)->limit($limit)->select();
        return $result;
    }

    /**
     * 图片获取器
     * @param $value
     */
    public function getImageAttr($value)
    {
        return request()->domain() . $value;
    }


    public function getCarouselImageAttr($value)
    {
        if (!empty($value)) {
            $value = explode(",", $value);
            $value = array_map(function ($v) {
                return request()->domain() . $v;
            }, $value);
        }
        return $value;
    }


    /**
     * 正常状态商品的列表
     * @param $data
     * @param int $num
     * @param bool $field
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getNormalLists($data, $num = 10, $field = true, $order)
    {
//        $order = ["listorder" => "desc", "id" => "desc"];
        $res = $this;
        if (isset($data['category_path_id'])) {
            $res = $this->whereFindInSet("category_path_id", $data['category_path_id']);
        }
//        $list = $res->where("status", "=", config("status.mysql.table_normal"))
//                ->order($order)
//                ->field($field)
//                ->paginate($num);
        $list = $res->field($field)
            ->where("status", "=", config("status.mysql.table_normal"))
            ->order($order)
            ->paginate($num);
//        dump($list);
//        die;
        return $list;
    }

    public function getNormalGoodsFindInSetCategoryId($categoryId, $field = true)
    {
        $order = ["listorder" => "desc", 'id' => "desc"];
        $result = $this->whereFindInSet("category_path_id", $categoryId)
            ->where("status",'=', config("status.mysql.table_normal"))
            ->order($order)
            ->field($field)
            ->limit(10)
            ->select();
        return $result->toArray();
    }
}