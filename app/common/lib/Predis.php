<?php


namespace app\common\lib;

/**
 * 单例 封装Predis
 * Class Predis
 * @package app\common\lib
 */
class Predis
{
    public static $_instance = null;
    public $redis = '';
    public static $pre = "sms_";

    private function __construct()
    {
        $this->redis = new \Redis();
        $this->redis->connect("120.77.159.232", 7481);
        $this->redis->auth('lswlcccom');
    }

    private function __clone()
    {
        // TODO: Implement __clone() method.
    }

    /**
     * 获取实例
     * @return Redis|\Swoole\Coroutine\Redis
     */
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * set
     * @param $key
     * @param $value
     * @param null $timeout
     * @return bool|string
     */
    public function set($key, $value, $timeout = null)
    {
        if (!$key) {
            return '';
        }
        if (is_array($value)) {
            $value = json_encode($value);
        }
        if (!$timeout) {
            return $this->redis->set($key, $value);
        }
        return $this->redis->setex($key, $timeout, $value);
    }

    /**
     * @param $key
     * @return bool|string
     */
    public function get($key)
    {
        if (!$key) {
            return '';
        }
        return $this->redis->get($key);
    }

    /**
     * @param $key
     * @param $value
     * @return int
     */
    public function sAdd($key, $value)
    {
        return $this->redis->sAdd($key, $value);
    }

    /**
     * @param $key
     * @param $value
     * @return int
     */
    public function sRem($key, $value)
    {
        return $this->redis->sRem($key, $value);
    }

    public function sMembers($key)
    {
        return $this->redis->sMembers($key);
    }

    public function del($key)
    {
        return $this->del($key);
    }
}