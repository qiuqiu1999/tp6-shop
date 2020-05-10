<?php


namespace app\admin\controller;

use app\BaseController;
use think\Validate;
use app\common\business\SpecsBis;
use think\facade\View;

class Specs extends BaseController
{
    public function dialog()
    {
        try {
            $specss = (new SpecsBis())->getNormalSpecss();
        } catch (\Exception $e) {
            return show(config("status.error"), $e->getMessage(), null);
        }
        return View::fetch("", [
            'specss' => json_encode($specss)
        ]);
    }
}