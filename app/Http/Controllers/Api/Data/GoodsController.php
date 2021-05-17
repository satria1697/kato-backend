<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Models\Data\Goods;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GoodsController extends BaseController
{
    public function store(Request $request) {
        $base64 = $request['image'];
        if ($base64) {
            $image = base64_decode(str_replace('data:image/jpg;base64,', '', $base64));
            $path = 'images/'.$request['name'].'.jpg';
            Storage::put($path, $image);
        } else {
            $path = null;
        }
        $goods = new Goods();
        $goods['name'] = $request['name'];
        $goods['description'] = $request['description'];
        $goods['price'] = $request['price'];
        $goods['stock'] = $request['stock'];
        $goods['image'] = $path;
        $goods['brief'] = $request['brief'];
        $goods['category_id'] = $request['categoryId'];
        if ($goods->save()) {
            return $this->sendResponse($goods, 'success');
        } else {
            return $this->sendError('error', ['error' => 'error-save']);
        }
    }

    public function index(Request $request) {
        $category_id = $request['category'];
        $filter = $request['filter'];
        $goods = Goods::query();
        if ($category_id) {
            $goods = $goods->where('category_id', $category_id);
        }
        if ($filter) { //true
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

    public function delete($id) {
        $goods = Goods::find($id);
        if ($goods) {
            if ($goods->delete()) {
                return $this->sendResponse([], 'success-delete');
            } else {
                return $this->sendError('error', ['error' => 'error-delete']);
            }
        } else {
            return $this->sendError('error', ['error' => 'not-found']);
        }
    }

    public function view($id) {
        $goods = Goods::with('category')->where('id', $id)->first();
        if ($goods['image']) {
            $goods['image'] = 'data:image/jpg;base64,' . base64_encode(Storage::get($goods['image']));
        }
        if ($goods) {
            return $this->sendResponse($goods, 'success');
        } else {
            return $this->sendError('error', ['error' => 'not-found']);
        }
    }

    public function update(Request $request, $id) {
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
        }
         else {
            $path = null;
        }
        $goods['image'] = $path;
        $goods['name'] = $request['name'];
        $goods['description'] = $request['description'];
        $goods['price'] = $request['price'];
        $goods['stock'] = $request['stock'];
        $goods['brief'] = $request['brief'];
        $goods['category_id'] = $request['categoryId'];
        $goods->save();
        return $this->sendResponse($goods, 'success');
    }
}
