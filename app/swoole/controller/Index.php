<?php


namespace app\swoole\controller;


use app\common\lib\sms\AliSms;

class Index
{
    public function fileUpload()
    {
        $file = request()->file('file');
        $saveName = \think\facade\Filesystem::disk('public')->putFile('live', $file);

        if (!$saveName) {
            return show(config("status.error"), "上传图片失败", null);
        }
        $data = [
            'image' => '/public/storage/' . $saveName,
        ];
        return show(config("status.success"), "上传图片成功", $data);
    }
}