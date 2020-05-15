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
        $categoryId = input("param.category_id", 0, "intval");

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

    // 商品推荐(针对用户) 未完成
    public function recommend()
    {
        // 简单推荐算法 => 通过Redis记录用户近期偏好 + 根据商品PV + 近期成交量

        // 以下临时代码
        $pageSize = input("param.page_size", 10, "intval");

        $data = [
            'category_path_id' => 120,
        ];
        $field = input("param.field", "listorder", "trim");
        $order = input("param.order", "2", "intval");
        $order = $order == 2 ? "asc" : "dasc";
        $order = [$field => $order];
        $goods = (new GoodsBis())->getNormalLists($data, $pageSize, $order);
        return Show::success($goods);
    }
}