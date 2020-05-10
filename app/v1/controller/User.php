<?php


namespace app\v1\controller;

use app\v1\business\JwtAuth;



 class User extends CommonController
{
    private $action = ['login'];


    public function userInfo()
    {
        $data = $this->userInfo;
        return show(config("status.success"),"ok",$data);
    }
}