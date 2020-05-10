<?php


namespace app\swoole;


use Swoole\Coroutine\System;
use think\App;

class WebsocketServer
{
    const HOST = "0.0.0.0";
    const PORT = 9505;
    public $server;

    public function __construct()
    {
        $this->server = new \Swoole\WebSocket\Server(self::HOST, self::PORT);


        $this->server->on('WorkerStart', [$this, "onWorkerStart"]);
        $this->server->on('open', [$this, "onOpen"]);
        $this->server->on('message', [$this, "onMessage"]);
        $this->server->on('close', [$this, "onClose"]);
        $this->server->on('request', [$this, "onRequest"]);
        $this->server->start();
    }

    public function onWorkerStart($server, $worker_id)
    {
        require __DIR__ . '/../../vendor/autoload.php';

    }

    public function onRequest($request, $response)
    {
//        foreach ($this->server->connections as $fd) {
//            // 需要先判断是否是正确的websocket连接，否则有可能会push失败
//            if ($this->server->isEstablished($fd)) {
//                $this->server->push($fd, $request->get['message']);
//            }
//        }
        $_SERVER = [];
        if (isset($request->server)) {
            foreach ($request->server as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        if (isset($request->header)) {
            foreach ($request->header as $k => $v) {
                $_SERVER[strtoupper($k)] = $v;
            }
        }
        $_GET = [];
        if (isset($request->get)) {
            foreach ($request->get as $k => $v) {
                $_GET[strtoupper($k)] = $v;
            }
        }
        $_POST = [];
        if (isset($request->post)) {
            foreach ($request->post as $k => $v) {
                $_POST[strtoupper($k)] = $v;
            }
        }

        ob_start();
//        print_r($_SERVER);
        try {
            $http = (new App())->http;

            $response2 = $http->run();

            $response2->send();
//            $http->end($response2);
        } catch (\Exception $e) {

        }
        print(request()->action());
        $http->end($response2);

        $res = ob_get_contents();
        if ($res) {
            ob_end_clean();
        }
        $response->end($res);
    }

    public function onOpen($server, $request)
    {
//        echo "用户" . $request->fd . "连接" . PHP_EOL;

    }

    /**
     * $frame->fd 连接id
     * $frame->data 接收数据
     * @param $server
     * @param $frame
     */
    public function onMessage($server, $frame)
    {
        var_dump($frame);
    }

    public function onClose($ser, $fd)
    {
        var_dump($fd);
    }
}

new WebsocketServer();