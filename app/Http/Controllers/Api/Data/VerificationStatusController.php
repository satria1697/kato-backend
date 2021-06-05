<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Data\VerificationStatus;
use Illuminate\Http\Request;

class VerificationStatusController extends BaseController
{
    public function index()
    {
        $data = VerificationStatus::all();
        return $this->sendResponse($data, 'success');
    }
}
