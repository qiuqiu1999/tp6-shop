<?php


namespace app\wechat\controller;


use app\common\lib\Curl;


class Menu
{

    public function menu()
    {
        $url = "https://api.weixin.qq.com/cgi-bin/menu/create";
//        $btnC = 11;
        // 一级菜单
        $btnChild1 = [
            'name' => '技术',
            'sub_button' => [
                [
                    "type" => "view",
                    "name" => "MYSQL",
                    "url" => "http://www.soso.com/"
                ],
                [
                    "type" => "view",
                    "name" => "PHP",
                    "url" => "http://www.soso.com/"
                ],
                [
                    "type" => "view",
                    "name" => "HTML",
                    "url" => "http://www.soso.com/"
                ]
            ]
        ];
        // 一级菜单
        $btnChild2 = [
            'name' => '商城',
            'sub_button' => [
                [
                    "type" => "view",
                    "name" => "前台",
                    "url" => "http://www.soso.com/"
                ],
                [
                    "type" => "view",
                    "name" => "后台",
                    "url" => "http://www.soso.com/"
                ],
                [
                    "type" => "view",
                    "name" => "其他",
                    "url" => "http://www.soso.com/"
                ]
            ]
        ];
        // 一级菜单
        $btnChild3 = [
            'name' => '福利',
            'sub_button' => [
                [
                    "type" => "scancode_waitmsg",
                    "name" => "21",
                    "key" => "rselfmenu_0_0"
                ],
                [
                    "type" => "scancode_waitmsg",
                    "name" => "22",
                    "key" => "rselfmenu_0_0"
                ],
                [
                    "type" => "scancode_waitmsg",
                    "name" => "23",
                    "key" => "rselfmenu_0_0"
                ]
            ]
        ];

        $menuButton = [
//            'button' => [
                $btnChild1,
                $btnChild2,
//                $btnChild3
//            ]
        ];
        $wechatService  = new WeChatService();
        $accessToken = $wechatService->getAccessToken();

        $wechatAuth = new WechatAuth($wechatService->AppID, $wechatService->AppSecret);
        $wechatAuth->setAccessToken($accessToken);
        $result = $wechatAuth->menuCreate($menuButton);
//        dd($accessToken);
//        $menu = json_encode($menuButton);
//        dd($accessToken);
//        dump(json_encode($menuButton));die;
//        $data = [
//            'access_token' => $accessToken,
//            json_encode($menuButton)
//        ];
//        dump($data);die;
//        $result = Curl::curl_request($url, $data, 'post');
        dd($result);
    }
}