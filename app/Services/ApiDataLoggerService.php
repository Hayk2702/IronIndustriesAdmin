<?php

namespace App\Services;

use App\Models\DataLogger;
use Illuminate\Support\Facades\Auth;
use DateTime;
class ApiDataLoggerService
{
    public function __construct()
    {
        //
    }

    public static function userActionsToDataLoggers($request,$type=null,$input=null,$user)
    {
        if (config('app.env') != 'local') {
            $endTime = microtime(true);
            $filename = 'api_datalogger_new_' . date('d-m-y') . '.log';
            $dataToLog  = 'Time: '   . gmdate("F j, Y, g:i a") . "\n";
            $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
            $dataToLog .= 'IP Address: ' .self::getRealIpAddr() . "\n";
            $dataToLog .= 'USER: ' . (($user)  ? $user->id : '') . "\n";
            $dataToLog .= 'headers: ' . $request->header('Authorization') . "\n";
            $dataToLog .= 'URL: '    . $request->fullUrl() . "\n";
            $dataToLog .= 'Type: '    . $type . "\n";
            $dataToLog .= 'Method: ' . $request->method() . "\n";
            $dataToLog .= 'Input: '  . json_encode($input) . "\n";
            $dataToLog .= 'output: '  . json_encode(['success'=>true]) . "\n";

            \File::append( storage_path('logs' . DIRECTORY_SEPARATOR ."api".DIRECTORY_SEPARATOR . $filename), $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");

            $log = New DataLogger();
            $log->time = new DateTime('now');
            $log->duration = number_format($endTime - LARAVEL_START, 3);
            $log->ip_address = self::getRealIpAddr();
            $log->user_id = (($user)  ? $user->id : '');
            $log->organization_id = (($user)  ? $user->organization_id : '');
            $log->headers = $request->header('Authorization');
            $log->url = $request->fullUrl();
            $log->type = $type;
            $log->method = $request->method();
            $log->input = json_encode($input);
            $log->output = json_encode(['success'=>true]);
            $log->save();
        }
    }

    static function getRealIpAddr()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
            $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
            $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

}
