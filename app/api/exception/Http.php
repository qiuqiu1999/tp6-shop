<?php


namespace app\api\exception;


use think\exception\Handle;
use think\Response;
use Throwable;

class Http extends Handle
{
    private $httpCode = 500;

    public function render($request, Throwable $e): Response
    {
        if($e instanceof \think\Exception) {
            return show($e->getCode(), $e->getMessage());
        }

        if($e instanceof \think\Exception\HttpResponseException) {
            return parent::render($request, $e);
        }

        if (method_exists($e, 'getStatusCode')) {
            $httpCode = $e->getStatusCode();
        } else {
            $httpCode = $this->httpCode;
        }
        return show(config("status.error"), $e->getMessage(), [],$httpCode);
//        return parent::render($request, $e);
    }
}