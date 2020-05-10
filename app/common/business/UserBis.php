<?php


namespace app\common\business;

use app\common\lib\Key;
use app\common\lib\Str;
use app\common\model\mysql\User as UserModel;
use think\facade\Cache;


class UserBis extends BaseBis
{
    public $model = null;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function login($data)
    {
//        cache(config("redis.code_pre").$data['phone_number'],1234,300*10);
        $redisCode = Cache::get(Key::phoneCode($data['phone_number']));
        if(empty($redisCode) || $redisCode != $data['code']) {
            throw new \think\Exception('不存在该验证码');
        }
        Cache::del(Key::phoneCode($data['phone_number']));
        $user = $this->getUserByPhoneNumber($data['phone_number']);

        if(empty($user)) {
            $userData = [
                'username' => 'xd_as'.$data['phone_number'],
                'phone_number' => $data['phone_number'],
                'type' => $data['type'],
                'status' => config("status.mysql.table_normal"),
            ];
            try {
                $this->model->save($userData);
                $userId = $this->model->id;
                $username = $this->model->usernmae;
            }catch (\Exception $e) {
                // TODO 记录日志
                throw new \think\Exception('数据库内部异常');
            }
        }else {
            $userData = [
                'update_time' => time(),
            ];
            try {
                $this->model->save($data);

            }catch (\Exception $e) {
                // TODO 记录日志
                throw new \think\Exception('数据库内部异常');
            }
            $userId = $user['id'];
            $username = $user['username'];
        }
        // 记录登录信息 登录成功 记录token redis
        $token = Str::getLoginToken($data['phone_number']);
        $reidsData = [
            'id' => $userId,
            'username' => $username
        ];
//        Time::userLoginExpirationTime
        $res = cache(Key::token($token), $reidsData, Key::tokenExpire());
        return $res ? ["token" => $token, "username" => $username] : false;
    }

    public function getUserByPhoneNumber($phoneNumber)
    {
        $user = $this->model->getUserByPhoneNumber($phoneNumber);
        if (empty($user) || $user->status != config("status.mysql.table_normal")) {
            return false;
        }
        return $user->toArray();
    }


    public function getNormalUserById($id)
    {
        $user = $this->model->getUserById($id);
        if(empty($user) || $user->status != config("status.mysql.table_normal")) {
            return [];
        }
        return $user->toArray();
    }

    public function updateNormalUserInfo($id, $data)
    {
        $user = $this->model->getUserById($id);
        if(empty($user) || $user->status != config("status.mysql.table_normal")) {
            return false;
        }

        $user = $this->model->UpdateById($id, $data);
        if($user) {
            return true;
        }
        return false;
    }
}