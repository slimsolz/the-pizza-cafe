<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CartQuantityRequest;
use App\Http\Requests\CartRequest;
use App\Models\Cart;
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class CartController extends Controller
{
    private const SMALL = 0;
    private const MEDIUM = 0.50;
    private const LARGE = 1;

    public function createCart(Request $request)
    {
        return response()->json($this->generateCartId());
    }

    public function addToCart(CartRequest $cartRequest, $pizza_id)
    {
        $cart = new Cart();

        $cart_id = $cartRequest->cart_id;
        $quantity = $cartRequest->quantity;
        $size = $cartRequest->size;
        $pizza = Pizza::with('photo')->find($pizza_id);

        if (!$pizza) {
            return response()->json([
                'success' => false,
                'message' => 'pizza doesn\'t exist'
            ], Response::HTTP_NOT_FOUND);
        }

        if (Cart::where(['cart_id' => $cart_id, 'pizza_id' => $pizza_id])->first()) {
            return response()->json([
                'success' => false,
                'message' => 'pizza already added to cart'
            ], Response::HTTP_CONFLICT);
        }

        $cart->cart_id = $cart_id;
        $cart->pizza_id = $pizza_id;
        $cart->quantity = $quantity;
        $cart->size = $size;
        $cart->price = $this->getItemTotal($pizza->price, $quantity, $size);
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'added to cart',
            'cart' => $cart,
            'pizza' => $pizza
        ]);
    }

    public function updateItemInCart(CartQuantityRequest $cartQuantityRequest, $cart_id, $item_id)
    {
        $cart = Cart::where(['cart_id' => $cart_id, 'pizza_id' => $item_id])->first();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ], Response::HTTP_NOT_FOUND);
        }
        $newQuantity = $cartQuantityRequest->quantity ?? $cart->quantity;
        $newSize = $cartQuantityRequest->size ?? $cart->size;

        $pizza = Pizza::with('photo')->find($item_id);
        $cart->quantity = $newQuantity;
        $cart->size = $newSize;
        $cart->price = $this->getItemTotal($pizza->price, $cartQuantityRequest->quantity, $newSize);
        $cart->save();

        return response()->json([
            'success' => true,
            'message' => 'successfully updated',
            'cart' => $cart,
        ]);
    }

    public function removeItemFromCart($cart_id, $item_id)
    {
        $cart = Cart::where(['cart_id' => $cart_id, 'pizza_id' => $item_id])->delete();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
        ], Response::HTTP_NO_CONTENT);
    }

    public function emptyCart($cart_id)
    {
        $cart = Cart::where(['cart_id' => $cart_id])->delete();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
        ], Response::HTTP_NO_CONTENT);
    }

    public function getCartTotalPrice(Request $request, $cart_id)
    {
        $cart_total = Cart::where(['cart_id' => $cart_id])->sum('price');
        if (!$cart_total) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'total' => $cart_total
        ]);
    }

    public function viewCart($cart_id)
    {
        $cart = Cart::where(['cart_id' => $cart_id])->get();
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'cart' => $cart
        ]);
    }

    private function getItemTotal($item_price, $quantity, $size='S')
    {
        $sizeCost =  $size === 'M' ? self::MEDIUM : ($size === 'L' ? self::LARGE : self::SMALL);
        return ($item_price * $quantity) + ($sizeCost * $quantity);
    }

    private function generateCartId()
    {
        return Str::random(20);
    }
}
