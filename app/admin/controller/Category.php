<?php


namespace app\admin\controller;

use app\BaseController;
use think\Validate;
use app\common\business\CategoryBis;
use think\facade\View;

class Category extends BaseController
{
//getNormalByPid
    /*
     * 栏目列表页
     */
    public function index()
    {
        $pid = input("param.pid", 0, "intval");
//        dump($pid);die;
        $data = [
            "pid" => $pid
        ];
        try {
            $categorys = (new CategoryBis())->getLists($data, 5);
        } catch (\Exception $e) {
            $categorys = \app\common\lib\Arr::paginateData(5);
        }
        return View::fetch("", [
            "categorys" => $categorys,
            "pid" => $pid
        ]);
    }

    /*
     * 添加栏目页
     */
    public function add()
    {
        try {
            $categorys = (new CategoryBis())->getNormalCategorys();
        } catch (\Exception $e) {
            $categorys = [];
        }
        $categorys = json_encode($categorys);
        return View::fetch("", [
            "categorys" => $categorys
        ]);
    }

    public function save()
    {
        $name = input("param.name", "", "trim");
        $pid = input("param.pid", "", "intval");

        $data = [
            'name' => $name,
            'pid' => $pid
        ];
        $validate = new \app\admin\validate\CheckData();
        // 校验参数
        if (!$validate->scene('add_category')->check($data)) {
            return show(config("status.error"), $validate->getError());
        }
        try {
            $result = (new CategoryBis())->add($data);
        } catch (\Exception $e) {
            return show(config("status.error"), $e->getMessage(), null);

        }

        if ($result) {
            return show(config("status.success"), "栏目添加成功", null);
        }
        return show(config("status.error"), "栏目添加失败", null);
    }

    public function del()
    {
        $categoryId = input('param.id', '', 'intval');
//        $uid = $this->userInfo['id'];
        $uid = 1;

        $data = [
            'operate_user' => $uid,
            'id' => $categoryId,
        ];
        try {
            $result = (new CategoryBis)->del($data);
        } catch (\Exception $e) {
            return show(config("status.error"), $e->getMessage(), null);
        }
        if ($result) {
            return show(config("status.success"), "删除成功", null);
        }
        return show(config("status.error"), "删除失败", null);
    }

    public function listorder()
    {
        $id = input('param.id', '', 'intval');
        $listorder = input('param.listorder', '', 'intval');
        $data = [
            'id' => $id,
            'listorder' => $listorder
        ];
        try {
            $result = (new CategoryBis)->listorder($id, $listorder);
        } catch (\Exception $e) {
            return show(config("status.error"), $e->getMessage(), null);
        }
        if ($result) {
            return show(config("status.success"), "排序修改成功", null);
        }
        return show(config("status.error"), "排序修改失败", null);
    }

    public function status()
    {
        $id = input('param.id', '', 'intval');
        $status = input('param.status', '', 'intval');
        $data = [
            'id' => $id,
            'status' => $status
        ];
        try {
            $result = (new CategoryBis)->status($id, $status);
        } catch (\Exception $e) {
            return show(config("status.error"), $e->getMessage(), null);
        }
        if ($result) {
            return show(config("status.success"), "状态修改成功", null);
        }
        return show(config("status.error"), "状态修改失败", null);
    }

    public function dialog()
    {
        try {
            $categorys = (new CategoryBis())->getNormalByPid();
        } catch (\Exception $e) {
            $categorys = [];
        }
        return View::fetch("", [
            'categorys' => json_encode($categorys)
        ]);
    }
    public function getByPid()
    {
        $pid = input('param.pid', 0, 'intval');
        $categorys = (new CategoryBis())->getNormalByPid($pid);
        return show(config("status.success"), "ok", $categorys);

    }
}