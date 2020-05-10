<?php


namespace app\admin\business;


use app\common\model\mysql\AdminUser as AdminUserModel;

class AdminBusiness
{
    public $adminUserObj = null;

    public function __construct()
    {
        $this->adminUserObj = new AdminUserModel();
    }

    public function login($data)
    {
        $adminUser = $this->getAdminUserByUsername($data['username']);
        if(empty($adminUser)) {
            throw new \think\Exception('不存在该用户');
        }
        if ($adminUser['password'] != md5($data['password'])) {
            throw new \think\Exception('密码错误');
        }
        $updateData = [
            "update_time" => time(),
            "last_login_time" => time(),
            "last_login_ip" => request()->ip()
        ];
        $res = $this->adminUserObj->updateById($adminUser['id'], $updateData);

        if (empty($res)) {
            throw new \think\Exception('登录失败');
        }
        session(config("admin.session_admin"), $adminUser);
        return true;
    }

    /**
     * 通过用户名获取用户数据
     * @param $username
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getAdminUserByUsername($username)
    {
        $adminUser = $this->adminUserObj->getAdminUserByUsername($username);
        if (empty($adminUser) || $adminUser->status != config("status.mysql.table_normal")) {
            return false;
        }
        return $adminUser->toArray();
    }
}