<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Models\Data\Categories;
use Illuminate\Http\Request;

class CategoriesController extends BaseController
{
    public function index() {
        $categories = Categories::all();
        return $this->sendResponse($categories, 'success-get');
    }
}
