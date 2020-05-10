<?php


namespace app\v1\business;

use app\common\lib\Str;
use app\v1\model\UserModel;


class UserBusiness
{
    public $userObj = null;

    public function __construct()
    {
        $this->userObj = new UserModel();
    }

    public function login($data)
    {
//        cache(config("redis.code_pre").$data['phone_number'],1234,300*10);
        $redisCode = cache(config("redis.code_pre").$data['phone_number']);
        if(empty($redisCode) || $redisCode != $data['code']) {
            throw new \think\Exception('不存在该验证码');
        }

        $user = $this->getUserByPhoneNumber($data['phone_number']);
        if(empty($user)) {
            $userData = [
                'username' => 'xd_as'.$data['phone_number'],
                'phone_number' => $data['phone_number'],
                'type' => $data['type'],
                'status' => config("status.mysql.table_normal"),
            ];
            try {
                $this->userObj->save($userData);
                $userId = $this->userObj->id;
                $username = $this->userObj->usernmae;
            }catch (\Exception $e) {
                // TODO 记录日志
                throw new \think\Exception('数据库内部异常');
            }
        }else {
//            $userData = [
//            ];
//            try {
//                $this->userObj->save($data);
//                $userId = $this->userObj->id;
//            }catch (\Exception $e) {
//                // TODO 记录日志
//                throw new \think\Exception('数据库内部异常');
//            }
            $userId = $user->id;
            $username = $user->username;
        }
        // 记录登录信息 登录成功 记录token redis
        $token = Str::getLoginToken($data['phone_number']);
        $reidsData = [
            'id' => $userId,
            'username' => $username
        ];
//        Time::userLoginExpirationTime
        $res = cache(config("redis.token_pre").$token, $reidsData, 500);
        return $res ? ["token" => $token, "username" => $username] : false;
    }

    public function getUserByPhoneNumber($phoneNumber)
    {
        $user = $this->userObj->getUserByPhoneNumber($phoneNumber);
        if (empty($user) || $user->status != config("status.mysql.table_normal")) {
            return false;
        }
//        return $user->toArray();
        return $user;
    }

    /**
     * 返回用户正常数据
     * @param $id
     * @return array
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function getNormalUserById($id)
    {
        $user = $this->userObj->getUserById($id);
        if(empty($user) || $user->status != config("status.mysql.table_normal")) {
            return [];
        }
        return $user->toArray();
    }

    /**
     * 根据ID修改用户信息
     * @param $id
     * @param $data
     * @return bool
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\DbException
     * @throws \think\db\exception\ModelNotFoundException
     */
    public function updateNormalUserInfo($id, $data)
    {
        $user = $this->userObj->getUserById($id);
        if(empty($user) || $user->status != config("status.mysql.table_normal")) {
            return [];
        }

        $user = $this->userObj->UpdateById($id, $data);
        if($user) {
            return true;
        }
        return false;
    }
}