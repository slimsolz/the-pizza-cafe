<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Pizza;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    private const DELIVERY_FEE = 1.99;
    private const EXCHANGE_RATE = 0.85;

    public function createOrder(OrderRequest $orderRequest)
    {
        $amountInCart = Cart::where('cart_id', $orderRequest->cart_id)->sum('price');

        $order = new Order();
        $order->cart_id = $orderRequest->cart_id;
        $order->user_id = $orderRequest->user_id ?? null;
        $order->currency = $orderRequest->currency;
        $order->zip_code = $orderRequest->zip_code;
        $order->delivery_fee = self::DELIVERY_FEE;
        $order->delivery_address = $orderRequest->delivery_address;
        $order->sub_total = $this->getTotalCartAmount($amountInCart, $orderRequest->currency);
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'order placed successfully',
            'data' => $order,
        ], Response::HTTP_CREATED);
    }

    public function getOrderSummary($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'not found',
            ], Response::HTTP_NOT_FOUND);
        }
        $cart = Cart::where('cart_id', $order->cart_id)->get();
        foreach($cart as $ct) {
            $pizza = Pizza::with('photo')->where('id', $ct->pizza_id)->get();
            $ct["pizza"] = $pizza;
        }
        $order['cart_summary'] = $cart;

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    public function cancelOrder($id)
    {
        if ($order = Order::find($id)) {
            $order->delete();
            return response()->json([], Response::HTTP_NO_CONTENT);
        }

        return response()->json([
            'success' => false,
            'message' => 'not found',
        ], Response::HTTP_NOT_FOUND);
    }

    public function getOrderHistory(Request $request)
    {
        $order = Order::where('user_id', $request->auth->id)->get();
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'not found',
            ], Response::HTTP_NOT_FOUND);
        }
        foreach($order as $od) {
            $cart = Cart::where('cart_id', $od->cart_id)->get();
            foreach($cart as $ct) {
                $pizza = Pizza::with('photo')->where('id', $ct->pizza_id)->get();
                $ct["pizza"] = $pizza;
            }
            $od['cart_summary'] = $cart;
        }

        return response()->json([
            'success' => true,
            'data' => $order,
        ]);
    }

    private function getTotalCartAmount($price, $currency)
    {
        $fee = $price + self::DELIVERY_FEE;
        return round(($currency === 'euro') ? (self::EXCHANGE_RATE * $fee) : $fee, 2);
    }
}
