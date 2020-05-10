<?php


namespace app\swoole\controller;


use app\BaseController;
use Swoole\Server;
use Swoole\WebSocket\Server as WebsocketServer;

class Live
{
//    /** @var WebsocketServer */
    public $server = null;

    public function __construct(Server $server)
    {
        $this->server = $server;
    }

    public function index()
    {
        $content = request()->param("content");
        $type = request()->param("type", '', 'intval');
        $team_id = request()->param("team_id", '', 'intval');
        $image = request()->param("image", '', "trim");

        $teams = [
            1 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
            4 => [
                'name' => '马刺',
                'logo' => '/live/imgs/team1.png',
            ],
        ];

        $data = [
            'type' => $type,
            'title' => !empty($teams[$team_id]['name']) ? $teams[$team_id]['name'] : '直播源',
            'logo' => !empty($teams[$team_id]['logo']) ? $teams[$team_id]['logo']: '',
            'content' => !empty($content) ? $content : '',
            'image' => !empty($image) ? $image: '',
        ];

        $taskData = [
            'method' => 'livePush',
            'data' => $data
        ];
        $this->server->task($taskData);
        return show(config("status.success"), "发送成功", null);
    }


}