<?php


namespace app\admin\controller;



use app\BaseController;
use app\common\lib\Show;
use think\facade\Log;

class Image extends BaseController
{
    public function upload()
    {
        $file = request()->file('file');
        try {
            $saveName = \think\facade\Filesystem::disk('public')->putFile('mall', $file);
        } catch (\Exception $e) {
            Log::write($e->getMessage(), 'error');
            return Show::error('上传异常');
        }
        if (!$saveName) {
            return Show::error( "上传图片失败");
        }
        $data = [
            'image' => '/uploads/' . $saveName,
        ];
        return Show::success($data);
    }

    public function layUpload()
    {
        $file = request()->file('file');
        $saveName = \think\facade\Filesystem::disk('public')->putFile('mall', $file);

        if (!$saveName) {
            return json(['code' => 1, 'data' => []], 200);
        }
        $result = [
            'code' => 0,
            'msg' => "上传成功",
            'data' => [
                'src' => '/uploads/' . $saveName,
            ]
        ];
        return json($result, 200);
    }
}