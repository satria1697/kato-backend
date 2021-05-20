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

    public function sendError($error, $errorMessages = [], $code = 403) {
        $response = [
            'success' => false,
            'message' => $error,
        ];
        if(!empty($errorMessages)){
            $response['data'] = $errorMessages;
        }
        return Response::json($response, $code);
    }
}
