<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Mail\VerificationMail;
use App\Models\Data\Verification;
use App\Models\Profile;
use App\Models\User;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AuthController extends BaseController
{
    public function register(Request $request)
    {
        $rules = [
            'email' => 'required|user,email|email',
            'password' => 'required|string',
        ];
        $input = $request->all();
        $validate = $this->validateData($input, $rules);
        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 422);
        }

        $input['password'] = bcrypt($input['password']);
        $code = rand(111111111, 999999999);
        $input['verification_code'] = $code;
        $input['level_id'] = 8;

        $user = User::create($input);
        $success['name'] =  $user['name'];
        $success['level'] =  $user['level_id'];
        $jwtpayload = array(
            "email" => $user['email'],
            "code" => $code
        );
        $jwt = JWT::encode($jwtpayload, $this->key);
        $link = $jwt;
        $web = 'http://localhost:3000/register/verification?key=';
        Mail::to($input['email'])->send(new VerificationMail($web.$link));

        $profile = new Profile();
        $profile['name'] = $input['name'];
        $profile['user_id'] = $user['id'];
        $profile->save();

        $verification = new Verification();
        $verification['user_id'] = $user['id'];
        $verification->save();

        return $this->sendResponse($success, 'User register successfully.');
    }

    public function login(Request $request)
    {
        if(Auth::attempt(['email' => $request['email'], 'password' => $request['password']])) {
            $user = Auth::user();
            $verify = $user['email_verified_at'];
            if (!$verify) {
                return $this->sendError('email-not-verified', $verify);
            }
            if ($user['level_id'] > 8) {
                return $this->sendError('user-blocked');
            }
            $payload = array(
                "user" => $user['name'],
                "level" => $user['level_id'],
                "id" => $user['id'],
                "email" => $user['email'],
            );
            $jwt = JWT::encode($payload, $this->key);
            $user['token'] = $jwt;
            $success['jwt'] = $jwt;
            return $this->sendResponse($success, 'user-login');
        } else {
            return $this->sendError('Unauthorised.', ['error'=>'Unauthorised'], 200);
        }
    }

    public function logout(Request $request)
    {
        $decode = $this->getHeader($request);
        $user = User::where('id', $decode->id)->first();
        $user['token'] = '';
        if (!$user->save()) {
            return $this->sendError('logout-fail');
        }
        return $this->sendResponse('', 'success');
    }

    public function verification(Request $request)
    {
        $input = $request->all();
        $user = User::where('email', $input['email'])->first();
        if ($input['code'] != $user['verification_code']) {
            return $this->sendError('wrong-code');
        }
        $user['email_verified_at'] = Carbon::now();
        if (!$user->save()) {
            return $this->sendError('fail-update');
        }
        return $this->sendResponse('code-true', 'success-verif');
    }
}
