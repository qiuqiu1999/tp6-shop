<?php


namespace app\api\controller;

use app\BaseController;
use app\common\lib\Show;
use think\console\Input;
use think\Validate;
use app\common\business\CategoryBis;
use think\facade\View;

class Category extends BaseController
{
    public function index()
    {
        try {
            $categorys = cache("mall_category");
            if(!$categorys) {
                $categorys = (new CategoryBis())->getNormalCategorys();
                $categorys = \app\common\lib\Arr::getTree($categorys);
                $categorys = \app\common\lib\Arr::treeSlice($categorys);
                cache("mall_category", $categorys, 2000);
            }
        } catch (\Exception $e) {
            return Show::error($e->getMessage());
        }
        if ($categorys) {
            return Show::success($categorys);
        }
        return Show::error("数据为空");
    }

    public function search()
    {
        $id = input("param.cid", 0, "intval");


        $result = (new CategoryBis())->search($id);
        return Show::success($result);
    }

    public function sub()
    {
        $id = input("param.id", 0, "intval");


        $result = (new CategoryBis())->sub($id);
        return Show::success($result);
    }
}