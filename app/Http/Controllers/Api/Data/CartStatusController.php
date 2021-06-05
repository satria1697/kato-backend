<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Data\Cart;
use App\Models\Data\CartStatus;
use Illuminate\Http\Request;

class CartStatusController extends BaseController
{
    public function index()
    {
        $data = CartStatus::all();
        return $this->sendResponse($data, 'success-get');
    }

    public function updateCart(Request $request)
    {
        $rules = [
            'id' => 'required|numeric',
            'status' => 'required|string',
        ];

        $input = $request->all();
        $validate = $this->validateData($input, $rules);
        if ($validate->fails()) {
            return $this->sendError('validate-fail', $validate->errors(), 422);
        }

        $id = $input['id'];
        $status = $input['status'];
        $cart = Cart::where('id', $id)->first();
        $cart->update(
            [
            'status' => $status
            ]
        );
        $cart->save();
        return $this->sendResponse($cart, 'success-update');
    }
}
