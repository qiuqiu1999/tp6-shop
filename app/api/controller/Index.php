<?php


namespace app\api\controller;


use app\common\business\GoodsBis;
use app\common\lib\Show;

class Index extends ApiBase
{
    public function getRotationChart()
    {
        try {
            $result = (new GoodsBis())->getRotationChart();
        } catch (\Exception $e) {
            // 记录日志
            $result = [];
        }
        return Show::success($result);
    }

    public function categoryGoodsRecommend()
    {
        $categoryIds = [
            71,
            51
        ];
        $result = (new GoodsBis())->categoryGoodsRecommend($categoryIds);
        return Show::success($result);

    }
}