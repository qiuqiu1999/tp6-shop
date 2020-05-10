<?php


namespace app\api\controller;


use app\common\business\CartBis;
use app\common\lib\Show;

class Cart extends AuthBase
{
    public function add()
    {
        if (!$this->request->isPost()) {
            return Show::error("非法请求");
        }
        $id = input("param.id", 0, "intval");
        $num = input("param.num", 0, "intval");
        // 数据校验
        $result = (new CartBis())->insertRedis($this->userId,$id, $num);
        if ($result === FALSE) {
            return Show::error("添加失败");
        }
        return Show::success();
    }

    public function lists()
    {
        if (!$this->request->isPost()) {
            return Show::error("非法请求");
        }
        $id = input("param.id", '', "trim");
        try {
            $result = (new CartBis())->getUserCart($this->userId, $id);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        return Show::success($result);
    }

    public function update()
    {
        if (!$this->request->isPost()) {
            return Show::error("非法请求");
        }
        $id = input("param.id", 0, "intval");
        $num = input("param.num", 0, "intval");
        // 数据校验
        try{
            $result = (new CartBis())->updateRedis($this->userId,$id, $num);
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        if ($result === FALSE) {
            return Show::error("更新失败");
        }
        return Show::success();
    }

    public function delete()
    {
        if (!$this->request->isPost()) {
            return Show::error("非法请求");
        }
        $id = input("param.id", 0, "intval");
        $result = (new CartBis())->deleteRedis($this->userId,$id);
        if ($result === FALSE) {
            return Show::error("删除商品失败");
        }
        return Show::success();
    }
}