<?php


namespace app\v1\controller;


use app\BaseController;
use app\v1\business\LeaveMessageBis;

class Message extends BaseController
{
    public function leaveMessage()
    {
        $toUid = input('param.to_uid', 0, 'intval');
        $toUid = 1;
        $data = [
            'to_id' => $toUid
        ];
        try {
            $articleDetail = (new LeaveMessageBis())->leaveMessageList($toUid);
        }catch (\Exception $e) {
            return show(config("status.error"),$e->getMessage(), null);
        }
        if(empty($articleDetail)){
            return show(config("status.success"),"数据为空", []);
        }
        return show(config("status.success"),"ok", $articleDetail);
    }
}