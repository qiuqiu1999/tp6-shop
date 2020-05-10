<?php


namespace app\common\model\mysql;



class User extends BaseModel
{
    protected $table = "mall_user";

    /**
     * 自动写入生成时间
     * @var bool
     */
    protected $autoWriteTimestamp = true;

    /**
     * 根据用户名获取后端数据表的数据
     * @param $username
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserByPhoneNumber($phoneNumber) {
        if(empty($phoneNumber)) {
            return false;
        }

        $where = [
            'phone_number' => trim($phoneNumber),
        ];
        $result = $this->where($where)->find();
        return $result;
    }

    /**
     * 通过ID获取用户数据
     * @param $id
     * @return array|bool|Model|null
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getUserById($id)
    {
        $id = intval($id);
        if(empty($id)) {
            return false;
        }
        return $this->find($id);
    }
}