<?php


namespace app\common\lib;

/**
 * 响应类库
 * Class Show
 * @package app\common\lib
 */
class Show
{
    /**
     * 请求成功
     * @param array $data
     * @param string $message
     * @return \think\response\Json
     */
    public static function success($data = [], $message = "success")
    {
        $result = [
            "status" => config("status.success"),
            "message" => $message,
            "result" => $data
        ];
        return json($result);
    }

    /**
     * 请求失败
     * @param array $data
     * @param string $message
     * @param int $status
     * @return \think\response\Json
     */
    public static function error($message = "error", $status = -1, $data = [])
    {
        $result = [
            "status" => $status,
            "message" => $message,
            "result" => $data
        ];
        return json($result);
    }
}