<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\sms;
use App\Models\User;
use SoapClient;
use Http;

class SmsController extends Controller
{
    public static $number = 10000000242564;
    public static $smsEnabled = true;


    public static function sendSms(
        User $user, // receiver
        SmsTypes $type,
         $data, // array
         $mobile,
        bool $createLog = true,

    ) {
        // $u=User::where('mobile',$mobile)->first();
        // return 'sss';
        // $user->verify_code=1234;
        // $user->save();
        // return "ok";
        $url2 = 'https://sms.smsnegar.com:443/fullrest/api/send';
        $payload = [
            "UserName" => "yazdanplus",
            "Password" => "Aa123456",
            "Id" => 0,
            'DomainName' => 'yazd',
            "Smsbody" => $data,
            "Mobiles" => [
                $mobile,
            ],
            "SenderNumber" => "30001613"
        ];
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($url2, $payload);
        if ($response->successful()) {
            return "✅ پیامک با موفقیت برنامه‌ریزی شد.";
        } else {
            return "❌ خطا در ارسال پیامک: " . $response->status() . " | " . $response->body();
        }
        /*
        if (!self::$smsEnabled) // abort
            return array(
                'status',
                'Failed',
                'message',
                'Sms feature is disabled!'
            );
        if (!$user->id) // abort
            return array(
                'status',
                'Failed',
                'message',
                'Invalid user! (User have no id)'
            );


        $mobile = $user->mobile;
        $text = self::getSmsText($type, $data);

        if ($text == null)
            return array(
                'status',
                'Failed',
                'message',
                'Can not generate text for this Sms type!'
            );
        //        dd($mobile, $text);


        // send actual sms with panel
        $client = new SoapClient("http://185.237.85.55/smsWebService.asmx?wsdl");
        $oo = $client->__SoapCall(
            'sendSingleSMS',
            [
                array(
                    "username" => "ramin",
                    "password" => "R@min10300",
                    'domain' => 'sms.smsnegar',
                    "messageBody" => $text,
                    "recipientNumber" => $mobile,
                    "senderNumber" => self::$number
                )
            ]
        );


        // create log to 'sms' table if needed
        if ($createLog) {
            $smsLog = self::createSmsLog($user, $text, $type);
        }

*/
        // return all data
        // return array(
        //     'status',
        //     'Success',
        //     'mobile' => $mobile,
        //     'text' => $text,
        //     'data' => $data,
        //     'smsLog' => $smsLog ?? 'not created because of $createLog argument was false',
        // );
    }

    private static function getSmsText(SmsTypes $type, $data/*json*/): ?string
    {
        // todo : add other types
        switch ($type) {
            case SmsTypes::LOGIN_VERIFY_CODE:
                return '(آنتو) ' . 'کد ورود ' . 'Code: ' . $data['code'];
                break;
        }
        return null;
    }

    public static function createSmsLog(User $user, string $text, SmsTypes $type): sms
    {
        $smsLog = new sms();
        $smsLog->text = $text;
        $smsLog->number = SmsController::$number; // sender
        $smsLog->user_to_id = $user->id; // receiver
        $smsLog->type = $type->value;
        $smsLog->save();
        return $smsLog;
    }

}
