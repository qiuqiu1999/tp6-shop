<?php

namespace app\v1\business;

use app\v1\model\UserModel;

class UserService
{
    public $sql;
    public $userInfo;

    public function __construct($user_id = '',$tel = '')
    {
        $userInfo = [];
        if(!empty($user_id)){
            $userInfo = UserModel::where('id', $user_id)->find();
            $this->sql = UserModel::getLastsql();
        }elseif($tel){
            $userInfo = UserModel::where('tel', $tel)->find();
            $this->sql = UserModel::getLastsql();
        }
        $this->userInfo = $userInfo;
    }

    public function getUserList()
    {
        $userModel = UserModel();
        $result = $userModel->select();
        return $result;
    }

}