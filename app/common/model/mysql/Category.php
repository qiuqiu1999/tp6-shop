<?php


namespace app\common\model\mysql;



class Category extends BaseModel
{
    protected $table = "mall_category";

    /**
     * 通过栏目名获取栏目信息
     * @param $name
     * @return array|bool|Model|null
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

    /**
     * 获取所有正常的栏目数据
     * @param string $flied
     * @return \think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalCategorys($flied = '*')
    {
        $where = [
            "status" => config("status.mysql.table_normal"),
        ];
        $order = [
            'listorder' => "desc",
            'id' => 'desc'
        ];
        $result = $this->field($flied)->where($where)->order($order)->select();
        return $result;
    }

    /**
     * 获取栏目列表(分页 )
     * @param $where
     * @param $offset
     * @return \think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getLists($where, $num = 10)
    {
        $order = [
            'listorder' => "desc",
            'id' => 'desc'
        ];
        $result = $this->where("status", "<>", config("status.mysql.table_delete"))
            ->where($where)
            ->order($order)
            ->paginate($num);
        return $result;
    }

    /**
     * 获取子栏目数
     * @param $condition
     * @return mixed
     */
    public function getChildInPids($condition)
    {
        $where[] = ['pid', 'IN', $condition['pids']];
        $where[] = ['status', '<>', config("status.mysql.table_delete")];
        $result = $this->where($where)
            ->field(['pid', "count(*) as count"])
            ->group("pid")
            ->select();
        return $result;
    }

    public function getNormalByPid($pid = 0, $field)
    {
        $where = [
            'pid' => $pid,
            'status' => ['neq', config("status.mysql.table_normal")]
        ];
        $order = [
            'listorder' => "desc",
            'id' => 'desc'
        ];

        $result = $this->where($where)->field($field)->order($order)->select();
        return $result;
    }

    public function getNormalInIds($ids = [0], $field = '*')
    {
        $where = [
            'status' => ['neq', config("status.mysql.table_normal")]
        ];
        $order = [
            'listorder' => "desc",
            'id' => 'desc'
        ];

        $result = $this->where($where)->whereIn('id',$ids)->field($field)->order($order)->select();
        return $result;
    }

    public function getNormalInPids($pids = [0], $field = '*')
    {
        $where = [
            'status' => ['neq', config("status.mysql.table_normal")]
        ];
        $order = [
            'listorder' => "desc",
            'id' => 'desc'
        ];

        $result = $this->where($where)->whereIn('pid',$pids)->field($field)->order($order)->select();
        return $result;
    }
}