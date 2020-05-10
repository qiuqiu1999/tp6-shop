<?php


namespace app\api\controller;


use app\common\business\UserBis;
use app\common\lib\Show;

class User extends AuthBase
{
    // 用户信息
    public function index()
    {
        $userBusiness = new UserBis();
        $user = $userBusiness->getNormalUserById($this->userId);

        $resUser = [
            'id' => $this->userId,
            'username' => $user['username'],
            'sex' => $user['sex']
        ];
        return Show::success($resUser);
    }

    // 保存信息
    public function save()
    {
        $userId = $this->userId;
        if (input("param.id", '', 'intval') != $userId) {
            return show("status.error", "错误", null);
        }
        $sex = input("param.sex", '', 'intval');
        $data = [
            'update_time' => time(),
            'operate_user' => $userId,
            'sex' => $sex
        ];
        $validate = new \app\api\validate\CheckData();
        if (!$validate->scene('update_user_info')->check($data)) {
            return show(config("status.error"), $validate->getError());
        }

        $userBusiness = new UserBis();
        $res = $userBusiness->updateNormalUserInfo($userId, $data);

        if ($res) {
            return Show::success();
        }
        return Show::error("修改失败");
    }

    // 保存信息页面
//    public function edit()
//    {
//        $userId = $this->userId;
//        if(input("param.id", '', 'intval') != $userId) {
//            return show("status.error", "错误", null);
//        }
//        dump('user/edit');
//    }

//    public function update()
//    {
//        $userId = $this->userId;
//        if(input("param.id", '', 'intval') != $userId) {
//            return show("status.error", "错误", null);
//        }
//    }

    // 删除
//    public function delete()
//    {
//        $userId = $this->userId;
//        if(input("param.id", '', 'intval') != $userId) {
//            return show("status.error", "错误", null);
//        }
//    }
    public function address()
    {
        $result = [
            [
                'id',
                'consignee_info' => "广东 广州 荔湾区 芳村大道 接龙里x巷x号",
            ]
        ];
        return Show::success($result);
    }
}