<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Models\Data\Verification;
use App\Models\User;
use Illuminate\Http\Request;

class VerificationController extends BaseController
{
    public function update(Request $request, $id) {
        $decode = $this->checkJwt($request['jwt']);
        $data = Verification::where('id', $id)->first();
        if (!$data) {
            return $this->sendError('not-found');
        }
        $input = $request->all();
        $base64_id = $this->base64ToImg('verification/'.$decode->email, $input['idCard']);
        $base64_company = $this->base64ToImg('verification/'.$decode->email, $input['companyCard']);

        if ($base64_id) {
            $data["id_card"] = $base64_id;
            $data["id_card_status"] = 2;
        }
        if ($base64_company) {
            $data["company_card"] = $base64_company;
            $data["company_card_status"] = 2;
        }
        $data["company_id"] = $input['companyId'];
        if (!$data->save()) {
            return $this->sendError('cant-save');
        }
        return $this->sendResponse(true, 'success');
    }

    public function updateStatus(Request $request, $id) {
        $decode = $this->checkJwt($request['jwt']);
        if ($decode->level > 2) {
            $this->sendError('not-authenticated');
        }
        $data = Verification::where('id', $id)->first();
        if (!$data) {
            return $this->sendError('not-found');
        }
        $input = $request->all();
        $data['company_card_status'] = $input['companyCardStatus'];
        $data['id_card_status'] = $input['idCardStatus'];

        if ($input['level'] > 0) {
            $user = User::where('id', $decode->id)->first();
            $user['level_id'] = $input['level'];
            $user->save();
        }

        if (!$data->save()) {
            return $this->sendError('cant-save');
        }
        return $this->sendResponse(true, 'success');
    }
}
