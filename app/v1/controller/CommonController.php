<?php


namespace app\v1\controller;


use app\BaseController;

use app\v1\business\JwtAuth;
use app\v1\business\UserService;

class CommonController extends BaseController
{
    private $host = ['http://localhost','https://www.qiujincheng.top'];
    private $ignore = [
        'Login/login',
        'Article/articleDetail',
        'Article/articleList',
    ];

    public $userInfo;

    public function initialize()
    {
        header("Access-Control-Allow-Origin: *");
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Authorization");
            header('Access-Control-Allow-Methods: GET, POST, PUT,DELETE,OPTIONS,PATCH');
            file_put_contents('option.txt', json_encode($_REQUEST));
            exit;
        }

        $now = request()->controller() . '/' . request()->action();

//        if (!function_exists('getallheaders')) {
//            function getallheaders()
//            {
//                foreach ($_SERVER as $name => $value) {
//                    if (substr($name, 0, 5) == 'HTTP_') {
//                        $headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
//                    }
//                }
//                return $headers;
//            }
//        }

        if (!in_array($now, $this->ignore)) {
//            try {
//                $header = getallheaders();
//                //头部信息
//
//                $token = $header['Authorization'];
//                //解析token
//                $instance = JwtAuth::getInstance();
//                $decodeToken = $instance->setToken($token)->decode();
//
//                if ($header['Origin'] != $decodeToken->aud) {
//                    $data = ['code' => -1, 'msg' => '非法操作'];
//                    json($data)->send();
//                    exit;
//                }
//            } catch (\Exception $e) {
//                $data = ['code' => -1, 'msg' => '登录过期,请重新登录'];
//                json($data)->send();
//                exit;
//            }

//            $userService = new UserService($decodeToken->uid);
            $userService = new UserService(1);
            $this->userInfo = $userService->userInfo;
        } else {
            //无需验证token
//            try {
//                $header = getallheaders();
//                //防止盗用
//                if (!in_array($header['Origin'] , $this->host)) {
//                    $data = ['code' => -2, 'msg' => '非法操作'];
//                    json($data)->send();
//                    exit();
//                }
//            } catch (\Exception $e) {
//                $data = ['code' => -3, 'msg' => '非法操作'];
//                json($data)->send();
//                exit;
//            }
        }
    }
}