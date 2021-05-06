<?php

namespace App\Http\Controllers\Api;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class BaseController extends Controller
{
    public function checkJwt($jwt) {
        $key = 'bvMp8EzdcXZjUn0f5K3vOCblCL6xoRk4';
        return JWT::decode($jwt, $key, array('HS256'));
    }

    public function sendResponse($result, $message) {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return Response::json($response, 200);
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

    public function base64ToImg($where, $base64) {
        $now = $this->unixNow();
        if ($base64) {
            $image = base64_decode(str_replace('data:image/png;base64,', '', $base64));
            $path = 'images/'.$where.'/'.$now.'.jpg';
            Storage::put($path, $image);
        } else {
            $path = null;
        }
        return $path;
    }

    public function imgToBase64($where) {
        return 'data:image/jpg;base64,' . base64_encode(Storage::get($where));
    }

    public function unixNow() {
        return Carbon::now()->unix();
    }
}
