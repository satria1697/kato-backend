<?php

namespace App\Http\Middleware;

use Firebase\JWT\JWT;

class BaseMiddleware
{
    public function checkJwt($jwt) {
        $key = 'bvMp8EzdcXZjUn0f5K3vOCblCL6xoRk4';
        $jwt = explode(' ', $jwt)[0];
        return JWT::decode($jwt, $key, array('HS256'));
    }
}
