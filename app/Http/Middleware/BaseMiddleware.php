<?php

namespace App\Http\Middleware;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Response;

class BaseMiddleware
{
    public function checkJwt($jwt) {
        $key = 'bvMp8EzdcXZjUn0f5K3vOCblCL6xoRk4';
        $jwt = explode(' ', $jwt)[1];
        return JWT::decode($jwt, $key, array('HS256'));
    }
}
