<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Api\BaseController;
use App\Mail\testEmail;
use App\Models\Data\Cart;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception\InvalidOrderException;

class CartController extends BaseController
{
    public function store(Request $request) {
        $jwt = $this->checkJwt($request->input('jwt'));
        if ($jwt->level > 8) {
            return $this->sendError("Unauthorized");
        }
        $cart = new Cart();
        $cart['user_id'] = $jwt->id;
        $cart['kratom_id'] = $request['id'];
        $cart['buying'] = $request['buying'];
        $cart['status'] = $request['status'];
        if ($cart->save()) {
            return $this->sendResponse('cart-saved', $cart);
        } else {
            return $this->sendError("cart-fail");
        }
    }

    public function show(Request $request) {
        $jwt = $this->checkJwt($request->input('jwt'));
        $id = $jwt->id;
        $cart = User::with('cart', 'cart.kratom')->where('id', $id)->first();
        foreach ($cart['cart'] as $car) {
            $kratom = $car['kratom'];
            if ($kratom['image']) {
                $kratom['image'] = 'data:image/png;base64,' . base64_encode(Storage::get($kratom['image']));
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
        $decode = $this->checkJwt($request['jwt']);
        $to_name = 'andhika';
        $to_email = 'andhikasatriab@gmail.com';
        $data = array(
            'name' => 'testonetwo',
            'body' => 'testBody'
        );
//        return $this->sendResponse(\config('mail.from'), 'success');
        Mail::to('andhikasatriab@gmail.com')->send(new testEmail());
        return $this->sendResponse('andhikasatriab@gmail.com', 'success');
    }
}
