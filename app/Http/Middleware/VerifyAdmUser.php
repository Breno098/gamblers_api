<?php

namespace App\Http\Middleware;

use App\Http\Resources\ApiResource;
use Closure;
use Illuminate\Http\Request;

class VerifyAdmUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->method() !== 'GET' && $request->user()->type !== 'adm'){
            return response(new ApiResource(['user' => 'Unauthorized']), 401);
        }

        return $next($request);
    }
}
