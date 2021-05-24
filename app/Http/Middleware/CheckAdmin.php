<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
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
        $user = User::where('id', $decode->id)->first();
        if ($decode->level > 2 && $user->level_id > 2) {
            return $this->sendError('not-authorize');
        }
        return $next($request);
    }
}
