<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\BaseController;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends BaseController
{
    public function index()
    {
        $data = Level::all();
        return $this->sendResponse($data, 'success');
    }
}
