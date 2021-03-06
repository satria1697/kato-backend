<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;

class ProfileController extends BaseController
{
    public function index()
    {
        $data = User::with('profile', 'verification')->get();
        return $this->sendResponse($data, 'success');
    }

    public function show($id)
    {
        $data = User::with('level', 'profile', 'verification', 'verification.id_status', 'verification.company_status')->where('id', $id)->first();
        if (!$data) {
            return $this->sendError('not-found');
        }

        if ($data->verification['id_card']) {
            $data->verification['id_card'] = $this->imgToBase64($data->verification['id_card']);
        }

        if ($data->verification['company_card']) {
            $data->verification['company_card'] = $this->imgToBase64($data->verification['company_card']);
        }

        return $this->sendResponse($data, 'success');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'string|min:5',
            'company' => 'string|min:5',
            'position' => 'string|min:1'
        ];

        $input = $request->all();
        $validate = $this->validateData($input, $rules);
        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 422);
        }

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
