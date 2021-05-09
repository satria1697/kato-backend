<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends BaseController
{
    public function index(Request $request) {
        $decode = $this->checkJwt($request['jwt']);
        if ($decode->level > 2) {
            return $this->sendError('not-authenticated');
        }
        $data = Level::all();
        return $this->sendResponse($data, 'success');
    }
}
