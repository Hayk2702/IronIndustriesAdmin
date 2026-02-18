<?php
namespace App\Http\Middleware;
use App\Models\DataLogger;
use Closure;
use DateTime;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ApiDataLogger
{
    private $startTime;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->startTime = microtime(true);
        return $next($request);
    }
    public function terminate($request, $response)
    {
        if ($request->ajax()) {
            $endTime = microtime(true);
            $filename = 'web_data_logger_' . date('d-m-y') . '.log';
            $dataToLog  = 'Time: '   . date("Y-m-d H:i:s") . "\n";
            $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
            $dataToLog .= 'IP Address: ' . $this->getRealIpAddr() . "\n";
            $dataToLog .= 'USER: ' . ((Auth::user())  ? Auth::user()->id : '') . "\n";
            $dataToLog .= 'headers: ' . $request->header('Authorization') . "\n";
            $dataToLog .= 'headers-all: ' . json_encode($request->header()) . "\n";
            $dataToLog .= 'URL: '    . $request->fullUrl() . "\n";
            $dataToLog .= 'Type: '    . 'web' . "\n";
            $dataToLog .= 'Method: ' . $request->method() . "\n";
            $dataToLog .= 'Input: '  . $request->getContent() . "\n";
            $dataToLog .= 'output: '  . $response->getContent() . "\n";

            Storage::append('logs/api/' . $filename, $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");
        }else{
            $endTime = microtime(true);
            $filename = 'api_data_logger_' . date('d-m-y') . '.log';
            $dataToLog  = 'Time: '   . date("Y-m-d H:i:s") . "\n";
            $dataToLog .= 'Duration: ' . number_format($endTime - LARAVEL_START, 3) . "\n";
            $dataToLog .= 'IP Address: ' . $this->getRealIpAddr() . "\n";
            $dataToLog .= 'USER: ' . ((Auth::user())  ? Auth::user()->id : '') . "\n";
            $dataToLog .= 'headers: ' . $request->header('Authorization') . "\n";
            $dataToLog .= 'headers-all: ' . json_encode($request->header()) . "\n";
            $dataToLog .= 'URL: '    . $request->fullUrl() . "\n";
            $dataToLog .= 'Type: '    . 'web' . "\n";
            $dataToLog .= 'Method: ' . $request->method() . "\n";
            $dataToLog .= 'Input: '  . $request->getContent() . "\n";
            $dataToLog .= 'output: '  . $response->getContent() . "\n";

            Storage::append('logs/api/' . $filename, $dataToLog . "\n" . str_repeat("=", 20) . "\n\n");
        }
    }

    function getRealIpAddr()
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
