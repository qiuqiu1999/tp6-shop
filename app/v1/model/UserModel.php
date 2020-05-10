<?php

namespace app\v1\model;

use think\Model;

/**
 *
 */
class UserModel extends Model
{
    protected $table = 'mall_user';
    protected $pk = 'id';

    protected static function init()
    {
        //TODO:初始化内容
    }

//    public function getRoleIdAttr($value)
//    {
//        $role = [];
//        $roleArr = cache('role');
//        foreach ($roleArr as $key) {
//            $role[$key['id']] = $key;
//        }
//        return $role[$value]['name'];
//    }

    /**
     * 通过ID获取用户信息
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
