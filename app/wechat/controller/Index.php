<?php


namespace app\wechat\controller;


use think\facade\Log;

class Index
{

    public function checkSignature()
    {
        $signature = input("param.signature");
        $timestamp = input("param.timestamp");
        $nonce = input("param.nonce");
        $token = "QiuJinCheng";
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode('', $tmpArr );
        $tmpStr = sha1( $tmpStr );


        if( $tmpStr == $signature ){
            ob_clean();
            Log::write(json_encode(input("param.")), 'info');
            Log::write(input("param.echostr"), 'info');
            echo input("param.echostr",0,"intval");
        }else{
            Log::write(json_encode(input("param.")), 'error');
        }
    }

}