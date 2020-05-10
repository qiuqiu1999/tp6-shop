<?php


namespace app\common\lib;

/**
 * Curl类库
 * Class Curl
 * @package app\common\lib
 */
class Curl
{
    public static function externalRequestPost($url, $data = array(), $contentType = 'application/x-www-form-urlencoded')
    {
        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url); // 要访问的地址
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0); // 对认证证书来源的检查
        //curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 1); // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // 模拟用户使用的浏览器
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1); // 使用自动跳转
        curl_setopt($curl, CURLOPT_AUTOREFERER, 1); // 自动设置Referer
        curl_setopt($curl, CURLOPT_POST, 1); // 发送一个常规的Post请求
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data); // Post提交的数据包
        curl_setopt($curl, CURLOPT_TIMEOUT, 30); // 设置超时限制防止死循环
        curl_setopt($curl, CURLOPT_HEADER, 0); // 显示返回的Header区域内容
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1); // 获取的信息以文件流的形式返回
        $tmpInfo = curl_exec($curl); // 执行操作
        if (curl_errno($curl)) {
            return 'Errno' . curl_error($curl);
        }
        curl_close($curl); // 关键CURL会话
        $data = json_decode($tmpInfo);
        return $data;
    }

    public static function curl_request($url, $data = null, $method = 'get', $https = true)
    {
        //1.初识化curl
        $ch = curl_init($url);
        //2.根据实际请求需求进行参数封装
        //返回数据不直接输出
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //如果是https请求
        if ($https === true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }
        //如果是post请求
        if ($method === 'post') {
            //开启发送post请求选项
            curl_setopt($ch, CURLOPT_POST, true);
            //发送post的数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        }
        //3.发送请求
        $result = curl_exec($ch);
        //4.返回返回值，关闭连接
        curl_close($ch);
        return $result;
    }

}