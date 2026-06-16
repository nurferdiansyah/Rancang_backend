<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use App\Models\Notification;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->get();

        return response()->json([
            'status' => 'success',
            'data'   => $orders,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'payment_method'   => 'required|string',
            'shipping_address' => 'required|string',
            'shipping_name'    => 'required|string',
            'shipping_phone'   => 'required|string',
        ]);

        $cartItems = Cart::with('product')
            ->where('user_id', $request->user()->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Keranjang kosong',
            ], 400);
        }

        $totalAmount = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        $order = Order::create([
            'user_id'          => $request->user()->id,
            'status'           => 'pending',
            'total_amount'     => $totalAmount,
            'payment_method'   => $request->payment_method,
            'shipping_address' => $request->shipping_address,
            'shipping_name'    => $request->shipping_name,
            'shipping_phone'   => $request->shipping_phone,
        ]);

        foreach ($cartItems as $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $item->product_id,
                'quantity'   => $item->quantity,
                'price'      => $item->product->price,
            ]);
        }

        Cart::where('user_id', $request->user()->id)->delete();
        Notification::create([
            'user_id' => $request->user()->id,
            'title' => 'Pesanan Berhasil',
            'message' => 'Pesanan #' . $order->id . ' berhasil dibuat',
            'type' => 'order',
            'is_read' => 0,
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Order berhasil dibuat',
            'data'    => $order->load('items.product'),
        ]);
    }

    public function show(Request $request, $id)
    {
        $order = Order::with('items.product')
            ->where('user_id', $request->user()->id)
            ->where('id', $id)
            ->first();

        if (!$order) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Order tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data'   => $order,
        ]);
    }
}