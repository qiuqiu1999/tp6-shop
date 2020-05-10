<?php


namespace app\listener;

use Swoole\Server;
use Swoole\WebSocket\Frame;
use think\Config;
use think\Request;
use think\swoole\websocket\socketio\Handler;
use think\swoole\websocket\socketio\Packet;

class Websocket extends Handler
{
    public function __construct(Server $server, Config $config)
    {
        parent::__construct($server, $config);
    }


    public function onOpen($fd, Request $request)
    {
        dump("connect {$fd} success");
//        dump($fd);
        \app\common\lib\Predis::getInstance()->sAdd("live_game_key", $fd);
    }


    public function onMessage(Frame $frame)
    {
        $packet = $frame->data;
        if (Packet::getPayload($packet)) {
            return false;
        }

        // TODO 业务逻辑
        dump("$packet");

        $this->checkHeartbeat($frame->fd, $packet);
        return true;

    }


    public function onClose($fd, $reactorId)
    {
//        \app\common\lib\Predis::getInstance()->sRem("live_game_key", $fd);
        dump("close {$fd} success");
    }
}