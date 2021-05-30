<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Http\Controllers\Controller;
use App\Models\Data\Cart;
use App\Models\Data\CartStatus;
use Illuminate\Http\Request;

class CartStatusController extends BaseController
{
    public function index() {
        $data = CartStatus::all();
        return $this->sendResponse($data, 'success-get');
    }

    public function updateCart(Request $request) {
        $id = $request->id;
        $status = $request->status;
        $cart = Cart::where('id', $id)->first();
        $cart->update([
            'status' => $status
        ]);
        $cart->save();
        return $this->sendResponse($cart, 'success-update');
    }
}
