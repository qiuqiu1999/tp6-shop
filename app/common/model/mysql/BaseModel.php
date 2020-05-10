<?php


namespace app\common\model\mysql;


use think\Model;

class BaseModel extends Model
{
    /**
     * 通过ID获取数据
     * @param $id
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalById($id)
    {
        if (empty($id)) {
            return false;
        }
        $where = [
            'id' => $id,
            'status' => ['neq', config("status.mysql.table_normal")]
        ];
        $result = $this->where($where)->find();
        return $result;
    }

    /**
     * 通过ID更新数据
     * @param $id
     * @param $data
     * @return Category|bool
     */
    public function updateById($id, $data)
    {
        if (empty($id) || !is_array($data)) {
            return false;
        }
        $where = [
            'id' => $id,
            'status' => ['NEQ', config("status.mysql.table_normal")]
        ];
        $data['update_time'] = time();
        return $this->where($where)->save($data);
    }


    public function getNormalInIds($ids, $filed = '*')
    {
        $result = $this->field($filed)->where("status", "=", config("status.mysql.table_normal"))
            ->whereIn('id', $ids)
            ->select();
        return $result;
    }

    public function getByCondition($condition = [], $order = ['id' => 'desc'])
    {
        if (!$condition || !is_array($condition)) {
            return false;
        }
        $result = $this->where($condition)->order($order)->select();
        return $result;
    }
}