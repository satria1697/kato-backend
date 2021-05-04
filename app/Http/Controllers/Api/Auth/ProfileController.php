<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{
    public function index() {
        $data = User::with('profile')->all();
        return $this->sendResponse($data, 'success');
    }

    public function show($id) {
        $data = User::with('profile')->where('id', $id)->first();
        return $this->sendResponse($data, 'success');
        if (!$data) {
            return $this->sendError('not-found');
        }
        return $this->sendResponse($data, 'success');
    }

    public function update(Request $request, $id) {
        $decode = $this->checkJwt($request['jwt']);
        if ($decode->level > 9) {
            return $this->sendError('not-authorized');
        }
        $input = $request->all();

        $data = Profile::where('user_id', $id)->first();
        $data['name'] = $input['name'];
        $data['company'] = $input['company'];
        $data['position'] = $input['position'];

        if (!$data->save()) {
            return $this->sendError('cant-save');
        }

        return $this->sendResponse($data->save(), 'success');
    }
}
