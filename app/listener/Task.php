<?php

declare (strict_types = 1);
namespace app\listener;

//use Task;

use Swoole\Server;

class Task
{
    /**
     * 监听task事件处理
     * event 时间内容
     * @return mixed
     */
    public function handle($event, Server $server)
    {
        $obj = new \app\common\lib\task\Task();
        $method = $event->data['method'];
        if (empty($method)) {
            echo "事件或参数错误".PHP_EOL;
            return;
        }
        $flag = $obj->$method($event->data['data'], $server);

        if (!$flag) {
            // 记录日志
            echo "异步任务方法" . $method . "失败".PHP_EOL;
            return;
        }
        echo "异步任务方法" . $method . "成功".PHP_EOL;
        return;
    }
}