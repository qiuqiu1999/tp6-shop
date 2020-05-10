<?php

namespace app\v1\business;

use app\v1\model\RoleModel;
use app\v1\model\RuleModel;


class AdminService
{
    public function getRole()
    {
        $role = cache('role');
        if(empty($role)){
            $role = RoleModel::select()->toArray();
            cache('role',$role);
        }
        return $role;
    }

    public function getRule()
    {
        $rule = cache('rule');
        if(empty($role)){
            $rule = RuleModel::select()->toArray();
            cache('rule',$rule);
        }
        return $rule;
    }

}