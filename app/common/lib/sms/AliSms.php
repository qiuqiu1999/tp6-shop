<?php

declare(strict_types=1);
namespace app\common\lib\sms;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use think\facade\Log;

class AliSms implements SmsBase
{
    /**
     * 发送短信验证码
     */
    public static function sendSms(string $tel, int $code) :bool
    {
//        $res['Message'] = 'OK';
//        $res['Code'] = 'OK';
//        $res['date'] = date("Y-m-d H:i:s");
        return true;
        $codeData = [
            'code' => $code
        ];
//        return true;
        AlibabaCloud::accessKeyClient(config("AliSms.access_key_id"), config("AliSms.access_key_secret"))
            ->regionId(config("AliSms.region_id"))
            ->asDefaultClient();

        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                // ->scheme('https') // https | http
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->options([
                    'query' => [
                        'RegionId' => config("AliSms.region_id"),
                        'PhoneNumbers' => $tel,
                        'SignName' => "程序学习网",
                        'TemplateCode' => config("AliSms.template_code"),
                        'TemplateParam' => json_encode($codeData),
                    ],
                ])
                ->request();
            Log::info("alisms-smsCode-{$tel}-result" . json_encode($result->toArray()));
        } catch (ClientException $e) {
            Log::error("alisms-smsCode-{$tel}-ClientException" . json_encode($e->getErrorMessage()));
            return false;
        } catch (ServerException $e) {
            Log::error("alisms-smsCode-{$tel}-ServerException" . json_encode($e->getErrorMessage()));
            return false;
        }
        return true;
    }
}