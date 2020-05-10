<?php

namespace app\common\lib;

use Firebase\JWT\JWT;

/**
 * Jwt类库
 * Class JwtAuth
 * @package app\common\lib
 */
class JwtAuth
{
    private static $instance;
    private $key = 'qiujincheng';
//    private $aud = 'https://www.qiujincheng.top';
    private $aud = 'http://localhost';
//    private $iss = "https://api.qiujincheng.top";
    private $iss = "http://tp6.com";
    private $token;
    public $uid;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function encode()
    {
        $time = time();
        $key = $this->key;
        $payload = array(
            "iss" => $this->iss,
            "aud" => $this->aud,
            "iat" => $time,
            "exp" => $time+3600,
            "nbf" => $time,
            "uid" => $this->uid
        );

        $this->token = JWT::encode($payload, $key);
        return $this;
    }


    public function setUid($uid)
    {
        $this->uid = $uid;
        return $this;
    }

    /**
     * 设置token
     * @param $token
     * @return $this
     */
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }


    public function getToken()
    {
        return $this->token;
    }

    /**
     * @return object
     */
    public function decode()
    {
        $key = $this->key;
        $token = $this->token;
        JWT::$leeway = 15;
        $decoded = JWT::decode($token, $key, array('HS256'));
        return $decoded;
    }
}