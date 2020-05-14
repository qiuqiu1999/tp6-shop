<?php


namespace app\api\controller\mall;


use app\api\controller\ApiBase;
use app\common\business\GoodsBis;
use app\common\lib\Show;
use think\App;

class Lists extends ApiBase
{
    public function index()
    {
        $pageSize = input("param.page_size", 10, "intval");
        $categoryId = input("param.category_id", 78, "intval");

        $data = [
            'category_path_id' => $categoryId,
        ];
        $field = input("param.field", "listorder", "trim");
        $order = input("param.order", "2", "intval");
        $order = $order == 2 ? "asc" : "dasc";
        $order = [$field => $order];
        $goods = (new GoodsBis())->getNormalLists($data, $pageSize, $order);
        return Show::success($goods);
    }

    // 商品推荐(针对用户)
    public function recommend()
    {
        // 使用Redis存储商品偏好, 再随机取出类型进行查询
        return Show::success();
    }
}