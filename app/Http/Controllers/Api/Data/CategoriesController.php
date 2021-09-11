<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Models\Data\Categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoriesController extends BaseController
{
    public function index(Request $request)
    {
        $search = $request->search;
        $filter = $request->filter;

        $split = explode(':', $search);
        $searchby = 'name';

        if (count($split) > 1) {
            $searchby = $split[0];
            $search = $split[1];
        }

        $categories = Categories::query();

        if ($search) {
            $categories->where($searchby, 'like', '%'.$search.'%');
        }
        if ($search && $searchby === "name") {
            $categories->where($searchby.'_id', 'like', '%'.$search.'%');
        }
        if ($filter) {
            $categories = $categories->where('show', 1);
        }
        $categories = $categories->get();
        return $this->sendResponse($categories, 'success-get');
    }

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|string',
            'name_id' => 'required|string',
        ];

        $input = $request->all();
        $validate = $this->validateData($input, $rules);
        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 422);
        }

        $data = new Categories();
        $data['name'] = $input['name'];
        $data['name_id'] = $input['name_id'];
        $data['show'] = 1;
        if (!$data->save()) {
            return $this->sendError('cant-save');
        }
        return $this->sendResponse(true, 'success-save');
    }

    public function show($id)
    {
        $data = Categories::where('id', $id)->first();
        if (!$data) {
            return $this->sendError('not-found');
        }
        return $this->sendResponse($data, 'success-get');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'name' => 'required|string',
            'name_id' => 'required|string',
        ];

        $input = $request->all();
        $validate = $this->validateData($input, $rules);
        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 422);
        }

        $data = Categories::where('id', $id)->first();
        $data['name'] = $input['name'];
        $data['name_id'] = $input['name_id'];
        if (!$data->save()) {
            return $this->sendError('cant-save');
        }
        return $this->sendResponse(true, 'success-save');
    }

    public function destroy($id)
    {
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
