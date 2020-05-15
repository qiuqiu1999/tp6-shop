<?php


namespace app\admin\controller;


use app\BaseController;
use think\facade\View;
use app\common\business\GoodsBis;

class Goods extends BaseController
{
    public function index()
    {
        return View::fetch();
    }

    public function add()
    {
        return View::fetch();
    }

    public function save()
    {
        $data = input("param.");
        // 数据校验

        //
        $data['category_path_id'] = $data['category_id'];
        if (strpos($data['category_path_id'], 'undefined')) {
            $data['category_path_id'] = trim(str_replace('undefined', '', $data['category_path_id']), ',');
        }
        $result = explode(",", $data['category_path_id']);
        $data['category_id'] = end($result);

        $res = (new GoodsBis)->instData($data);

        if($res) {
            return show(config("status.success"), "添加成功");
        }
        return show(config("status.error"), "添加失败");
    }

    public function goods()
    {
        $data = input("param.");
        try {
            $goodsList = (new GoodsBis())->getLists($data);
        } catch (\Exception $e) {
            $goodsList = \app\common\lib\Arr::paginateData(5);
        }
        return View::fetch("",[
            'goods' => $goodsList
        ]);
    }

    public function update()
    {
        $id = input("param.id", 0, 'intval');
        // 校验数据
        $data = input("param.");

        try {
            $res = (new GoodsBis())->updateById($id, $data);
        } catch (\Exception $e) {
            return show($e->getCode(), $e->getMessage());
        }
        if($res) {
            return show(config("status.success"), "修改成功");
        }
        return show(config("status.error"), "修改失败");
    }
}