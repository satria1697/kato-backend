<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Models\Data\Goods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoodsController extends BaseController
{
    public function store(Request $request)
    {
        $rules = [
            'image' => 'starts_with:data:image/|nullable',
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|number',
            'stock' => 'required|number',
            'brief' => 'required|string',
            'categoryId' => 'required|number',
        ];

        $input = $request->all();
        $validate = $this->validateData($input, $rules);
        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 422);
        }

        $base64 = $input['image'];
        if ($base64) {
            $image = base64_decode(str_replace('data:image/jpg;base64,', '', $base64));
            $path = 'images/'.$input['name'].'.jpg';
            Storage::put($path, $image);
        } else {
            $path = null;
        }
        $goods = new Goods();
        $goods['name'] = $input['name'];
        $goods['description'] = $input['description'];
        $goods['price'] = $input['price'];
        $goods['stock'] = $input['stock'];
        $goods['image'] = $path;
        $goods['brief'] = $input['brief'];
        $goods['category_id'] = $input['categoryId'];
        if (!$goods->save()) {
            return $this->sendError('error', ['error' => 'error-save']);
        }
        return $this->sendResponse($goods, 'success');
    }

    public function index(Request $request)
    {
        $input = $request->all();

        $search = $input['search'];
        $filter = $input['filter'];
        $category_id = $input['category'];

        $split = explode(':', $search);
        $searchby = 'name';

        if (count($split) > 1) {
            $searchby = $split[0];
            $search = $split[1];
        }

        $goods = Goods::query();
        if ($search) {
            $goods->where($searchby, 'like', '%'.$search.'%');
        }
        if ($category_id) {
            $goods = $goods->where('category_id', $category_id);
        }
        if ($filter) {
            $goods = $goods->with(['category' => fn($q) => $q->where('show', 1)]);
            $goods = $goods->get();
            $goods = $goods->filter(fn($item) => $item['category'] !== null)->values();
        } else {
            $goods = $goods->with(['category']);
            $goods = $goods->get();
        }
        foreach ($goods as $good) {
            if ($good['image']) {
                $good['image'] = 'data:image/png;base64,' . base64_encode(Storage::get($goods['image']));
            }
        }
        return $this->sendResponse($goods, 'success');
    }

    public function delete($id)
    {
        $goods = Goods::find($id);
        if (!$goods) {
            return $this->sendError('error', ['error' => 'not-found']);
        }
        if (!$goods->delete()) {
            return $this->sendError('error', ['error' => 'error-delete']);
        }
        return $this->sendResponse($id, 'success-delete');
    }

    public function view($id)
    {
        $goods = Goods::with('category')->where('id', $id)->first();
        if ($goods['image']) {
            $goods['image'] = 'data:image/jpg;base64,' . base64_encode(Storage::get($goods['image']));
        }
        if (!$goods) {
            return $this->sendError('error', ['error' => 'not-found']);
        }
        return $this->sendResponse($goods, 'success');
    }

    public function update(Request $request, $id)
    {
        $rules = [
            'image' => 'starts_with:data:image/|nullable',
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|number',
            'stock' => 'required|number',
            'brief' => 'required|string',
            'categoryId' => 'required|number',
        ];

        $input = $request->all();
        $validate = $this->validateData($input, $rules);
        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 422);
        }

        $goods = Goods::find($id);
        if (!$goods) {
            return $this->sendError('error', ['error' => 'not-found']);
        }
        $base64 = $request['image'];
        if ($base64) {
            $image = base64_decode(str_replace('data:image/jpg;base64,', '', $base64));
            $path = 'images/'.$request['name'].'.jpg';
            Storage::put($path, $image);
        } else if ($goods['image']) {
            $path = $goods['image'];
        } else {
            $path = null;
        }
        $goods['image'] = $path;
        $goods['name'] = $request['name'];
        $goods['description'] = $request['description'];
        $goods['price'] = $request['price'];
        $goods['stock'] = $request['stock'];
        $goods['brief'] = $request['brief'];
        $goods['category_id'] = $request['categoryId'];
        if (!$goods->save()) {
            return $this->sendError('error-save');
        }
        return $this->sendResponse($goods, 'success');
    }
}
