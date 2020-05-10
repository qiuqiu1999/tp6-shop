<?php


namespace app\common\business;

use app\common\model\mysql\Specs as SpecsModel;

class SpecsBis extends BaseBis
{
    public $model = null;

    public function __construct()
    {
        $this->model = new SpecsModel();
    }

    public function add($data)
    {
        $data['status'] = config("status.mysql.table_normal");
        $category = $this->model->getNormalByName($data['name']);

        if (!empty($category)) {
            throw new \think\Exception('规格名已存在');
        }
        $result = $result = $this->model->save($data);
        if (empty($result)) {
            return false;
        }
        return $this->model->getLastInsID();
    }

    public function getNormalSpecss()
    {
        $field = "id,name";
        $specss = $this->model->getNormalSpecss($field);
        if(empty($specss)) {
            return [];
        }
        $specss = $specss->toArray();
        return $specss;
    }
}