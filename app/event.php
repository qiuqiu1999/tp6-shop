<?php
// 事件定义文件

return [
    'bind' => [
    ],

    'listen' => [
        'AppInit' => [],
        'HttpRun' => [],
        'HttpEnd' => [],
        'LogLevel' => [],
        'LogWrite' => [],
        'swoole.task' => [
            \app\listener\Task::class,
        ],
        'test' => [
            \app\listener\WebsocketTest::class
        ],
    ],

    'subscribe' => [
    ],
];
