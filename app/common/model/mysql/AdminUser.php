<?php


namespace app\common\model\mysql;



class AdminUser extends BaseModel
{
    protected $table = "mall_admin_user";

    /**
     * 根据用户名获取后端数据表的数据
     * @param $username
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserByUsername($username) {
        if(empty($username)) {
            return false;
        }

        $where = [
            "username" => trim($username),
        ];
        $result = $this->where($where)->find();
        return $result;
    }
}