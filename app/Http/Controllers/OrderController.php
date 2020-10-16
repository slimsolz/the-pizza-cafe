<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\Cart;
use App\Models\Order;
use App\Models\User;
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
        return Order::find($id);
    }

    private function getTotalCartAmount($price, $currency)
    {
        $fee = $price + self::DELIVERY_FEE;
        return round(($currency === 'euro') ? (self::EXCHANGE_RATE * $fee) : $fee, 2);
    }
}
