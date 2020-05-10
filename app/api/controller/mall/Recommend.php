<?php


namespace app\api\controller\mall;


use app\api\controller\ApiBase;
use app\common\lib\Show;

class Recommend extends ApiBase
{
    public function index()
    {
        return Show::success();
    }

    public function searchTop()
    {
        $result = [
            "name" => "我是一级分类",
            "focus_id" => [1,11],
            "list" => [

            ]
        ];

        return Show::success($result);
    }
}