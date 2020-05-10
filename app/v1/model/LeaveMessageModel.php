<?php


namespace app\v1\model;

use think\Model;
/**
 *
 */
class LeaveMessageModel extends Model
{
    protected $table = 'blog_user_leave_message';
    protected $pk = 'id';

    /**
     * 通过toUid获取留言
     * @param $toUid
     * @param int $num
     * @return array|bool|\think\Paginator
     * @throws \think\db\exception\DbException
     */
    public function getLeaveMessageByToId($toUid, $num = 5)
    {
        if(empty($toUid)) {
            return false;
        }
        $where = [
            'to_uid' => $toUid,
            'status' => config("status.mysql.table_normal")
        ];
        $result = $this->where($where)->paginate($num);
        if(!$result) {
            return \app\common\lib\Arr::paginateData($num);
        }
        $result = $result->toArray();
        return $result;
    }

}
