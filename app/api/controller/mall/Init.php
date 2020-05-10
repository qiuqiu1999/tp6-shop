<?php


namespace app\api\controller\mall;


use app\api\controller\AuthBase;
use app\common\business\CartBis;
use app\common\lib\Show;

class Init extends AuthBase
{
    public function index()
    {
        if (!$this->request->isPost()) {
            return Show::error("非法请求");
        }
        $count = (new CartBis)->getUserCartCount($this->userId);
        $result = [
            'cart_num' => $count
        ];
        return Show::success($result);
    }
}