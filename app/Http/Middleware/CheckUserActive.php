<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CheckUserActive extends BaseMiddleware
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
        $user = User::where('id', $decode->id)->first();
        if ($decode->level > 8 && $decode->level_id > 8) {
            return $this->sendError('not-authorize');
        }
        return $next($request);
    }
}
