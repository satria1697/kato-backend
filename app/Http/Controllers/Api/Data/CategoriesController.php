<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Models\Data\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends BaseController
{
    private $rules = [
        'name' => 'required|string'
    ];

    public function index(Request $request) {
        $filter = $request['filter'];
        $categories = Categories::query();
        if ($filter) {
            $categories = $categories->where('show', 1);
        }
        $categories = $categories->get();
        return $this->sendResponse($categories, 'success-get');
    }

    public function store(Request $request) {
        $input = $request->all();
        $validate = $this->validateData($input, $this->rules);

        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 200);
        }

        $data = new Categories();
        $data['name'] = $input['name'];
        $data['show'] = 1;
        if (!$data->save()) {
            return $this->sendError('cant-save');
        }
        return $this->sendResponse(true, 'success-save');
    }

    public function show($id) {
        $data = Categories::where('id', $id)->first();
        if (!$data) {
            return $this->sendError('not-found');
        }
        return $this->sendResponse($data, 'success-get');
    }

    public function update(Request $request, $id) {
        $input = $request->all();

        $validate = $this->validateData($input, $this->rules);

        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 200);
        }

        $data = Categories::where('id', $id)->first();
        $data['name'] = $input['name'];
        if (!$data->save()) {
            return $this->sendError('cant-save');
        }
        return $this->sendResponse(true, 'success-save');
    }

    public function destroy($id) {
        $data = Categories::where('id', $id)->first();
        if (!$data) {
            return $this->sendError('not-found');
        }

        $data['show'] = !$data['show'];
        if (!$data->save()) {
            return $this->sendError('cant-delete');
        }

        return $this->sendResponse(true, 'success-delete');
    }
}
