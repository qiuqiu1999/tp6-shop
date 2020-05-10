<?php


namespace app\admin\controller;

use app\BaseController;
use think\Validate;
use app\common\business\SpecsValueBis;
use think\facade\View;

class SpecsValue extends BaseController
{

    public  function save()
    {
        $specsId = input("param.specs_id", 0, "intval");
        $name = input("param.name", 0, "trim");
        $data = [
            'specs_id' => $specsId,
            'name' => $name
        ];
        try {
            $id = (new SpecsValueBis())->add($data);
        } catch (\Exception $e) {
            return show(config("status.error"), $e->getMessage(), null);
        }
        if ($id) {
            return show(config("status.success"), "添加成功", ['id' => $id]);
        }
        return show(config("status.error"), "添加失败", null);
    }


    public function getBySpecsId()
    {
        $specsId = input("param.specs_id", 0, "intval");

        try {
            $specsValue = (new SpecsValueBis())->getBySpecsId($specsId);
        } catch (\Exception $e) {
            return show(config("status.error"), $e->getMessage(), null);
        }
        return show(config("status.success"), "ok", $specsValue);

    }
}