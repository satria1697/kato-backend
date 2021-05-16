<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Mail\CheckoutEmail;
use App\Models\Data\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class CartController extends BaseController
{
    public function store(Request $request) {
        $decode = $this->getHeader($request);
        $cart = new Cart();
        $cart['user_id'] = $decode->id;
        $cart['goods_id'] = $request['id'];
        $cart['buying'] = $request['buying'];
        $cart['status'] = $request['status'];
        if ($cart->save()) {
            return $this->sendResponse('cart-saved', $cart);
        } else {
            return $this->sendError("cart-fail");
        }
    }

    public function show(Request $request) {
        $decode = $this->getHeader($request);
        $id = $decode->id;
        $cart = User::with('cart', 'cart.goods', 'cart.status')->where('id', $id)->first();
        foreach ($cart['cart'] as $car) {
            $goods = $car['goods'];
            if ($goods['image']) {
                $goods['image'] = 'data:image/png;base64,' . base64_encode(Storage::get($goods['image']));
            }
            $car['buying'] += 0;
        }
        return $this->sendResponse($cart, 'success');
    }

    public function remove($id) {
        $cart = Cart::find($id);
        if (!$cart) {
            return $this->sendError('not-found');
        }
        if (!$cart->delete()) {
            return $this->sendError('cant-delete');
        }
        return $this->sendResponse($cart, 'delete-success');
    }

    public function checkout(Request $request) {
        $decode = $this->getHeader($request);
        $user = User::where('id', $decode->id)->get();
        $name = $user['name'];
        $to_email = $user['email'];
        $cart = Cart::with('goods')
            ->where('user_id', $decode->id)
            ->get();
        Mail::to($to_email)->send(new CheckoutEmail($cart, $name));
        return $this->sendResponse($to_email, 'success');
    }
}
