<?php


namespace app\common\business;

use app\common\model\mysql\SpecsValue as SpecsValueModel;
use app\common\business\SpecsBis;

class SpecsValueBis extends BaseBis
{
    public $model = null;

    public function __construct()
    {
        $this->model = new SpecsValueModel();
    }

    public function add($data)
    {
        $data['status'] = config("status.mysql.table_normal");
        $result = $this->model->save($data);
        return $this->model->id;
    }

    public function getBySpecsId($specsId)
    {
        $field = "id,specs_id,name";
        $specss = $this->model->getBySpecsId($specsId, $field);

        if(empty($specss)) {
            return [];
        }
        return $specss->toArray();
    }

    // 组装数据方法
    public function dealGoodsSkus($gids, $flagValue)
    {
        $specsValueKeys = array_keys($gids);
        foreach ($specsValueKeys as $specsValueKey) {
            $specsValueKey = explode(',', $specsValueKey);
            foreach ($specsValueKey as $k => $v) {
                $news[$k][] = $v;
                $specsValueIds[] = $v;
            }
        }
        $specsValueIds = array_unique($specsValueIds);
        $specsValues = $this->getNormalInIds($specsValueIds);

        $flagValue = explode(",", $flagValue);
        $result = [];
        foreach ($news as $k => $v) {
            $v = array_unique($v);
            $list = [];
            foreach ($v as $vv) {
                $list[] = [
                    'id' => $vv,
                    'name' => $specsValues[$vv]['name'],
                    'flag' => in_array($vv, $flagValue),
                ];
            }
            $result[$k] = [
                'name' => $specsValues[$v[0]]['specs_name'],
                "list" => $list,
            ];
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
        $specsNames = (new SpecsBis)->getNormalSpecss();
        $specsNamesArr = array_column($specsNames, "name", "id");
        $res = [];
        foreach ($result as $v) {
            $res[$v['id']] = [
                'name' => $v['name'],
                'specs_name' => $specsNamesArr[$v['specs_id']] ?? "",

            ];
        }
        return $res;
    }

    public function dealSpecsValue($skuIdSpecsValuesIds)
    {
        $ids = array_values($skuIdSpecsValuesIds);
        $ids = implode(',', $ids);
        $ids = array_unique(explode(',', $ids));
        $specsValues = $this->getNormalInIds($ids);
        if (!$specsValues) {
            return [];
        }
        $result = [];
        foreach ($skuIdSpecsValuesIds as $skuId => $specs) {
            $specs = explode(",", $specs);
            // 处理sku默认文案
            $skuStrArr = [];
            // $pecs [1,7]
            foreach ($specs as $spec) {
                $skuStrArr[] = $specsValues[$spec]['specs_name'].':'.$specsValues[$spec]['name'];
            }
            $result[$skuId] = implode(' ', $skuStrArr);
        }
        return $result;
    }
}