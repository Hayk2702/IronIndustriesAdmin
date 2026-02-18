<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckAuctionToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey = $request->token;

        if ($apiKey != env('CONNECT_AUCTION_TOKEN')) {
            if (!$request->wantsJson()) {
                return redirect()->back()->with('success', false)
                    ->with('message', 'Wrong request');
            } else {
                return response()->json(['success' => false, 'message' => 'Wrong request']);
            }
        }

        return $next($request);
    }
}
