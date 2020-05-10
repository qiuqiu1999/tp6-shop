<?php

use app\common\business\Sms;
use think\facade\Db;
use think\facade\Log;

require __DIR__ . '/../../vendor/autoload.php';

class Server
{
    const PORT = 9501;

    public function port()
    {
        $shell = "netstat -anp 2>/dev/null | grep " . self::PORT . ' | grep LISTEN | wc -l';

        $result = shell_exec($shell);
        if ($result != 1) {
            // 发送报警服务
            $tel = '13415560766';
            $code = '999999999999';
            try {
                $response = Sms::errorMsg($tel, $code);
            } catch (\Exception $e) {
//            // TODO: 记录日志
            }
            echo "error - " . date("Y-m-d H:i:s") . PHP_EOL;
		} else {
            echo "success - " . date("Y-m-d H:i:s") . PHP_EOL;
        }
    }
}

//swoole_timer_tick(2000, function ($timer_id) {
    (new Server())->port();
//});
