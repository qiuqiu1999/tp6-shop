<?php


namespace app\v1\business;

use app\v1\model\LeaveMessageModel;

class LeaveMessageBusiness
{
    public $mode = null;

    public function __construct()
    {
        $this->mode = new LeaveMessageModel();
    }

    public function leaveMessageList($toUid)
    {
        try {
            $result = $this->mode->getLeaveMessageByToId($toUid, 2);
        } catch (\Exception $e) {
            throw new \think\Exception('数据库内部异常1');
        }
        return $result;
    }

//    public function shuoshuoMessage($ssId)
//    {a
//        try {
//            $result = $this->mode->shuoshuoMessage($ssId, 2);
//        } catch (\Exception $e) {
//            throw new \think\Exception('数据库内部异常1');
//        }
//        return $result;
//    }
}