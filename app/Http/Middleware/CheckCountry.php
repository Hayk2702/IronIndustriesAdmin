<?php

namespace App\Http\Middleware;

use App\Models\Country;
use App\Models\MobileUser;
use App\Models\MobileUserWallets;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class CheckCountry
{

    public function handle(Request $request, Closure $next)
    {
        $countryCode = ($request->has("country") AND $request->country) ? $request->country : null;

        if (!$countryCode OR !Country::where("code",$countryCode)->where("status",1)->first()) {
            return response()->json(['isSuccess' => false], 401);
        }
        if(Auth::user()->country_code != $countryCode){
            return response()->json(['isSuccess' => false], 401);
        }
        if(!MobileUserWallets::where("mobile_user_id",Auth::user()->id)->where("country_code",$request->country)->first()){
            $wallet = New MobileUserWallets();
            $wallet->mobile_user_id = Auth::user()->id;
            $wallet->amount = 0;
            $wallet->country_code =$request->country;
            $wallet->save();
        }

        return $next($request);
    }
}
