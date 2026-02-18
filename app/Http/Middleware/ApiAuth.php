<?php

namespace App\Http\Middleware;

use App\Models\MobileUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

class ApiAuth
{

    public function handle(Request $request, Closure $next)
    {
        $bearerToken = $request->header("token");

        if (!$bearerToken) {
            return response()->json(['isSuccess' => false], 401);
        }

        $token = PersonalAccessToken::findToken($bearerToken);

        if ($token) {
            $user = MobileUser::find($token->tokenable_id);

            if ($user) {
                Auth::login($user);

                return $next($request);
            }
        }

        return response()->json([
            'isSuccess' => false,
        ], 401);
    }
}
