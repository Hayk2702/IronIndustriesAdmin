<?php

namespace App\Http\Middleware;

use App\Models\ApiKeys;
use App\Models\MobileUser;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Laravel\Sanctum\PersonalAccessToken;

class CheckMobile
{

    public function handle(Request $request, Closure $next)
    {
        try {
            $keyToken = $request->header("key-token");
            if (!$keyToken) {
                return response()->json(['isSuccess' => false], 403);
            }
            $apiKey = ApiKeys::where("key", "=", $keyToken)->first();
            if($apiKey && $apiKey->key === $keyToken){
                return response()->json(['isSuccess' => false], 403);
            }
            $text = $this->decrypt($keyToken);

            if($text){
                $words = explode(" ", $text);
                if($words AND isset($words[0]) AND isset($words[2])){
                    $dateString = $words[0] . " " . $words[2];
                    if($dateString){
                        $date = Carbon::parse($dateString);
                        $now = Carbon::now();
                        if ($date->diffInMinutes($now) < 2) {
                            $apiKey = new ApiKeys();
                            $apiKey->key = $keyToken;
                            $apiKey->save();
                            return $next($request);
                        }
                    }

                }
            }
            return response()->json([
                'isSuccess' => false,
                'yourKey' => $keyToken,
            ], 403);
        }
        catch (\Exception $e){
            return response()->json([
                'isSuccess' => false,
                'message' => $e->getMessage(),
            ], 403);
        }
    }

    private function decrypt($encrypt){
        $decText = "";

        for ($i = 0; $i < strlen($encrypt); $i+=2) {
            $z = $encrypt[$i];
            $encrypt[$i] = $encrypt[$i+1];
            $encrypt[$i+1] = $z;
        }

        for ($i = 0; $i<strlen($encrypt);$i++){
            if($i % 3 == 0){
                $decText .= $encrypt[$i];
            }
        }



        $decText = base64_decode($decText);
        return $decText;
    }

    private function encrypt($text) {
        $encText = "";
        $encodedText = base64_encode($text);

        for ($i = 0; $i < strlen($encodedText); $i++) {
            $encText .= $encodedText[$i];
            $ascii = ord($encodedText[$i]);
            $prevChar = chr($ascii-1);
            $nextChar = chr($ascii+1);
            $encText .= $prevChar;
            $encText .= $nextChar;
        }

        for ($i = 0; $i < strlen($encText); $i+=2) {
            $z = $encText[$i];
            $encText[$i] = $encText[$i+1];
            $encText[$i+1] = $z;
        }
        return $encText;
    }
}
