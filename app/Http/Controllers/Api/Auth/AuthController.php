<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Models\User;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends BaseController
{
    public function register(Request $request) {
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        $user = User::create($input);
        $success['token'] =  $user->createToken('Kratom')->accessToken;
        $success['name'] =  $user['name'];
        $success['level_id'] =  8;
        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request) {
        if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])){
            $user = Auth::user();
            $key = 'bvMp8EzdcXZjUn0f5K3vOCblCL6xoRk4';
            $success['token'] = $user->createToken('Kratom')->accessToken;
            $payload = array(
                "user" => $user['name'],
                "level" => $user['level_id'],
                "id" => $user['id']
            );
            $jwt = JWT::encode($payload, $key);
            $success['jwt'] = $jwt;
            return $this->sendResponse($success, 'user-login');
        }
        else{
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised']);
        }
    }

    public function logout() {
        if(Auth::check()) {
            $user = Auth::user()->token()->revoke();
            if ($user) {
                return $this->sendResponse('Success', 'user-logout');
            } else {
                return $this->sendError('error-logout', ['error' => 'error-logout']);
            }
        }
        else {
            return $this->sendError('error-logout', ['error' => 'error-logout']);
        }
    }
}
