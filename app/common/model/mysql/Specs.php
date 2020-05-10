<?php


namespace app\common\model\mysql;



class Specs extends BaseModel
{
    protected $table = "mall_specs";

    /**
     * @param string $field
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalSpecss($field = '*')
    {
        $where = [
            'status' => config("status.mysql.table_normal"),
        ];
        $result = $this->field($field)->where($where)->select();
        return $result;
    }

    /**
     * 通过规格名获取规格信息
     * @param $name
     * @return array|bool|\think\Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalByName($name)
    {
        if (empty($name)) {
            return false;
        }
        $where = [
            'name' => trim($name),
        ];
        $result = $this->where($where)->find();
        return $result;
    }

}