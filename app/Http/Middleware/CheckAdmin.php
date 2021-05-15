<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CheckAdmin extends BaseMiddleware
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
        $decode = $this->checkJwt($request->header('Authorization'));
        if ($decode->level > 2) {
            return Response::json('not-authorized', 403);
        }
        return $next($request);
    }
}
