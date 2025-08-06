<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class VerifyNfcToken
{
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('Authorization');
        $expected = 'Bearer ' . env('NFC_SECRET_TOKEN');

        if (!Str::startsWith($token, $expected)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
