<?php


namespace app\admin\controller;


class Error
{
    public function __call($name, $arguments)
    {
        return show(config("status.controller_not_found"), "控制器{$name}不存在",null,404);
    }
}