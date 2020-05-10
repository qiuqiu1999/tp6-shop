<?php


namespace app\common\model\mysql;



class SpecsValue extends BaseModel
{
    protected $table = "mall_specs_value";

    public function getBySpecsId($SpecsId, $field = '*')
    {
        $where = [
            'status' => config("status.mysql.table_normal"),
            'specs_id' => $SpecsId
        ];
        $result = $this->field($field)->where($where)->select();
        return $result;
    }
}